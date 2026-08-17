<?php

/**
 * 1. Registro da Metabox e Scripts
 */
function adicionar_metabox_receita() {
    add_meta_box(
        'receita_metabox',
        'Informações da Receita (Otimizado - Schema Supra-Sumo)',
        'renderizar_metabox_receita',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_metabox_receita');

// Carregar Scripts e Estilos no Admin para Autocomplete e Repeater
function sts_admin_receitas_enqueue_scripts($hook) {
    global $post;
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if ($post && $post->post_type === 'post') {
            wp_enqueue_script('jquery-ui-autocomplete');
            // Incluir estilos CSS do Autocomplete do próprio WP Admin
            wp_enqueue_style('wp-jquery-ui-dialog');
        }
    }
}
add_action('admin_enqueue_scripts', 'sts_admin_receitas_enqueue_scripts');

/**
 * 2. Interface do Painel Administrativo
 */
function renderizar_metabox_receita($post) {
    global $wpdb;
    wp_nonce_field('salvar_receita_meta', 'receita_nonce');
    
    // Tabelas do Banco
    $table_rel = $wpdb->prefix . 'receita_ingredientes_rel';
    $table_ing = $wpdb->prefix . 'receita_ingredientes_mestre';
    
    // Recuperação dos valores clássicos
    $tempo_preparo   = get_post_meta($post->ID, '_tempo_preparo', true);
    if (empty($tempo_preparo)) $tempo_preparo = get_post_meta($post->ID, 'tempo', true);
    
    $tempo_cozimento = get_post_meta($post->ID, '_tempo_cozimento', true);
    $porcoes         = get_post_meta($post->ID, '_porcoes', true);
    if (empty($porcoes)) $porcoes = get_post_meta($post->ID, 'rendimento', true);
    
    $dificuldade     = get_post_meta($post->ID, '_dificuldade', true);
    if (empty($dificuldade)) $dificuldade = get_post_meta($post->ID, 'dificuldade', true);

    $recipe_cuisine  = get_post_meta($post->ID, '_recipe_cuisine', true) ?: 'Brasileira';
    $video_url       = get_post_meta($post->ID, '_video_url', true);
    $diet_type       = get_post_meta($post->ID, '_diet_type', true);
    
    // Dados nutricionais salvos
    $calorias        = get_post_meta($post->ID, '_calorias', true);
    $carboidratos    = get_post_meta($post->ID, '_carboidratos', true);
    $proteinas       = get_post_meta($post->ID, '_proteinas', true);
    $gorduras        = get_post_meta($post->ID, '_gorduras', true);
    $nutri_serving   = get_post_meta($post->ID, '_nutri_serving', true);
    $nutri_source    = get_post_meta($post->ID, '_nutri_source', true);
    $nutri_is_auto   = get_post_meta($post->ID, '_nutri_auto_calculated', true);
    
    $informacoes_adicionais = get_post_meta($post->ID, '_informacoes_adicionais', true);
    $utensilios_text        = get_post_meta($post->ID, '_utensilios', true);
    $faq_perguntas          = get_post_meta($post->ID, '_faq_perguntas', true);
    $faq_respostas          = get_post_meta($post->ID, '_faq_respostas', true);
    $conclusao              = get_post_meta($post->ID, '_conclusao', true);
    $link_interno_url       = get_post_meta($post->ID, '_link_interno_url', true);
    $link_interno_texto     = get_post_meta($post->ID, '_link_interno_texto', true);
    
    // Carregar Utensílios de Afiliados Selecionados
    $utensilios_selecionados = get_post_meta($post->ID, '_receita_utensilios_afiliados', true);
    if (!is_array($utensilios_selecionados)) $utensilios_selecionados = [];
    
    // Carregar Ingredientes Estruturados do Banco
    $ingredientes_salvos = $wpdb->get_results($wpdb->prepare(
        "SELECT r.*, i.name as ingrediente_nome 
         FROM $table_rel r 
         JOIN $table_ing i ON r.ingredient_id = i.id 
         WHERE r.recipe_id = %d 
         ORDER BY r.sort_order ASC",
        $post->ID
    ));
    
    // Unidades de medidas suportadas
    $unidades = array(
        'g'           => 'Gramas (g)',
        'kg'          => 'Quilos (kg)',
        'ml'          => 'Mililitros (ml)',
        'l'           => 'Litros (l)',
        'xicara_cha'  => 'Xícara de Chá',
        'colher_sopa' => 'Colher de Sopa',
        'colher_cha'  => 'Colher de Chá',
        'colher_cafe' => 'Colher de Café',
        'unidade'     => 'Unidade',
        'pitada'      => 'Pitada',
        'a_gosto'     => 'A Gosto',
        'fatia'       => 'Fatia',
        'dente'       => 'Dente',
        'ramo'        => 'Ramo'
    );
    
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Copiar Prompt do Assistente
        $('#gan_copy_prompt_btn').click(function() {
            var btn = $(this);
            var title = $('#title').val() || '<?php echo esc_js(get_the_title($post->ID)); ?>';
            var focusKeyword = $('input[name="sts_focus_keyword"]').val() || $('input[name="_sts_focus_keyword"]').val() || '';
            
            var tags = [];
            $('#tagsdiv-post_tag .tagchecklist span').each(function() {
                tags.push($(this).text().replace('X', '').trim());
            });
            var keywordText = focusKeyword;
            if (tags.length > 0) {
                keywordText += (keywordText ? ', ' : '') + tags.join(', ');
            }
            if (!keywordText) {
                keywordText = 'Palavra-chave focada do post';
            }

            var prompt = $('#gan_prompt_template').val()
                .replace('[TITULO]', title)
                .replace('[PALAVRA_CHAVE]', keywordText);

            navigator.clipboard.writeText(prompt).then(function() {
                var origText = btn.text();
                btn.text('Copiado! ✓').css('background', '#46b450').css('border-color', '#46b450');
                setTimeout(function() {
                    btn.text(origText).css('background', '#ec5b13').css('border-color', '#ec5b13');
                }, 2000);
            });
        });

        // Alternar entre editor clássico e estruturado de ingredientes
        $('#ativar_editor_estruturado').click(function() {
            $('#editor-ingredientes-classico').hide();
            $('#editor-ingredientes-estruturado').show();
            $('#ingredientes_modo_uso').val('estruturado');
            // Se o container estiver vazio, adiciona o primeiro grupo padrão
            if ($('#estruturado-grupos-container .estruturado-grupo-box').length === 0) {
                adicionarGrupoEstruturado('Ingredientes');
            }
        });

        $('#ativar_editor_classico').click(function() {
            if (confirm('Atenção: Ao voltar para o editor clássico, o WordPress utilizará o texto livre configurado anteriormente. O editor estruturado não será perdido, mas as alterações feitas lá não aparecerão no front-end até você reativá-lo. Deseja prosseguir?')) {
                $('#editor-ingredientes-estruturado').hide();
                $('#editor-ingredientes-classico').show();
                $('#ingredientes_modo_uso').val('classico');
            }
        });

        // Inicializar Autocomplete nas linhas de ingredientes
        function aplicarAutocomplete(inputElement) {
            $(inputElement).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            action: 'sts_buscar_ingredientes',
                            q: request.term
                        },
                        success: function(data) {
                            if(data.success) {
                                response($.map(data.data, function(item) {
                                    return {
                                        label: item.text,
                                        value: item.text,
                                        id: item.id
                                    };
                                }));
                            }
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    var parentRow = $(this).closest('.estruturado-ingrediente-row');
                    parentRow.find('.ingrediente-id-val').val(ui.item.id);
                }
            });
        }

        // Função para Adicionar um Grupo Estruturado
        function adicionarGrupoEstruturado(nomeGrupo) {
            nomeGrupo = nomeGrupo || 'Novo Grupo';
            var groupIndex = $('#estruturado-grupos-container .estruturado-grupo-box').length;
            var groupHtml = `
            <div class="estruturado-grupo-box" style="background:#f9f9f9; padding:15px; border-left: 4px solid #ec5b13; margin-bottom: 20px; border-radius: 0 8px 8px 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <input type="text" class="grupo-title-input" value="${nomeGrupo}" placeholder="Nome do Grupo (ex: Massa)" style="font-weight:bold; font-size:1.1em; width:80%;">
                    <button type="button" class="remove-grupo-btn button" style="color:#d63638;">Remover Grupo</button>
                </div>
                <table class="wp-list-table widefat fixed striped" style="margin-bottom:10px; background:#fff;">
                    <thead>
                        <tr>
                            <th style="width:40%;">Ingrediente (Autocomplete)</th>
                            <th style="width:15%;">Qtd.</th>
                            <th style="width:20%;">Unidade</th>
                            <th style="width:20%;">Override de Exibição (Ex: a gosto)</th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody class="estruturado-linhas-container">
                    </tbody>
                </table>
                <button type="button" class="add-ingrediente-linha-btn button">+ Adicionar Ingrediente</button>
            </div>`;
            
            var $group = $(groupHtml);
            $('#estruturado-grupos-container').append($group);
            
            // Adicionar linha inicial automática
            adicionarLinhaIngrediente($group.find('.estruturado-linhas-container'), nomeGrupo);
        }

        // Função para Adicionar uma Linha de Ingrediente dentro de um Grupo
        function adicionarLinhaIngrediente($container, groupName, savedId, savedName, savedAmount, savedUnit, savedDisplay) {
            savedId = savedId || '';
            savedName = savedName || '';
            savedAmount = savedAmount || '';
            savedUnit = savedUnit || 'g';
            savedDisplay = savedDisplay || '';
            
            var rowHtml = `
            <tr class="estruturado-ingrediente-row">
                <td>
                    <input type="text" class="ingrediente-search-input" name="estruturado_ing_nome[]" value="${savedName}" placeholder="Digite e selecione o ingrediente..." style="width:100%;" required>
                    <input type="hidden" class="ingrediente-id-val" name="estruturado_ing_id[]" value="${savedId}">
                    <input type="hidden" class="ingrediente-grupo-val" name="estruturado_ing_grupo[]" value="${groupName}">
                </td>
                <td>
                    <input type="number" step="0.001" name="estruturado_ing_quantidade[]" value="${savedAmount}" placeholder="Ex: 2" style="width:100%;">
                </td>
                <td>
                    <select name="estruturado_ing_unidade[]" style="width:100%;">
                        <?php foreach ($unidades as $u_key => $u_name) : ?>
                            <option value="<?php echo esc_attr($u_key); ?>">${'<?php echo esc_js($u_name); ?>'}</option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="estruturado_ing_display[]" value="${savedDisplay}" placeholder="Ex: 2 xícaras peneiradas" style="width:100%;">
                </td>
                <td>
                    <button type="button" class="remove-linha-btn" style="border:none; background:none; color:#d63638; cursor:pointer; font-size:16px;">&times;</button>
                </td>
            </tr>`;
            
            var $row = $(rowHtml);
            $row.find('select').val(savedUnit);
            $container.append($row);
            
            // Aplicar Autocomplete ao novo input
            aplicarAutocomplete($row.find('.ingrediente-search-input'));
        }

        // Adicionar novo grupo
        $('#adicionar-grupo-estruturado-btn').click(function() {
            adicionarGrupoEstruturado();
        });

        // Adicionar ingrediente no grupo
        $(document).on('click', '.add-ingrediente-linha-btn', function() {
            var groupBox = $(this).closest('.estruturado-grupo-box');
            var groupName = groupBox.find('.grupo-title-input').val();
            adicionarLinhaIngrediente(groupBox.find('.estruturado-linhas-container'), groupName);
        });

        // Atualizar o nome do grupo nos inputs filhos quando o título do grupo mudar
        $(document).on('input change', '.grupo-title-input', function() {
            var val = $(this).val();
            var groupBox = $(this).closest('.estruturado-grupo-box');
            groupBox.find('.ingrediente-grupo-val').val(val);
        });

        // Remover linha de ingrediente
        $(document).on('click', '.remove-linha-btn', function() {
            $(this).closest('tr').remove();
        });

        // Remover grupo inteiro
        $(document).on('click', '.remove-grupo-btn', function() {
            if (confirm('Remover este grupo e todos os seus ingredientes?')) {
                $(this).closest('.estruturado-grupo-box').remove();
            }
        });

        // Autocompletes iniciais para ingredientes salvos no carregamento
        $('.estruturado-ingrediente-row .ingrediente-search-input').each(function() {
            aplicarAutocomplete(this);
        });

        // Cálculo Automático de Tempo Total
        function calcularTempoTotal() {
            var prep = parseInt($('#sts_prep_time').val()) || 0;
            var cook = parseInt($('#sts_cook_time').val()) || 0;
            $('#sts_total_time').val(prep + cook);
        }
        $('#sts_prep_time, #sts_cook_time').on('input change', calcularTempoTotal);
        calcularTempoTotal();
    });
    </script>
    
    <div class="receita-metabox">
        
        <!-- Prompt Helper (Padrão de Excelência Google) -->
        <div class="metabox-section gan-prompt-helper" style="border: 1px dashed #ec5b13; background: #fffdfb; margin-bottom: 20px; padding:15px; border-radius:8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="margin: 0; color: #ec5b13;">📋 Gerador de Prompt para ChatGPT / Gemini</h3>
                <button type="button" id="gan_copy_prompt_btn" class="button button-primary" style="background: #ec5b13; border-color: #ec5b13;">Copiar Prompt</button>
            </div>
            <p class="description">Clique no botão para copiar o prompt de redação com o título e a palavra-chave deste post configurados. Cole na IA para obter a tabela nutricional média.</p>
            <textarea id="gan_prompt_template" style="display:none;">Você é o assistente oficial de redação do site "Descomplicando Receitas".
Com base no prato "[TITULO]" e nas seguintes notas: "[PALAVRA_CHAVE]", gere as informações formatadas para eu copiar e colar diretamente no meu painel administrativo do WordPress, conforme as seguintes seções:
1. CONTEÚDO PRINCIPAL (Introdução da receita - 2 a 3 parágrafos envolventes focados em Ganho de Informação, explicando por que a receita funciona):
2. RESUMO DA CHEF (Uma frase em itálico de até 130 caracteres resumindo o diferencial do prato):
3. DADOS DE CLASSIFICAÇÃO:
   - Tempo de Preparo (somente número em minutos):
   - Tempo de Cozimento (somente número em minutos):
   - Rendimento (ex: 6 porções):
   - Dieta (ex: Sem Glúten, Low Carb, ou deixar em branco):
4. INGREDIENTES:
   [Dividido por grupos se houver, com ingrediente por linha]
5. MODO DE PREPARO:
   [Lista numerada com passo a passo curto]
6. DICAS DA MARY:
   [Dicas extras de armazenamento, substituições ou técnica]
7. UTENSÍLIOS:
   [Lista simples de utensílios utilizados]
8. PERGUNTAS FREQUENTES (FAQ) - 2 perguntas estruturadas com respostas curtas:
   - Pergunta 1:
   - Resposta 1:
   - Pergunta 2:
   - Resposta 2:
9. CONCLUSÃO DA RECEITA:
   [Um parágrafo curto de encerramento amigável para aparecer após o modo de preparo]
10. METADADOS DE SEO:
   - Título SEO (máximo 60 caracteres com palavra-chave):
   - Meta Descrição (máximo 150 caracteres instigantes):
11. INFORMAÇÕES NUTRICIONAIS (Estimativa média baseada nos ingredientes e rendimento):
   - Calorias (somente número):
   - Carbs (somente número em gramas):
   - Proteínas (somente número em gramas):
   - Gorduras (somente número em gramas):
   - Porção (ex: 1 fatia, 1 copo, 1 porção):
   - Fonte dos Dados (ex: USDA, Estimativa IA, etc.):

Obs: deixe no padrão somente para eu copiar e colar, numeração desnecessária pode retirar.</textarea>
        </div>
        
        <div class="metabox-section">
            <h3>Informações de Classificação e Vídeo</h3>
            <div class="metabox-grid">
                <p>
                    <label><strong>Tempo de Preparo (min):</strong></label>
                    <input type="number" name="tempo_preparo" id="sts_prep_time" value="<?php echo esc_attr($tempo_preparo); ?>" style="width:100%">
                </p>
                <p>
                    <label><strong>Tempo de Cozimento (min):</strong></label>
                    <input type="number" name="tempo_cozimento" id="sts_cook_time" value="<?php echo esc_attr($tempo_cozimento); ?>" style="width:100%">
                </p>
                <p>
                    <label><strong>Tempo Total (Soma):</strong></label>
                    <input type="number" name="total_time" id="sts_total_time" value="<?php echo esc_attr(get_post_meta($post->ID, '_total_time', true)); ?>" style="width:100%; background:#f0f0f0;" readonly>
                </p>
                <p>
                    <label><strong>Culinária:</strong></label>
                    <input type="text" name="recipe_cuisine" value="<?php echo esc_attr($recipe_cuisine); ?>" placeholder="Padrão: Brasileira" style="width:100%">
                </p>
                <p>
                    <label><strong>Dificuldade:</strong></label>
                    <select name="dificuldade" style="width:100%">
                        <option value="Fácil" <?php selected($dificuldade, 'Fácil'); ?>>Fácil</option>
                        <option value="Médio" <?php selected($dificuldade, 'Médio'); ?>>Médio</option>
                        <option value="Difícil" <?php selected($dificuldade, 'Difícil'); ?>>Difícil</option>
                    </select>
                </p>
                <p>
                    <label><strong>Rendimento (Base para Nutrição):</strong></label>
                    <input type="text" name="porcoes" value="<?php echo esc_attr($porcoes); ?>" placeholder="Ex: 4 porções" style="width:100%" required>
                </p>
                <p>
                    <label><strong>Tipo de Dieta:</strong></label>
                    <input type="text" name="diet_type" value="<?php echo esc_attr($diet_type); ?>" placeholder="Ex: Low Carb, Vegana" style="width:100%">
                </p>
            </div>
            <div style="margin-top:15px;">
                <label><strong>URL do Vídeo (YouTube/Vimeo):</strong></label>
                <input type="url" name="video_url" value="<?php echo esc_url($video_url); ?>" placeholder="https://..." style="width:100%">
                <p class="description">Vídeos aumentam as chances de destaque no Google Discover.</p>
            </div>
        </div>

        <div class="metabox-section">
            <h3>Linkagem Interna Otimizada (SEO)</h3>
            <p class="description">Recomende uma receita dentro do artigo para reter o leitor e ganhar autoridade no Google.</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                <p style="margin: 0;">
                    <label><strong>URL da Receita Recomendada:</strong></label>
                    <input type="url" name="link_interno_url" value="<?php echo esc_url($link_interno_url); ?>" placeholder="https://descomplicandoreceitas.com.br/..." style="width:100%">
                </p>
                <p style="margin: 0;">
                    <label><strong>Texto Âncora Personalizado (Opcional):</strong></label>
                    <input type="text" name="link_interno_texto" value="<?php echo esc_attr($link_interno_texto); ?>" placeholder="Se vazio, usa o título do post de destino" style="width:100%">
                </p>
            </div>
        </div>

        <!-- SEÇÃO DE INGREDIENTES ESTRUTURADOS -->
        <div class="metabox-section">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                <h3>Ingredientes</h3>
                <?php
                // Detectar se já está estruturado
                $modo_uso = 'classico';
                if (!empty($ingredientes_salvos) || $nutri_is_auto) {
                    $modo_uso = 'estruturado';
                }
                ?>
                <input type="hidden" name="ingredientes_modo_uso" id="ingredientes_modo_uso" value="<?php echo esc_attr($modo_uso); ?>">
                
                <div>
                    <button type="button" id="ativar_editor_estruturado" class="button button-primary" style="background:#ec5b13; border-color:#ec5b13; <?php echo $modo_uso === 'estruturado' ? 'display:none;' : ''; ?>">Ativar Editor Estruturado (Normalizado)</button>
                    <button type="button" id="ativar_editor_classico" class="button" style="<?php echo $modo_uso === 'classico' ? 'display:none;' : ''; ?>">Mudar para Editor Clássico (Texto Livre)</button>
                </div>
            </div>

            <!-- EDITOR CLÁSSICO (Texto Livre / Fallback) -->
            <div id="editor-ingredientes-classico" style="<?php echo $modo_uso === 'estruturado' ? 'display:none;' : ''; ?>">
                <p class="description" style="color: #ec5b13; font-weight: bold; margin-bottom: 10px;">⚠️ Atualmente este post está usando o editor clássico (Texto Livre). Ative o editor estruturado acima para habilitar cálculos automáticos e a calculadora de porções.</p>
                <div id="ingredientes-container">
                    <?php
                    $ingredientes_grupo = get_post_meta($post->ID, '_ingredientes_grupo', true);
                    $ingredientes_classico = get_post_meta($post->ID, '_ingredientes', true);
                    if (!empty($ingredientes_grupo)) {
                        foreach ($ingredientes_grupo as $index => $grupo) {
                            echo '<div class="ingrediente-grupo">';
                            echo '<input type="text" name="ingredientes_grupo[]" value="' . esc_attr($grupo) . '" placeholder="Nome do grupo">';
                            echo '<textarea name="ingredientes[]" placeholder="Lista de ingredientes">' . esc_textarea($ingredientes_classico[$index] ?? '') . '</textarea>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="ingrediente-grupo"><input type="text" name="ingredientes_grupo[]" placeholder="Grupo (ex: Massa)"><textarea name="ingredientes[]" placeholder="Ingredientes"></textarea></div>';
                    }
                    ?>
                </div>
            </div>

            <!-- EDITOR ESTRUTURADO (Normalizado) -->
            <div id="editor-ingredientes-estruturado" style="<?php echo $modo_uso === 'classico' ? 'display:none;' : ''; ?>">
                <p class="description" style="margin-bottom: 15px;">Digite o nome do ingrediente para buscar na base central de ingredientes. Defina as quantidades e as unidades correspondentes.</p>
                
                <div id="estruturado-grupos-container">
                    <?php
                    if (!empty($ingredientes_salvos)) {
                        // Agrupar ingredientes por grupo
                        $grupos = [];
                        foreach ($ingredientes_salvos as $i) {
                            $grupos[$i->group_name][] = $i;
                        }
                        
                        foreach ($grupos as $nome_grupo => $rows) : ?>
                            <div class="estruturado-grupo-box" style="background:#f9f9f9; padding:15px; border-left: 4px solid #ec5b13; margin-bottom: 20px; border-radius: 0 8px 8px 0;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                                    <input type="text" class="grupo-title-input" value="<?php echo esc_attr($nome_grupo); ?>" placeholder="Nome do Grupo" style="font-weight:bold; font-size:1.1em; width:80%;">
                                    <button type="button" class="remove-grupo-btn button" style="color:#d63638;">Remover Grupo</button>
                                </div>
                                <table class="wp-list-table widefat fixed striped" style="margin-bottom:10px; background:#fff;">
                                    <thead>
                                        <tr>
                                            <th style="width:40%;">Ingrediente (Autocomplete)</th>
                                            <th style="width:15%;">Qtd.</th>
                                            <th style="width:20%;">Unidade</th>
                                            <th style="width:20%;">Override de Exibição (Ex: a gosto)</th>
                                            <th style="width:5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="estruturado-linhas-container">
                                        <?php foreach ($rows as $r) : ?>
                                            <tr class="estruturado-ingrediente-row">
                                                <td>
                                                    <input type="text" class="ingrediente-search-input" name="estruturado_ing_nome[]" value="<?php echo esc_attr($r->ingrediente_nome); ?>" placeholder="Digite e selecione o ingrediente..." style="width:100%;" required>
                                                    <input type="hidden" class="ingrediente-id-val" name="estruturado_ing_id[]" value="<?php echo esc_attr($r->ingredient_id); ?>">
                                                    <input type="hidden" class="ingrediente-grupo-val" name="estruturado_ing_grupo[]" value="<?php echo esc_attr($nome_grupo); ?>">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="estruturado_ing_quantidade[]" value="<?php echo $r->amount !== null ? esc_attr(floatval($r->amount)) : ''; ?>" placeholder="Ex: 2" style="width:100%;">
                                                </td>
                                                <td>
                                                    <select name="estruturado_ing_unidade[]" style="width:100%;">
                                                        <?php foreach ($unidades as $u_key => $u_name) : ?>
                                                            <option value="<?php echo esc_attr($u_key); ?>" <?php selected($r->unit, $u_key); ?>><?php echo esc_html($u_name); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="estruturado_ing_display[]" value="<?php echo esc_attr($r->display_text); ?>" placeholder="Ex: 2 xícaras peneiradas" style="width:100%;">
                                                </td>
                                                <td>
                                                    <button type="button" class="remove-linha-btn" style="border:none; background:none; color:#d63638; cursor:pointer; font-size:16px;">&times;</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" class="add-ingrediente-linha-btn button">+ Adicionar Ingrediente</button>
                            </div>
                        <?php endforeach;
                    }
                    ?>
                </div>
                <button type="button" id="adicionar-grupo-estruturado-btn" class="button button-primary">+ Novo Grupo de Ingredientes</button>
            </div>
        </div>

        <div class="metabox-section">
            <h3>Modo de Preparo</h3>
            <div id="instrucoes-container">
                <?php
                $instrucoes = get_post_meta($post->ID, '_instrucoes', true);
                if (!empty($instrucoes)) {
                    foreach ($instrucoes as $index => $instrucao) {
                        echo '<div class="instrucao-item">';
                        echo '<label>Passo ' . ($index + 1) . '</label>';
                        echo '<textarea name="instrucoes[]">' . esc_textarea($instrucao) . '</textarea>';
                        echo '<button type="button" class="remove-item">× Remover</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="instrucao-item"><textarea name="instrucoes[]" placeholder="Passo 1"></textarea></div>';
                }
                ?>
            </div>
            <button type="button" id="adicionar-instrucao" class="button">+ Passo</button>
        </div>

        <!-- INFORMAÇÕES NUTRICIONAIS -->
        <div class="metabox-section">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                <h3>Nutrição (E-E-A-T & Google Discover)</h3>
                <?php if ($nutri_is_auto) : ?>
                    <span class="notice notice-success inline" style="margin:0; padding:5px 10px; font-weight:bold; background:#d4edda; border-color:#c3e6cb; color:#155724; border-radius: 5px;">🔥 Calculado Automaticamente pelo Sistema</span>
                <?php endif; ?>
            </div>
            
            <div class="metabox-grid">
                <p>
                    <label>Calorias (kcal):</label>
                    <input type="number" name="calorias" value="<?php echo esc_attr($calorias); ?>" style="width:100%" <?php echo $nutri_is_auto ? 'readonly style="background:#f0f0f0;"' : ''; ?>>
                </p>
                <p>
                    <label>Carbs (g):</label>
                    <input type="number" name="carboidratos" value="<?php echo esc_attr($carboidratos); ?>" style="width:100%" <?php echo $nutri_is_auto ? 'readonly style="background:#f0f0f0;"' : ''; ?>>
                </p>
                <p>
                    <label>Proteínas (g):</label>
                    <input type="number" name="proteinas" value="<?php echo esc_attr($proteinas); ?>" style="width:100%" <?php echo $nutri_is_auto ? 'readonly style="background:#f0f0f0;"' : ''; ?>>
                </p>
                <p>
                    <label>Gorduras (g):</label>
                    <input type="number" name="gorduras" value="<?php echo esc_attr($gorduras); ?>" style="width:100%" <?php echo $nutri_is_auto ? 'readonly style="background:#f0f0f0;"' : ''; ?>>
                </p>
                <p>
                    <label>Porção (Ex: 1 fatia):</label>
                    <input type="text" name="nutri_serving" value="<?php echo esc_attr($nutri_serving); ?>" style="width:100%" placeholder="Padrão: 1 porção">
                </p>
                <p>
                    <label>Fonte dos Dados:</label>
                    <input type="text" name="nutri_source" value="<?php echo esc_attr($nutri_source); ?>" style="width:100%" placeholder="<?php echo $nutri_is_auto ? 'Cálculo Automático' : 'Manual'; ?>">
                </p>
            </div>
            <?php if ($nutri_is_auto) : ?>
                <p class="description" style="color:#155724; font-weight:bold; margin-top: 10px;">* Para alterar a nutrição, edite as quantidades de ingredientes. O sistema recalcula automaticamente ao salvar.</p>
            <?php endif; ?>
        </div>

        <!-- UTENSÍLIOS DE AFILIADOS E DICAS -->
        <div class="metabox-section">
            <h3>Dicas e Utensílios</h3>
            <label><strong>Dicas da Mary:</strong></label>
            <?php wp_editor($informacoes_adicionais, 'informacoes_adicionais', ['textarea_rows' => 4, 'media_buttons' => false]); ?>
            <br>
            
            <label><strong>Utensílios Recomendados (Indicações Mary - Afiliados):</strong></label>
            <p class="description" style="margin-bottom:10px;">Selecione os utensílios recomendados para esta receita que você já possui cadastrados no sistema.</p>
            <div style="max-height: 150px; overflow-y: auto; background: #fafafa; border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom:15px;">
                <?php
                $afiliados = get_posts(array(
                    'post_type'      => 'sts_indicacoes',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC'
                ));
                
                if (!empty($afiliados)) {
                    foreach ($afiliados as $prod) {
                        $checked = in_array($prod->ID, $utensilios_selecionados) ? 'checked' : '';
                        $mkt = get_post_meta($prod->ID, '_sts_marketplace', true) ?: 'Outro';
                        echo '<p style="margin: 5px 0;"><label>';
                        echo '<input type="checkbox" name="utensilios_afiliados[]" value="' . $prod->ID . '" ' . $checked . '>';
                        echo ' ' . esc_html($prod->post_title) . ' <span style="font-size:0.9em; color:#888;">(' . esc_html(ucfirst($mkt)) . ')</span>';
                        echo '</label></p>';
                    }
                } else {
                    echo '<p class="description">Nenhum produto cadastrado nas Indicações da Mary ainda.</p>';
                }
                ?>
            </div>
            
            <label><strong>Outros Utensílios (Texto Livre / Fallback):</strong></label>
            <?php 
            $u_val = is_array($utensilios_text) ? implode("\n", $utensilios_text) : $utensilios_text;
            wp_editor($u_val, 'utensilios', ['textarea_rows' => 3, 'media_buttons' => false]); 
            ?>
        </div>

        <div class="metabox-section">
            <h3>FAQ - Perguntas Frequentes (SEO God Mode)</h3>
            <p class="description">Estas perguntas aparecerão como Rich Snippets no Google, aumentando seu CTR.</p>
            <div id="faq-container">
                <?php
                if (!empty($faq_perguntas)) {
                    foreach ($faq_perguntas as $index => $pergunta) {
                        $resposta = $faq_respostas[$index] ?? '';
                        echo '<div class="faq-item">';
                        echo '<input type="text" name="faq_perguntas[]" value="' . esc_attr($pergunta) . '" placeholder="Pergunta" style="width:100%; margin-bottom:5px; font-weight:bold;">';
                        echo '<textarea name="faq_respostas[]" placeholder="Resposta curta e direta" style="width:100%; height:60px;">' . esc_textarea($resposta) . '</textarea>';
                        echo '<button type="button" class="remove-item">× Remover Pergunta</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="faq-item"><input type="text" name="faq_perguntas[]" placeholder="Pergunta"><textarea name="faq_respostas[]" placeholder="Resposta"></textarea></div>';
                }
                ?>
            </div>
            <button type="button" id="adicionar-faq" class="button">+ Adicionar Pergunta</button>
        </div>

        <div class="metabox-section">
            <h3>Conclusão da Receita (Padrão de Excelência Google)</h3>
            <p class="description">Escreva considerações finais, sugestões de consumo ou uma breve despedida para fechar a receita de forma engajadora no final do post.</p>
            <?php wp_editor($conclusao, 'conclusao', ['textarea_rows' => 4, 'media_buttons' => false]); ?>
        </div>

    </div>

    <style>
        .receita-metabox .metabox-section { background: #wrap; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .metabox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; }
        .ingrediente-grupo, .instrucao-item, .faq-item { background: #f9f9f9; padding: 15px; border-left: 4px solid #ec5b13; margin-bottom: 15px; border-radius: 0 8px 8px 0; }
        .ingrediente-grupo input, .ingrediente-grupo textarea, .instrucao-item textarea, .faq-item input, .faq-item textarea { width: 100%; margin-bottom: 8px; }
        .remove-item { color: #d63638; cursor: pointer; border: none; background: none; font-size: 11px; font-weight: bold; }
        .remove-item:hover { text-decoration: underline; }
        .ui-autocomplete { background:#fff; border: 1px solid #ccc; max-height: 200px; overflow-y:auto; width: 300px; box-shadow:0 4px 6px rgba(0,0,0,0.1); border-radius:4px; z-index:99999 !important; }
        .ui-menu-item { padding: 8px 12px; cursor: pointer; border-bottom:1px solid #f0f0f0; }
        .ui-menu-item:hover { background:#f4f4f4; color:#ec5b13; }
        .ui-helper-hidden-accessible { display:none; }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('#adicionar-faq').click(function() {
            $('#faq-container').append('<div class="faq-item"><input type="text" name="faq_perguntas[]" placeholder="Pergunta"><textarea name="faq_respostas[]" placeholder="Resposta"></textarea><button type="button" class="remove-item">× Remover Pergunta</button></div>');
        });
        $('#adicionar-instrucao').click(function() {
            var count = $('#instrucoes-container .instrucao-item').length + 1;
            $('#instrucoes-container').append('<div class="instrucao-item"><label>Passo ' + count + '</label><textarea name="instrucoes[]"></textarea><button type="button" class="remove-item">× Remover</button></div>');
        });
        $(document).on('click', '.remove-item', function() { $(this).parent().remove(); });
    });
    </script>
    <?php
}

/**
 * 3. Salvamento dos Dados & Cálculo Nutricional
 */
function salvar_metabox_receita($post_id) {
    global $wpdb;
    if (!isset($_POST['receita_nonce']) || !wp_verify_nonce($_POST['receita_nonce'], 'salvar_receita_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $campos = [
        'tempo_preparo', 'tempo_cozimento', 'total_time', 'porcoes', 'dificuldade', 
        'calorias', 'carboidratos', 'proteinas', 'gorduras', 
        'nutri_serving', 'nutri_source', 'recipe_cuisine', 'video_url', 'diet_type',
        'link_interno_url', 'link_interno_texto'
    ];

    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $value = $_POST[$campo];
            if ($campo === 'video_url') {
                $valor = esc_url_raw($value);
            } else {
                $valor = sanitize_text_field($value);
            }
            if ($campo === 'recipe_cuisine' && empty($valor)) {
                $valor = 'Brasileira';
            }
            update_post_meta($post_id, '_' . $campo, $valor);
        }
    }

    if (isset($_POST['informacoes_adicionais'])) {
        update_post_meta($post_id, '_informacoes_adicionais', wp_kses_post($_POST['informacoes_adicionais']));
    }

    // Salvar Utensílios de Afiliados (Checkboxes)
    $utensilios_afiliados = isset($_POST['utensilios_afiliados']) ? array_map('intval', $_POST['utensilios_afiliados']) : [];
    update_post_meta($post_id, '_receita_utensilios_afiliados', $utensilios_afiliados);

    // Salvar Utensílios Clássicos (Texto Livre)
    if (isset($_POST['utensilios'])) {
        $content = wp_kses_post($_POST['utensilios']);
        $u = [];
        if (preg_match_all('/<li>(.*?)<\/li>/', $content, $m)) {
            $u = array_filter(array_map('strip_tags', $m[1]));
        } else {
            $u = array_filter(explode("\n", strip_tags($content)));
        }
        update_post_meta($post_id, '_utensilios', array_map('trim', $u));
    }

    if (isset($_POST['conclusao'])) {
        update_post_meta($post_id, '_conclusao', wp_kses_post($_POST['conclusao']));
    }

    if (isset($_POST['instrucoes'])) {
        update_post_meta($post_id, '_instrucoes', array_filter(array_map('wp_kses_post', $_POST['instrucoes'])));
    }

    if (isset($_POST['faq_perguntas']) && isset($_POST['faq_respostas'])) {
        update_post_meta($post_id, '_faq_perguntas', array_map('sanitize_text_field', $_POST['faq_perguntas']));
        update_post_meta($post_id, '_faq_respostas', array_map('wp_kses_post', $_POST['faq_respostas']));
    }

    // Salvar Ingredientes Clássicos (Salva para manter fallback ativo se o usuário não usar o estruturado)
    if (isset($_POST['ingredientes_grupo']) && isset($_POST['ingredientes'])) {
        update_post_meta($post_id, '_ingredientes_grupo', array_map('sanitize_text_field', $_POST['ingredientes_grupo']));
        update_post_meta($post_id, '_ingredientes', array_map('wp_kses_post', $_POST['ingredientes']));
    }

    // PROCESSAMENTO DOS INGREDIENTES ESTRUTURADOS E CÁLCULO NUTRICIONAL
    $table_rel = $wpdb->prefix . 'receita_ingredientes_rel';
    $table_ing = $wpdb->prefix . 'receita_ingredientes_mestre';
    $table_conv = $wpdb->prefix . 'receita_ingredientes_conversoes';
    
    $modo_uso = isset($_POST['ingredientes_modo_uso']) ? sanitize_text_field($_POST['ingredientes_modo_uso']) : 'classico';
    
    if ($modo_uso === 'estruturado' && isset($_POST['estruturado_ing_nome'])) {
        // Limpar ingredientes antigos vinculados à receita
        $wpdb->delete($table_rel, array('recipe_id' => $post_id));
        
        $ids = isset($_POST['estruturado_ing_id']) ? $_POST['estruturado_ing_id'] : array();
        $nomes = $_POST['estruturado_ing_nome'];
        $quantidades = $_POST['estruturado_ing_quantidade'];
        $unidades = $_POST['estruturado_ing_unidade'];
        $displays = $_POST['estruturado_ing_display'];
        $grupos = $_POST['estruturado_ing_grupo'];
        
        // 1. Inserir os dados na tabela customizada
        $sort_order = 0;
        foreach ($nomes as $index => $nome_ingrediente) {
            $nome_ingrediente = sanitize_text_field($nome_ingrediente);
            if (empty($nome_ingrediente)) continue;
            
            $ing_id = isset($ids[$index]) ? intval($ids[$index]) : 0;
            
            // Se o ID for vazio, vamos buscar pelo slug ou auto-cadastrar no dicionário mestre
            if ($ing_id <= 0) {
                $slug = sanitize_title($nome_ingrediente);
                
                // Verificar se já existe por slug (para evitar duplicados)
                $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_ing WHERE slug = %s", $slug));
                if ($existing_id) {
                    $ing_id = $existing_id;
                } else {
                    // Insere um ingrediente mestre básico
                    $wpdb->insert($table_ing, array(
                        'name'               => $nome_ingrediente,
                        'slug'               => $slug,
                        'food_category'      => 'Geral',
                        'density_g_per_ml'   => 1.0000,
                        'kcal_per_100g'      => 0.00,
                        'protein_g_per_100g' => 0.00,
                        'carbs_g_per_100g'   => 0.00,
                        'fat_g_per_100g'     => 0.00,
                        'fiber_g_per_100g'   => 0.00,
                        'sodium_mg_per_100g' => 0.00,
                        'is_allergen'        => 0,
                        'allergen_type'      => NULL
                    ));
                    $ing_id = $wpdb->insert_id;
                }
            }
            
            if ($ing_id > 0) {
                $wpdb->insert($table_rel, array(
                    'recipe_id'     => $post_id,
                    'ingredient_id' => $ing_id,
                    'group_name'    => !empty($grupos[$index]) ? sanitize_text_field($grupos[$index]) : 'Ingredientes',
                    'amount'        => $quantidades[$index] !== '' ? floatval($quantidades[$index]) : null,
                    'unit'          => sanitize_text_field($unidades[$index]),
                    'display_text'  => sanitize_text_field($displays[$index]),
                    'sort_order'    => $sort_order++
                ));
            }
        }
        
        // 2. Realizar Cálculo Nutricional Automático usando o Helper
        sts_receita_recalcular_nutricao($post_id);
    } else {
        // Se mudou ou manteve o clássico, limpa o status de automático
        delete_post_meta($post_id, '_nutri_auto_calculated');
    }
}
add_action('save_post', 'salvar_metabox_receita');

// Endpoint AJAX para autocomplete de ingredientes mestre
function sts_buscar_ingredientes_ajax() {
    global $wpdb;
    $search = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
    
    if (empty($search)) {
        wp_send_json_success([]);
    }
    
    $table = $wpdb->prefix . 'receita_ingredientes_mestre';
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT id, name FROM $table WHERE name LIKE %s LIMIT 10",
        '%' . $wpdb->esc_like($search) . '%'
    ));
    
    $data = [];
    foreach ($results as $row) {
        $data[] = [
            'id'   => $row->id,
            'text' => $row->name
        ];
    }
    
    wp_send_json_success($data);
}
add_action('wp_ajax_sts_buscar_ingredientes', 'sts_buscar_ingredientes_ajax');

/**
 * 4. Linkagem Interna Otimizada para SEO (Injetada automaticamente no conteúdo)
 */
function sts_inserir_link_interno_no_conteudo($content) {
    if (!is_single() || !is_main_query() || get_post_type() !== 'post') {
        return $content;
    }
    
    static $injected = false;
    if ($injected) {
        return $content;
    }
    
    $post_id = get_the_ID();
    $custom_url = get_post_meta($post_id, '_link_interno_url', true);
    $custom_text = get_post_meta($post_id, '_link_interno_texto', true);
    
    $link = '';
    $titulo = '';
    
    if (!empty($custom_url)) {
        $link = $custom_url;
        if (!empty($custom_text)) {
            $titulo = $custom_text;
        } else {
            $linked_post_id = url_to_postid($custom_url);
            if ($linked_post_id) {
                $titulo = get_the_title($linked_post_id);
            } else {
                $titulo = preg_replace('/^https?:\/\/(www\.)?/', '', $custom_url);
            }
        }
    } else {
        $categories = wp_get_post_categories($post_id);
        if (!empty($categories)) {
            $related_posts = get_posts(array(
                'category__in'   => $categories,
                'post__not_in'   => array($post_id),
                'posts_per_page' => 1,
                'orderby'        => 'rand'
            ));
            if (!empty($related_posts)) {
                $link = get_permalink($related_posts[0]->ID);
                $titulo = get_the_title($related_posts[0]->ID);
            }
        }
    }
    
    if (!empty($link) && !empty($titulo)) {
        $injected = true;
        
        $box_html = '
        <div class="inline-callout bg-slate-50 dark:bg-slate-800/40 p-5 my-8 rounded-[24px] border-l-4 border-primary flex items-start gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300">
            <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5" style="font-variation-settings: \'FILL\' 0, \'wght\' 400, \'GRAD\' 0, \'opsz\' 24;">link</span>
            <div>
                <span class="font-black text-slate-400 uppercase tracking-widest text-[9px] block mb-1">Recomendamos para você</span>
                <a href="' . esc_url($link) . '" class="text-slate-800 dark:text-white hover:text-primary font-black text-base sm:text-lg transition-colors leading-snug">
                    Veja essa receita: ' . esc_html($titulo) . '
                </a>
            </div>
        </div>';
        
        $paragraphs = explode('</p>', $content);
        if (count($paragraphs) > 2) {
            $paragraphs[1] .= $box_html;
            $content = implode('</p>', $paragraphs);
        } else {
            $content = $box_html . $content;
        }
    }
    
    return $content;
}
add_filter('the_content', 'sts_inserir_link_interno_no_conteudo', 15);

/**
 * 5. Helper: Recalcular a Tabela Nutricional da Receita e Gravar no PostMeta
 */
function sts_receita_recalcular_nutricao($post_id) {
    global $wpdb;
    
    $table_rel = $wpdb->prefix . 'receita_ingredientes_rel';
    $table_ing = $wpdb->prefix . 'receita_ingredientes_mestre';
    $table_conv = $wpdb->prefix . 'receita_ingredientes_conversoes';
    
    // Tentar ler o número de porções configurado no post
    $porcoes_texto = get_post_meta($post_id, 'porcoes', true) ?: get_post_meta($post_id, '_porcoes', true) ?: '';
    if (empty($porcoes_texto) && isset($_POST['porcoes'])) {
        $porcoes_texto = sanitize_text_field($_POST['porcoes']);
    }
    
    $num_porcoes = 4; // Fallback
    if (preg_match('/\d+/', $porcoes_texto, $matches)) {
        $num_porcoes = intval($matches[0]) ?: 4;
    }
    
    $total_kcal = 0;
    $total_carbs = 0;
    $total_protein = 0;
    $total_fat = 0;
    $total_fiber = 0;
    $total_sodium = 0;
    
    $receita_ings = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_rel WHERE recipe_id = %d", $post_id));
    
    foreach ($receita_ings as $ring) {
        $ing_mestre = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ing WHERE id = %d", $ring->ingredient_id));
        if (!$ing_mestre || $ring->amount === null) continue;
        
        $amount = floatval($ring->amount);
        $unit = $ring->unit;
        $peso_g = 0;
        
        if ($unit === 'g') {
            $peso_g = $amount;
        } elseif ($unit === 'kg') {
            $peso_g = $amount * 1000;
        } elseif ($unit === 'ml') {
            $peso_g = $amount * floatval($ing_mestre->density_g_per_ml);
        } elseif ($unit === 'l') {
            $peso_g = $amount * 1000 * floatval($ing_mestre->density_g_per_ml);
        } else {
            $conv = $wpdb->get_row($wpdb->prepare(
                "SELECT grams_equivalent FROM $table_conv WHERE ingredient_id = %d AND unit = %s",
                $ring->ingredient_id,
                $unit
            ));
            if ($conv) {
                $peso_g = $amount * floatval($conv->grams_equivalent);
            } else {
                $fallbacks_unidades = array(
                    'xicara_cha'  => 200.0,
                    'colher_sopa' => 15.0,
                    'colher_cha'  => 5.0,
                    'colher_cafe' => 2.0,
                    'unidade'     => 100.0,
                    'fatia'       => 30.0,
                    'dente'       => 4.0,
                    'ramo'        => 5.0
                );
                $peso_base = isset($fallbacks_unidades[$unit]) ? $fallbacks_unidades[$unit] : 10.0;
                $peso_g = $amount * $peso_base;
            }
        }
        
        $total_kcal    += (floatval($ing_mestre->kcal_per_100g) / 100) * $peso_g;
        $total_carbs   += (floatval($ing_mestre->carbs_g_per_100g) / 100) * $peso_g;
        $total_protein += (floatval($ing_mestre->protein_g_per_100g) / 100) * $peso_g;
        $total_fat     += (floatval($ing_mestre->fat_g_per_100g) / 100) * $peso_g;
        $total_fiber   += (floatval($ing_mestre->fiber_g_per_100g) / 100) * $peso_g;
        $total_sodium  += (floatval($ing_mestre->sodium_mg_per_100g) / 100) * $peso_g;
    }
    
    $kcal_per_serving    = round($total_kcal / $num_porcoes);
    $carbs_per_serving   = round($total_carbs / $num_porcoes, 1);
    $protein_per_serving = round($total_protein / $num_porcoes, 1);
    $fat_per_serving     = round($total_fat / $num_porcoes, 1);
    
    update_post_meta($post_id, '_calorias', $kcal_per_serving);
    update_post_meta($post_id, '_carboidratos', $carbs_per_serving);
    update_post_meta($post_id, '_proteinas', $protein_per_serving);
    update_post_meta($post_id, '_gorduras', $fat_per_serving);
    update_post_meta($post_id, '_nutri_auto_calculated', 1);
    update_post_meta($post_id, '_nutri_source', 'Cálculo Automático (Schema Supra-Sumo)');
}

/**
 * 6. AJAX: Solicitar estruturação da receita antiga à API do ChatGPT (OpenAI)
 */
function sts_analisar_receita_ia_ajax() {
    global $wpdb;
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if ($post_id <= 0) {
        wp_send_json_error('ID de receita inválido.');
    }
    
    $api_key = get_option('sts_openai_api_key', '');
    if (empty($api_key)) {
        wp_send_json_error('Chave de API da OpenAI não está configurada.');
    }
    
    $ingredientes = get_post_meta($post_id, '_ingredientes', true);
    $grupos = get_post_meta($post_id, '_ingredientes_grupo', true);
    
    if (empty($ingredientes) || !is_array($ingredientes)) {
        wp_send_json_error('Esta receita não possui ingredientes no formato clássico.');
    }
    
    $texto_receita = "";
    foreach ($ingredientes as $idx => $ing_text) {
        $grupo = isset($grupos[$idx]) ? trim($grupos[$idx]) : '';
        if (!empty($grupo)) {
            $texto_receita .= "\nGrupo: {$grupo}\n";
        }
        $texto_receita .= "- " . trim($ing_text) . "\n";
    }
    
    $system_prompt = "Você é um robô parser de dados culinários brasileiro. Você receberá uma lista de ingredientes de uma receita. Seu objetivo é analisar cada ingrediente e extrair os dados dividindo em:
1. 'grupo': Nome do grupo de ingredientes correspondente (ex: 'Massa', 'Recheio', 'Cobertura'). Se não houver, use 'Ingredientes'.
2. 'ingrediente': Nome limpo e normalizado do ingrediente (ex: 'Farinha de trigo', 'Açúcar refinado', 'Ovo de galinha', 'Bacon'). Remova observações.
3. 'quantidade': Número fracionário ou inteiro da quantidade física (ex: 1.5, 2, 600). Se for a gosto ou indefinida, retorne nulo (null).
4. 'unidade': Sigla interna da unidade culinária. Deve ser estritamente uma destas opções: 'g', 'kg', 'ml', 'l', 'xicara_cha', 'colher_sopa', 'colher_cha', 'colher_cafe', 'unidade', 'pitada', 'a_gosto', 'fatia', 'dente', 'ramo'. Se for 'a gosto', use 'a_gosto'.
5. 'override': Texto adicional com detalhes de preparo ou notas de exibição (ex: 'descascada e picada', 'limpo', 'derretida', 'para polvilhar').

Você deve retornar estritamente um objeto JSON com a chave 'ingredientes' contendo a lista.";

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
        'timeout' => 45,
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ),
        'body' => json_encode(array(
            'model' => 'gpt-4o-mini',
            'response_format' => array('type' => 'json_object'),
            'messages' => array(
                array(
                    'role' => 'system',
                    'content' => $system_prompt
                ),
                array(
                    'role' => 'user',
                    'content' => "Lista de ingredientes clássicos para extrair:\n" . $texto_receita
                )
            ),
            'temperature' => 0.1
        ))
    ));
    
    if (is_wp_error($response)) {
        wp_send_json_error('Erro na requisição à API: ' . $response->get_error_message());
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (isset($data['error'])) {
        wp_send_json_error('Erro retornado pela OpenAI: ' . $data['error']['message']);
    }
    
    $choices = $data['choices'] ?? [];
    if (empty($choices)) {
        wp_send_json_error('Resposta vazia da OpenAI.');
    }
    
    $content = $choices[0]['message']['content'] ?? '';
    $parsed_content = json_decode($content, true);
    $ingredientes_estruturados = $parsed_content['ingredientes'] ?? [];
    
    wp_send_json_success($ingredientes_estruturados);
}
add_action('wp_ajax_sts_analisar_receita_ia', 'sts_analisar_receita_ia_ajax');

/**
 * 7. AJAX: Gravar a receita estruturada final pós-migração no banco MySQL
 */
function sts_confirmar_migracao_ia_ajax() {
    global $wpdb;
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if ($post_id <= 0) {
        wp_send_json_error('ID de receita inválido.');
    }
    
    $ingredientes = isset($_POST['ingredientes']) ? $_POST['ingredientes'] : [];
    if (empty($ingredientes) || !is_array($ingredientes)) {
        wp_send_json_error('Nenhum dado de ingrediente enviado.');
    }
    
    $table_rel = $wpdb->prefix . 'receita_ingredientes_rel';
    $table_ing = $wpdb->prefix . 'receita_ingredientes_mestre';
    
    // Limpar ingredientes antigos vinculados à receita
    $wpdb->delete($table_rel, array('recipe_id' => $post_id));
    
    $sort_order = 0;
    foreach ($ingredientes as $item) {
        $nome_ingrediente = sanitize_text_field($item['nome'] ?? '');
        if (empty($nome_ingrediente)) continue;
        
        $quantidade = ($item['quantidade'] !== '') ? floatval($item['quantidade']) : null;
        $unidade = sanitize_text_field($item['unidade'] ?? 'g');
        $display = sanitize_text_field($item['display'] ?? '');
        $grupo = sanitize_text_field($item['grupo'] ?? 'Ingredientes');
        
        // Regra de auto-cadastro se não existir no mestre
        $slug = sanitize_title($nome_ingrediente);
        $ing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_ing WHERE slug = %s", $slug));
        
        if (!$ing_id) {
            $wpdb->insert($table_ing, array(
                'name'               => $nome_ingrediente,
                'slug'               => $slug,
                'food_category'      => 'Geral',
                'density_g_per_ml'   => 1.0000,
                'kcal_per_100g'      => 0.00,
                'protein_g_per_100g' => 0.00,
                'carbs_g_per_100g'   => 0.00,
                'fat_g_per_100g'     => 0.00,
                'fiber_g_per_100g'   => 0.00,
                'sodium_mg_per_100g' => 0.00,
                'is_allergen'        => 0,
                'allergen_type'      => NULL
            ));
            $ing_id = $wpdb->insert_id;
        }
        
        if ($ing_id > 0) {
            $wpdb->insert($table_rel, array(
                'recipe_id'     => $post_id,
                'ingredient_id' => $ing_id,
                'group_name'    => $grupo,
                'amount'        => $quantidade,
                'unit'          => $unidade,
                'display_text'  => $display,
                'sort_order'    => $sort_order++
            ));
        }
    }
    
    // Atualizar o modo de uso do postmeta
    update_post_meta($post_id, '_ingredientes_modo_uso', 'estruturado');
    
    // Recalcular nutrientes
    sts_receita_recalcular_nutricao($post_id);
    
    wp_send_json_success('Receita migrada com sucesso!');
}
add_action('wp_ajax_sts_confirmar_migracao_ia', 'sts_confirmar_migracao_ia_ajax');