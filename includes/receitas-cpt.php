<?php

/**
 * 1. Registro da Metabox
 */
function adicionar_metabox_receita() {
    add_meta_box(
        'receita_metabox',
        'Informações da Receita (Otimizado 2026)',
        'renderizar_metabox_receita',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_metabox_receita');

/**
 * 2. Interface do Painel Administrativo
 */
function renderizar_metabox_receita($post) {
    wp_nonce_field('salvar_receita_meta', 'receita_nonce');
    
    // Recuperação dos valores com Fallback Inteligente (Sincronização 2026)
    // Se o campo novo estiver vazio, tentamos buscar no campo antigo do ACF
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
    
    $calorias        = get_post_meta($post->ID, '_calorias', true);
    $carboidratos    = get_post_meta($post->ID, '_carboidratos', true);
    $proteinas       = get_post_meta($post->ID, '_proteinas', true);
    $gorduras        = get_post_meta($post->ID, '_gorduras', true);
    $nutri_serving   = get_post_meta($post->ID, '_nutri_serving', true);
    $nutri_source    = get_post_meta($post->ID, '_nutri_source', true);
    
    $informacoes_adicionais = get_post_meta($post->ID, '_informacoes_adicionais', true);
    $ingredientes           = get_post_meta($post->ID, '_ingredientes', true);
    $ingredientes_grupo     = get_post_meta($post->ID, '_ingredientes_grupo', true);
    $instrucoes             = get_post_meta($post->ID, '_instrucoes', true);
    $utensilios_text        = get_post_meta($post->ID, '_utensilios', true);
    $faq_perguntas          = get_post_meta($post->ID, '_faq_perguntas', true);
    $faq_respostas          = get_post_meta($post->ID, '_faq_respostas', true);
    ?>
    
    <script>
    // Esconder o Meta Box redundante do ACF via CSS no Admin
    jQuery(document).ready(function($) {
        // Procuramos pelo box do ACF que contém "Rendimento" ou "Dificuldade"
        $('.postbox').each(function() {
            var title = $(this).find('h2 span').text();
            if (title.includes('Rendimento') || title.includes('Tempo') || title.includes('Dificuldade')) {
                $(this).hide();
            }
        });
    });
    </script>
    
    <div class="receita-metabox">
        
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
                    <label><strong>Rendimento:</strong></label>
                    <input type="text" name="porcoes" value="<?php echo esc_attr($porcoes); ?>" placeholder="Ex: 4 porções" style="width:100%">
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
            <h3>Ingredientes</h3>
            <div id="ingredientes-container">
                <?php
                if (!empty($ingredientes_grupo)) {
                    foreach ($ingredientes_grupo as $index => $grupo) {
                        echo '<div class="ingrediente-grupo">';
                        echo '<input type="text" name="ingredientes_grupo[]" value="' . esc_attr($grupo) . '" placeholder="Nome do grupo">';
                        echo '<textarea name="ingredientes[]" placeholder="Lista de ingredientes">' . esc_textarea($ingredientes[$index] ?? '') . '</textarea>';
                        echo '<button type="button" class="remove-item">× Remover</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="ingrediente-grupo"><input type="text" name="ingredientes_grupo[]" placeholder="Grupo (ex: Massa)"><textarea name="ingredientes[]" placeholder="Ingredientes"></textarea></div>';
                }
                ?>
            </div>
            <button type="button" id="adicionar-grupo" class="button">+ Grupo de Ingredientes</button>
        </div>

        <div class="metabox-section">
            <h3>Modo de Preparo</h3>
            <div id="instrucoes-container">
                <?php
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

        <div class="metabox-section">
            <h3>Nutrição (E-E-A-T & Google Discover)</h3>
            <div class="metabox-grid">
                <p><label>Calorias:</label><input type="number" name="calorias" value="<?php echo esc_attr($calorias); ?>" style="width:100%"></p>
                <p><label>Carbs (g):</label><input type="number" name="carboidratos" value="<?php echo esc_attr($carboidratos); ?>" style="width:100%"></p>
                <p><label>Proteínas (g):</label><input type="number" name="proteinas" value="<?php echo esc_attr($proteinas); ?>" style="width:100%"></p>
                <p><label>Gorduras (g):</label><input type="number" name="gorduras" value="<?php echo esc_attr($gorduras); ?>" style="width:100%"></p>
                <p><label>Porção (Ex: 1 fatia):</label><input type="text" name="nutri_serving" value="<?php echo esc_attr($nutri_serving); ?>" style="width:100%"></p>
                <p><label>Fonte dos Dados:</label><input type="text" name="nutri_source" value="<?php echo esc_attr($nutri_source); ?>" style="width:100%"></p>
            </div>
        </div>

        <div class="metabox-section">
            <h3>Dicas e Utensílios</h3>
            <label><strong>Dicas da Mary:</strong></label>
            <?php wp_editor($informacoes_adicionais, 'informacoes_adicionais', ['textarea_rows' => 4, 'media_buttons' => false]); ?>
            <br>
            <label><strong>Utensílios:</strong></label>
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
                        echo '<input type="text" name="faq_perguntas[]" value="' . esc_attr($pergunta) . '" placeholder="Pergunta (Ex: Pode congelar?)" style="width:100%; margin-bottom:5px; font-weight:bold;">';
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

    </div>

    <style>
        .receita-metabox .metabox-section { background: #fff; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .metabox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; }
        .ingrediente-grupo, .instrucao-item, .faq-item { background: #f9f9f9; padding: 15px; border-left: 4px solid #ec5b13; margin-bottom: 15px; border-radius: 0 8px 8px 0; }
        .ingrediente-grupo input, .ingrediente-grupo textarea, .instrucao-item textarea, .faq-item input, .faq-item textarea { width: 100%; margin-bottom: 8px; }
        .remove-item { color: #d63638; cursor: pointer; border: none; background: none; font-size: 11px; font-weight: bold; }
        .remove-item:hover { text-decoration: underline; }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('#adicionar-grupo').click(function() {
            $('#ingredientes-container').append('<div class="ingrediente-grupo"><input type="text" name="ingredientes_grupo[]" placeholder="Grupo"><textarea name="ingredientes[]"></textarea><button type="button" class="remove-item">× Remover</button></div>');
        });
        $('#adicionar-instrucao').click(function() {
            var count = $('#instrucoes-container .instrucao-item').length + 1;
            $('#instrucoes-container').append('<div class="instrucao-item"><label>Passo ' + count + '</label><textarea name="instrucoes[]"></textarea><button type="button" class="remove-item">× Remover</button></div>');
        });
        $('#adicionar-faq').click(function() {
            $('#faq-container').append('<div class="faq-item"><input type="text" name="faq_perguntas[]" placeholder="Pergunta"><textarea name="faq_respostas[]" placeholder="Resposta"></textarea><button type="button" class="remove-item">× Remover Pergunta</button></div>');
        });
        $(document).on('click', '.remove-item', function() { $(this).parent().remove(); });

        // Cálculo Automático de Tempo Total
        function calcularTempoTotal() {
            var prep = parseInt($('#sts_prep_time').val()) || 0;
            var cook = parseInt($('#sts_cook_time').val()) || 0;
            $('#sts_total_time').val(prep + cook);
        }
        $('#sts_prep_time, #sts_cook_time').on('input change', calcularTempoTotal);
        calcularTempoTotal(); // Executar ao carregar
    });
    </script>
    <?php
}

/**
 * 3. Salvamento dos Dados
 */
function salvar_metabox_receita($post_id) {
    if (!isset($_POST['receita_nonce']) || !wp_verify_nonce($_POST['receita_nonce'], 'salvar_receita_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $campos = [
        'tempo_preparo', 'tempo_cozimento', 'total_time', 'porcoes', 'dificuldade', 
        'calorias', 'carboidratos', 'proteinas', 'gorduras', 
        'nutri_serving', 'nutri_source', 'recipe_cuisine', 'video_url', 'diet_type'
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

    if (isset($_POST['ingredientes_grupo']) && isset($_POST['ingredientes'])) {
        update_post_meta($post_id, '_ingredientes_grupo', array_map('sanitize_text_field', $_POST['ingredientes_grupo']));
        update_post_meta($post_id, '_ingredientes', array_map('wp_kses_post', $_POST['ingredientes']));
    }

    if (isset($_POST['instrucoes'])) {
        update_post_meta($post_id, '_instrucoes', array_filter(array_map('wp_kses_post', $_POST['instrucoes'])));
    }

    if (isset($_POST['faq_perguntas']) && isset($_POST['faq_respostas'])) {
        update_post_meta($post_id, '_faq_perguntas', array_map('sanitize_text_field', $_POST['faq_perguntas']));
        update_post_meta($post_id, '_faq_respostas', array_map('wp_kses_post', $_POST['faq_respostas']));
    }

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
}
add_action('save_post', 'salvar_metabox_receita');