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
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'cardapios'),
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
        .sts-cardapio-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 10px; background: #f9f9f9; }
        .sts-day-box { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .sts-day-title { font-weight: bold; border-bottom: 2px solid #e74c3c; margin-bottom: 12px; padding-bottom: 5px; color: #2c3e50; text-transform: uppercase; font-size: 11px; }
        .sts-meal-row { margin-bottom: 15px; }
        .sts-meal-label { display: block; font-size: 11px; margin-bottom: 4px; color: #7f8c8d; font-weight: 600; }
        .sts-recipe-select { width: 100%; height: 32px; font-size: 12px; }
    </style>';

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
}
add_action('save_post', 'sts_save_cardapio_metadata');
