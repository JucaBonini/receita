<?php
/**
 * Admin Migrador: Painel de migração assistida por IA para receitas antigas (texto livre)
 */

defined('ABSPATH') || exit;

// Registrar Submenu no Admin
function sts_receitas_add_migrator_menu() {
    add_submenu_page(
        'sts-ingredientes',
        'Migrar Receitas',
        'Migrar Receitas',
        'manage_options',
        'sts-migrador',
        'sts_receitas_render_migrator_admin'
    );
}
add_action('admin_menu', 'sts_receitas_add_migrator_menu');

// Renderizar a tela do migrador
function sts_receitas_render_migrator_admin() {
    global $wpdb;
    
    // Processar Salvamento da Chave da API
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sts_save_api_key'])) {
        if (check_admin_referer('sts_save_api_key_action', 'sts_api_key_nonce')) {
            $api_key = sanitize_text_field($_POST['sts_openai_api_key']);
            update_option('sts_openai_api_key', $api_key);
            echo '<div class="notice notice-success is-dismissible"><p>Chave da API da OpenAI salva com sucesso!</p></div>';
        }
    }
    
    $api_key = get_option('sts_openai_api_key', '');
    $selected_post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    // Unidades de medidas para o template JS
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
    
    // Query para obter posts clássicos pendentes
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_ingredientes',
                'compare' => 'EXISTS'
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => '_ingredientes_modo_uso',
                    'value'   => 'estruturado',
                    'compare' => '!='
                ),
                array(
                    'key'     => '_ingredientes_modo_uso',
                    'compare' => 'NOT EXISTS'
                )
            )
        )
    );
    
    $recipes_query = new WP_Query($args);
    $pending_recipes = $recipes_query->posts;
    wp_reset_postdata();
    ?>
    <div class="wrap">
        <h1>Migração de Receitas Assistida por IA 🤖</h1>
        <p class="description">Converta as suas receitas clássicas antigas que estão no formato de texto livre para o novo formato de ingredientes normalizados em banco e cálculos automáticos.</p>
        
        <!-- 🔑 CONFIGURAÇÃO DA CHAVE DA API -->
        <div class="card" style="margin-top: 15px; padding: 15px; border-radius: 8px; border: 1px solid #ccd0d4; max-width: 800px;">
            <h3>Configuração de API OpenAI</h3>
            <form method="post" action="">
                <?php wp_nonce_field('sts_save_api_key_action', 'sts_api_key_nonce'); ?>
                <table class="form-table" style="margin-top:0;">
                    <tr>
                        <th scope="row" style="width:200px; padding: 10px 0;"><label for="sts_openai_api_key">Chave de API OpenAI (sk-...):</label></th>
                        <td style="padding: 10px 0;">
                            <input type="password" id="sts_openai_api_key" name="sts_openai_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" placeholder="Cole sua chave sk- aqui..." required>
                            <p class="description">Sua chave é necessária para que a inteligência artificial faça a análise gramatical e estruturada dos ingredientes em lote.</p>
                        </td>
                    </tr>
                </table>
                <p class="submit" style="margin: 10px 0 0 0; padding:0;">
                    <input type="submit" name="sts_save_api_key" class="button button-primary" value="Salvar Chave de API">
                </p>
            </form>
        </div>
        
        <?php if (empty($api_key)) : ?>
            <div class="notice notice-warning" style="margin-top: 20px;">
                <p>⚠️ Insira uma Chave da API da OpenAI acima para habilitar o migrador inteligente por IA.</p>
            </div>
            <?php return; ?>
        <?php endif; ?>

        <!-- 📁 SELETOR DE RECEITAS PENDENTES (Sanfona no Topo de Largura Total) -->
        <div class="card" style="margin-top: 20px; padding: 15px; border-radius: 8px; border: 1px solid #ccd0d4; max-width: 100%;">
            <h3 style="margin:0; cursor:pointer; display:flex; justify-content:space-between; align-items:center;" id="toggle-pendentes-btn">
                <span style="display: flex; align-items: center; gap: 6px;">
                    <span class="dashicons dashicons-category" style="color: #ec5b13; font-size:20px; width:20px; height:20px;"></span>
                    📁 Selecionar Receita para Migrar (<?php echo count($pending_recipes); ?> pendentes)
                </span>
                <span class="dashicons dashicons-arrow-down-alt2" style="font-size:20px; width:20px; height:20px;"></span>
            </h3>
            <div id="pendentes-lista-container" style="<?php echo $selected_post_id > 0 ? 'display:none;' : ''; ?> margin-top:15px; border-top:1px solid #e2e8f0; padding-top:15px;">
                <?php if (empty($pending_recipes)) : ?>
                    <p style="color:#46b450; font-weight:bold; margin:0;">🎉 Nenhuma receita clássica pendente de migração!</p>
                <?php else : ?>
                    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:10px; max-height:220px; overflow-y:auto; padding:5px;">
                        <?php foreach ($pending_recipes as $recipe) : 
                            $class = ($recipe->ID === $selected_post_id) ? 'background:#ec5b13; color:white; border-color:#ec5b13;' : 'background:#f6f7f7; color:#334155; border-color:#cbd5e1;';
                            $title = $recipe->post_title ?: '(Sem título #' . $recipe->ID . ')';
                            ?>
                            <a href="?page=sts-migrador&post_id=<?php echo $recipe->ID; ?>" style="display:block; padding:10px; border-radius: 5px; text-decoration:none; <?php echo $class; ?> font-weight:500; border:1px solid; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo esc_attr($title); ?>">
                                <?php echo esc_html($title); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ⚡ INTERFACE DE MIGRAÇÃO INTERATIVA (Largura Total da Tela) -->
        <div class="card" style="margin-top: 20px; padding: 20px; border-radius: 8px; min-height: 400px; display:flex; flex-direction:column; gap:20px; max-width: 100%;">
            <?php if ($selected_post_id <= 0) : ?>
                <div style="text-align:center; padding: 80px 0; color:#64748b;">
                    <span class="dashicons dashicons-admin-plugins" style="font-size: 50px; width: 50px; height: 50px; margin-bottom: 15px;"></span>
                    <h3>Selecione uma receita na barra superior para iniciar a migração</h3>
                </div>
            <?php else : 
                $selected_post = get_post($selected_post_id);
                $ing_classicos = get_post_meta($selected_post_id, '_ingredientes', true);
                $grp_classicos = get_post_meta($selected_post_id, '_ingredientes_grupo', true);
                ?>
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:15px; margin-bottom:10px;">
                    <h2 style="margin:0; font-size: 1.5em;"><?php echo esc_html($selected_post->post_title); ?></h2>
                    <div>
                        <button type="button" id="btn-analisar-ia" class="button button-primary" style="background:#0284c7; border-color:#0284c7; font-weight: 500;">
                            <span class="dashicons dashicons-admin-generic" style="vertical-align: middle; margin-right: 3px; font-size:17px;"></span> Analisar com IA
                        </button>
                        <a href="<?php echo get_edit_post_link($selected_post_id); ?>" target="_blank" class="button">Editar Post</a>
                    </div>
                </div>
                
                <!-- Caixa Superior: Receita Original Clássica -->
                <div style="background:#f8fafc; border: 1px solid #cbd5e1; border-radius:8px; padding:15px; margin-bottom: 10px;">
                    <h4 style="margin-top:0; color:#475569; display: flex; align-items: center; gap: 6px; font-size: 1.05em; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">
                        <span class="dashicons dashicons-editor-ul" style="font-size: 19px; width: 19px; height: 19px; color:#ec5b13;"></span> 
                        Ingredientes Originais (Texto Livre)
                    </h4>
                    <div style="background:#fff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; font-family: monospace; font-size: 13px; line-height: 1.6; max-height: 150px; overflow-y: auto; color:#334155; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
                        <?php 
                        if (is_array($ing_classicos)) {
                            foreach ($ing_classicos as $idx => $ing_text) {
                                $grupo = isset($grp_classicos[$idx]) ? trim($grp_classicos[$idx]) : '';
                                if (!empty($grupo)) {
                                    echo '<strong style="display:block; margin-top:8px; color:#ec5b13; font-family: sans-serif; font-size: 12px;">[' . esc_html($grupo) . ']</strong>';
                                }
                                echo '<div style="margin-left: 10px;">' . nl2br(esc_html($ing_text)) . '</div>';
                            }
                        } else {
                            echo '<p>Nenhum ingrediente clássico encontrado neste post.</p>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Caixa Inferior: Revisão Estruturada da IA -->
                <div style="background:#fff; border: 1px solid #cbd5e1; border-radius:8px; padding:20px; display:flex; flex-direction:column; gap:15px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <h4 style="margin-top:0; border-bottom: 2px solid #cbd5e1; padding-bottom:8px; color:#475569; display: flex; align-items: center; gap: 6px; font-size: 1.05em;">
                        <span class="dashicons dashicons-edit" style="font-size: 19px; width: 19px; height: 19px; color:#16a34a;"></span>
                        Revisão da Estruturação Normalizada
                    </h4>
                    
                    <div id="migrador-status" style="padding: 15px; border-radius:6px; background:#f1f5f9; text-align:center; color:#64748b; font-weight: 500;">
                        <span class="dashicons dashicons-warning" style="vertical-align: middle; margin-right:5px;"></span>
                        Clique no botão <strong>"Analisar com IA"</strong> no topo para que a IA processe a estruturação.
                    </div>
                    
                    <!-- Formulário de Edição Estruturada -->
                    <form id="form-migrar-dados" style="display:none;">
                        <div id="migrador-grupos-container">
                            <!-- Gerado dinamicamente via JS -->
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:20px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                            <button type="button" id="btn-add-grupo-migrador" class="button">+ Novo Grupo</button>
                            <button type="submit" id="btn-confirmar-migracao" class="button button-primary" style="background:#16a34a; border-color:#16a34a; font-weight:bold; font-size:1.1em; padding:5px 25px;">
                                Aprovar e Concluir Migração ✓
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
            
        </div>
    </div>

    <!-- ⚡ SCRIPTS DE INTERATIVIDADE E AJAX -->
    <script>
    jQuery(document).ready(function($) {
        var unidades = <?php echo json_encode($unidades); ?>;
        var selectedPostId = <?php echo $selected_post_id; ?>;
        
        // Alternar visualização da lista de pendentes (Sanfona)
        $('#toggle-pendentes-btn').click(function() {
            $('#pendentes-lista-container').slideToggle();
            var icon = $(this).find('.dashicons').last();
            if (icon.hasClass('dashicons-arrow-down-alt2')) {
                icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
            } else {
                icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
            }
        });
        
        // 1. Ação do Botão Analisar com IA
        $('#btn-analisar-ia').click(function() {
            var btn = $(this);
            var statusBox = $('#migrador-status');
            var form = $('#form-migrar-dados');
            
            btn.prop('disabled', true).text('Analisando via IA...');
            statusBox.removeClass().addClass('notice notice-info').html('<p>⏳ Solicitando estruturação à API do ChatGPT. Isso pode levar alguns segundos...</p>').show();
            form.hide();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sts_analisar_receita_ia',
                    post_id: selectedPostId
                },
                success: function(response) {
                    btn.prop('disabled', false).html('<span class="dashicons dashicons-admin-generic" style="vertical-align: middle; margin-right: 3px; font-size:17px;"></span> Reanalisar com IA');
                    
                    if (response.success) {
                        statusBox.hide();
                        form.show();
                        renderizarDadosIa(response.data);
                    } else {
                        statusBox.removeClass().addClass('notice notice-error').html('<p>❌ Erro da IA: ' + response.data + '</p>').show();
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<span class="dashicons dashicons-admin-generic" style="vertical-align: middle; margin-right: 3px; font-size:17px;"></span> Tentar Novamente');
                    statusBox.removeClass().addClass('notice notice-error').html('<p>❌ Falha de comunicação AJAX no servidor.</p>').show();
                }
            });
        });

        // Converter fração culinária textual para decimal de ponto flutuante
        function converterFracaoParaDecimal(valor) {
            if (valor === undefined || valor === null) return '';
            valor = String(valor).trim();
            if (valor === '') return '';
            
            // Substituir vírgula por ponto decimal
            valor = valor.replace(',', '.');
            
            // Se já for um número puro (ex: 2 ou 1.5), retorna direto
            if (/^\d+(\.\d+)?$/.test(valor)) {
                return parseFloat(valor);
            }
            
            // Se for fração simples (ex: "1/2")
            if (/^\d+\/\d+$/.test(valor)) {
                var partes = valor.split('/');
                return parseFloat(partes[0]) / parseFloat(partes[1]);
            }
            
            // Se for número misto com espaço (ex: "1 1/2")
            if (/^\d+\s+\d+\/\d+$/.test(valor)) {
                var partesEspaco = valor.split(/\s+/);
                var inteiro = parseFloat(partesEspaco[0]);
                var partesFracao = partesEspaco[1].split('/');
                var fracao = parseFloat(partesFracao[0]) / parseFloat(partesFracao[1]);
                return inteiro + fracao;
            }
            
            // Se for número misto com traço (ex: "1-1/2")
            if (/^\d+-\d+\/\d+$/.test(valor)) {
                var partesTraco = valor.split('-');
                var inteiro = parseFloat(partesTraco[0]);
                var partesFracao = partesTraco[1].split('/');
                var fracao = parseFloat(partesFracao[0]) / parseFloat(partesFracao[1]);
                return inteiro + fracao;
            }
            
            // Tenta extrair qualquer número/fração do início da string (ex: "1/2 xícara" ou "3 unidades")
            var matchFracao = valor.match(/^(\d+)\/(\d+)/);
            if (matchFracao) {
                return parseFloat(matchFracao[1]) / parseFloat(matchFracao[2]);
            }
            
            var matchMisto = valor.match(/^(\d+)\s+(\d+)\/(\d+)/);
            if (matchMisto) {
                return parseFloat(matchMisto[1]) + (parseFloat(matchMisto[2]) / parseFloat(matchMisto[3]));
            }
            
            var matchPuro = valor.match(/^\d+(\.\d+)?/);
            if (matchPuro) {
                return parseFloat(matchPuro[0]);
            }
            
            return '';
        }

        // Renderizar a Resposta da IA na Tela
        function renderizarDadosIa(dados) {
            var container = $('#migrador-grupos-container');
            container.empty();
            
            // Agrupar dados por grupo culinário
            var grupos = {};
            dados.forEach(function(item) {
                var gNome = item.grupo || item.group || 'Ingredientes';
                if (!grupos[gNome]) {
                    grupos[gNome] = [];
                }
                grupos[gNome].push(item);
            });
            
            // Gerar HTML de cada grupo
            Object.keys(grupos).forEach(function(gNome) {
                var groupHtml = criarHtmlGrupo(gNome, grupos[gNome]);
                container.append(groupHtml);
            });
        }

        function criarHtmlGrupo(gNome, itens) {
            var rowsHtml = '';
            itens.forEach(function(item) {
                // Leitura defensiva das propriedades (suporta português e inglês da IA)
                var ing = item.ingrediente !== undefined ? item.ingrediente : (item.ingredient !== undefined ? item.ingredient : (item.name !== undefined ? item.name : ''));
                var rawQtd = item.quantidade !== undefined ? item.quantidade : (item.quantity !== undefined ? item.quantity : (item.amount !== undefined ? item.amount : (item.qtd !== undefined ? item.qtd : '')));
                var unit = item.unidade !== undefined ? item.unidade : (item.unit !== undefined ? item.unit : 'g');
                var override = item.override !== undefined ? item.override : (item.display_text !== undefined ? item.display_text : (item.observacao !== undefined ? item.observacao : ''));
                
                // Converter quantidade culinária textual em decimal de ponto flutuante
                var qtd = converterFracaoParaDecimal(rawQtd);
                
                rowsHtml += criarHtmlLinha(ing, qtd, unit, override);
            });
            
            var groupHtml = `
            <div class="migrador-grupo-box" style="background:#f8fafc; border:1px solid #cbd5e1; padding:15px; border-radius:6px; margin-bottom:15px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <input type="text" class="migrador-grupo-title" name="grupo_nome[]" value="${gNome}" placeholder="Nome do Grupo (ex: Cobertura)" style="font-weight:bold; width:70%; font-size:1.05em;">
                    <button type="button" class="btn-remove-grupo button button-small" style="color:#d63638;">Remover Grupo</button>
                </div>
                <table class="wp-list-table widefat fixed striped" style="background:#fff; margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th style="width:40%;">Ingrediente Mestre</th>
                            <th style="width:15%;">Qtd.</th>
                            <th style="width:20%;">Unidade</th>
                            <th style="width:20%;">Override (Ex: picado)</th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody class="migrador-linhas-container">
                        ${rowsHtml}
                    </tbody>
                </table>
                <button type="button" class="btn-add-linha button button-small">+ Adicionar Linha</button>
            </div>`;
            return groupHtml;
        }

        function criarHtmlLinha(ing, qtd, unit, override) {
            ing = ing || '';
            qtd = qtd || '';
            unit = unit || 'g';
            override = override || '';
            
            var options = '';
            Object.keys(unidades).forEach(function(key) {
                var selected = (key === unit) ? 'selected' : '';
                options += `<option value="${key}" ${selected}>${unidades[key]}</option>`;
            });
            
            return `
            <tr class="migrador-linha-ingrediente">
                <td>
                    <input type="text" class="migrador-ingrediente-nome" name="ing_nome[]" value="${ing}" placeholder="Ingrediente..." style="width:100%;" required>
                </td>
                <td>
                    <input type="number" step="0.001" name="ing_quantidade[]" value="${qtd}" placeholder="Ex: 2" style="width:100%;">
                </td>
                <td>
                    <select name="ing_unidade[]" style="width:100%;">
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="text" name="ing_display[]" value="${override}" placeholder="Observação..." style="width:100%;">
                </td>
                <td>
                    <button type="button" class="btn-remove-linha" style="border:none; background:none; color:#d63638; cursor:pointer; font-size:16px;">&times;</button>
                </td>
            </tr>`;
        }

        // Adicionar Linha em Grupo Existente
        $(document).on('click', '.btn-add-linha', function() {
            var body = $(this).closest('.migrador-grupo-box').find('.migrador-linhas-container');
            body.append(criarHtmlLinha('', '', 'g', ''));
        });

        // Adicionar Novo Grupo Vazio
        $('#btn-add-grupo-migrador').click(function() {
            var container = $('#migrador-grupos-container');
            var groupHtml = criarHtmlGrupo('Novo Grupo', [{ingrediente:'', quantidade:'', unidade:'g', override:''}]);
            container.append(groupHtml);
        });

        // Remover Grupo
        $(document).on('click', '.btn-remove-grupo', function() {
            if (confirm('Deseja realmente remover este grupo e todos os seus ingredientes?')) {
                $(this).closest('.migrador-grupo-box').remove();
            }
        });

        // Remover Linha
        $(document).on('click', '.btn-remove-linha', function() {
            $(this).closest('tr').remove();
        });

        // 2. Submissão do Formulário de Confirmação (Gravação)
        $('#form-migrar-dados').submit(function(e) {
            e.preventDefault();
            
            var statusBox = $('#migrador-status');
            var submitBtn = $('#btn-confirmar-migracao');
            
            submitBtn.prop('disabled', true).text('Gravando no Banco...');
            statusBox.removeClass().addClass('notice notice-info').html('<p>💾 Gravando os relacionamentos e recalculando macros no banco MySQL...</p>').show();
            
            // Compilar estrutura para envio
            var grupos = [];
            $('.migrador-grupo-box').each(function() {
                var groupBox = $(this);
                var gNome = groupBox.find('.migrador-grupo-title').val() || 'Ingredientes';
                
                groupBox.find('.migrador-linha-ingrediente').each(function() {
                    var row = $(this);
                    grupos.push({
                        grupo: gNome,
                        nome: row.find('.migrador-ingrediente-nome').val(),
                        quantidade: row.find('input[name="ing_quantidade[]"]').val(),
                        unidade: row.find('select[name="ing_unidade[]"]').val(),
                        display: row.find('input[name="ing_display[]"]').val()
                    });
                });
            });
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sts_confirmar_migracao_ia',
                    post_id: selectedPostId,
                    ingredientes: grupos
                },
                success: function(response) {
                    if (response.success) {
                        statusBox.removeClass().addClass('notice notice-success').html('<p>🎉 Receita migrada com sucesso! Redirecionando para a próxima pendência...</p>').show();
                        setTimeout(function() {
                            window.location.href = '?page=sts-migrador';
                        }, 1500);
                    } else {
                        submitBtn.prop('disabled', false).text('Aprovar e Concluir Migração ✓');
                        statusBox.removeClass().addClass('notice notice-error').html('<p>❌ Erro no salvamento: ' + response.data + '</p>').show();
                    }
                },
                error: function() {
                    submitBtn.prop('disabled', false).text('Aprovar e Concluir Migração ✓');
                    statusBox.removeClass().addClass('notice notice-error').html('<p>❌ Falha de comunicação AJAX no servidor.</p>').show();
                }
            });
        });
    });
    </script>
    <?php
}
