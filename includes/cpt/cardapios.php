<?php
/**
 * Custom Post Type: Cardápio da Semana (STS 2026 Engine)
 * Gerencia a curadoria semanal de receitas com slots de refeição.
 */

function sts_register_cpt_cardapio() {
    $labels = array(
        'name'               => 'Cardápios',
        'singular_name'      => 'Cardápio',
        'menu_name'          => 'Planejador (MENU)',
        'add_new'            => 'Nova Semana',
        'add_new_item'       => 'Criar Cardápio Semanal',
        'edit_item'          => 'Editar Cardápio',
        'all_items'          => 'Todos os Cardápios',
        'search_items'       => 'Buscar Cardápios',
        'not_found'          => 'Nenhum cardápio encontrado.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => 'cardapio-da-semana',
        'rewrite'            => array('slug' => 'cardapio-da-semana' , 'with_front' => false),
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('sts_cardapio', $args);
}
add_action('init', 'sts_register_cpt_cardapio');

/**
 * Metabox para Configurar os Slots do Cardápio
 */
function sts_add_cardapio_metaboxes() {
    add_meta_box(
        'sts_cardapio_slots',
        'Planejamento Semanal (Seleção de Receitas)',
        'sts_render_cardapio_slots_metabox',
        'sts_cardapio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sts_add_cardapio_metaboxes');

function sts_render_cardapio_slots_metabox($post) {
    wp_nonce_field('sts_save_cardapio', 'sts_cardapio_nonce');
    
    $dias = array(
        'segunda' => 'Segunda-feira',
        'terca'   => 'Terça-feira',
        'quarta'  => 'Quarta-feira',
        'quinta'  => 'Quinta-feira',
        'sexta'   => 'Sexta-feira',
        'sabado'  => 'Sábado',
        'domingo' => 'Domingo'
    );
    
    $refeicoes = array(
        'cafe'   => '☕ Café da Manhã',
        'almoco' => '🍽️ Almoço',
        'jantar' => '🌙 Jantar'
    );

    // Buscar todas as receitas (posts)
    $recipes = get_posts(array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ));

    $current_data = get_post_meta($post->ID, '_sts_cardapio_data', true) ?: array();

    echo '<style>
        .sts-cardapio-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; padding: 10px; background: #f9f9f9; }
        .sts-day-box { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .sts-day-title { font-weight: bold; border-bottom: 2px solid #e74c3c; margin-bottom: 12px; padding-bottom: 5px; color: #2c3e50; text-transform: uppercase; font-size: 11px; }
        .sts-meal-row { margin-bottom: 15px; }
        .sts-meal-label { display: block; font-size: 11px; margin-bottom: 4px; color: #7f8c8d; font-weight: 600; }
        .sts-recipe-select { width: 100%; height: 32px; font-size: 12px; }
        .sts-control-header { background: #fff; padding: 20px; border-bottom: 2px solid #eee; margin-bottom: 20px; display: flex; gap: 20px; align-items: center; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .sts-control-field { flex: 1; }
        .sts-control-label { display: block; font-weight: 800; text-transform: uppercase; font-size: 11px; color: #e74c3c; margin-bottom: 8px; letter-spacing: 1px; }
        .sts-control-select { width: 100%; padding: 8px; border-radius: 6px; border: 2px solid #eee; font-weight: 700; color: #2c3e50; }
    </style>';

    $current_mes = get_post_meta($post->ID, '_sts_cardapio_mes', true);
    $current_sem = get_post_meta($post->ID, '_sts_cardapio_semana', true);

    echo '<div class="sts-control-header">';
    
    // Campo de Mês
    echo '<div class="sts-control-field">';
    echo '<label class="sts-control-label">🗓️ Mês de Referência</label>';
    echo '<select name="sts_cardapio_mes" class="sts-control-select">';
    $meses = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    echo '<option value="">-- Selecione o Mês --</option>';
    foreach ($meses as $mes) {
        echo '<option value="'.$mes.'" '.selected($current_mes, $mes, false).'>'.$mes.'</option>';
    }
    echo '</select>';
    echo '</div>';

    // Campo de Semana
    echo '<div class="sts-control-field">';
    echo '<label class="sts-control-label">📅 Semana do Mês</label>';
    echo '<select name="sts_cardapio_semana" class="sts-control-select">';
    echo '<option value="">-- Selecione a Semana --</option>';
    for($s=1; $s<=5; $s++) {
        echo '<option value="Semana '.$s.'" '.selected($current_sem, 'Semana '.$s, false).'>Semana '.$s.'</option>';
    }
    echo '</select>';
    echo '</div>';

    echo '</div>';

    echo '<div class="sts-cardapio-grid">';
    
    foreach ($dias as $dia_key => $dia_label) {
        echo '<div class="sts-day-box">';
        echo '<div class="sts-day-title">' . $dia_label . '</div>';
        
        foreach ($refeicoes as $ref_key => $ref_label) {
            $selected_id = isset($current_data[$dia_key][$ref_key]) ? $current_data[$dia_key][$ref_key] : '';
            
            echo '<div class="sts-meal-row">';
            echo '<label class="sts-meal-label">' . $ref_label . '</label>';
            echo '<select name="sts_cardapio[' . $dia_key . '][' . $ref_key . ']" class="sts-recipe-select">';
            echo '<option value="">-- Nenhuma Selecionada --</option>';
            foreach ($recipes as $recipe) {
                echo '<option value="' . $recipe->ID . '" ' . selected($selected_id, $recipe->ID, false) . '>' . esc_html($recipe->post_title) . '</option>';
            }
            echo '</select>';
            echo '</div>';
        }
        
        echo '</div>'; // sts-day-box
    }
    
    echo '</div>'; // sts-cardapio-grid

    // Script para Sincronizar Título Automaticamente (JS)
    echo '<script>
        (function($) {
            $(document).ready(function() {
                var $mes = $("select[name=\'sts_cardapio_mes\']");
                var $sem = $("select[name=\'sts_cardapio_semana\']");
                var $title = $("#title");

                function syncTitle() {
                    var mesVal = $mes.val();
                    var semVal = $sem.val();
                    if (mesVal && semVal) {
                        $title.val(mesVal + " - " + semVal);
                        // Trigger de aviso de alteração do WP
                        $title.trigger("change");
                    }
                }

                $mes.on("change", syncTitle);
                $sem.on("change", syncTitle);
            });
        })(jQuery);
    </script>';
}

/**
 * Salvar Dados do Cardápio
 */
function sts_save_cardapio_metadata($post_id) {
    if (!isset($_POST['sts_cardapio_nonce']) || !wp_verify_nonce($_POST['sts_cardapio_nonce'], 'sts_save_cardapio')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['sts_cardapio'])) {
        update_post_meta($post_id, '_sts_cardapio_data', $_POST['sts_cardapio']);
    }

    if (isset($_POST['sts_cardapio_mes'])) {
        update_post_meta($post_id, '_sts_cardapio_mes', sanitize_text_field($_POST['sts_cardapio_mes']));
    }

    if (isset($_POST['sts_cardapio_semana'])) {
        $semana = sanitize_text_field($_POST['sts_cardapio_semana']);
        update_post_meta($post_id, '_sts_cardapio_semana', $semana);
    }

    // Gerar Título e Slug Automático baseado em Mês e Semana
    if (isset($_POST['sts_cardapio_mes']) && isset($_POST['sts_cardapio_semana'])) {
        $mes = sanitize_text_field($_POST['sts_cardapio_mes']);
        $sem = sanitize_text_field($_POST['sts_cardapio_semana']);
        
        $new_title = $mes . ' - ' . $sem;
        $new_slug = sanitize_title($new_title);
        
        // Evitar loop infinito ao atualizar o post no save_post
        remove_action('save_post', 'sts_save_cardapio_metadata');
        wp_update_post(array(
            'ID'         => $post_id,
            'post_title' => $new_title,
            'post_name'  => $new_slug
        ));
        add_action('save_post', 'sts_save_cardapio_metadata');
    }
}
add_action('save_post', 'sts_save_cardapio_metadata');

/**
 * Adicionar Colunas Customizadas na Listagem do Painel
 */
function sts_set_cardapio_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'title') {
            $new_columns['sts_mes'] = '🗓️ Mês';
            $new_columns['sts_semana'] = '📅 Semana';
        }
    }
    return $new_columns;
}
add_filter('manage_sts_cardapio_posts_columns', 'sts_set_cardapio_columns');

function sts_fill_cardapio_columns($column, $post_id) {
    switch ($column) {
        case 'sts_mes':
            echo '<strong>' . (get_post_meta($post_id, '_sts_cardapio_mes', true) ?: '-') . '</strong>';
            break;
        case 'sts_semana':
            echo '<span class="badge" style="background:#e74c3c; color:#fff; padding:2px 8px; border-radius:4px; font-weight:bold; font-size:10px;">' . (get_post_meta($post_id, '_sts_cardapio_semana', true) ?: '-') . '</span>';
            break;
    }
}
add_action('manage_sts_cardapio_posts_custom_column', 'sts_fill_cardapio_columns', 10, 2);
