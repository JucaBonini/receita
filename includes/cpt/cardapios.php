<?php
/**
 * 🥗 MOTOR DE CARDÁPIO SEMANAL (Nível Zeus) - STS 2.8.3
 * Gerencia a curadoria semanal de receitas com slots de refeição e lista de compras.
 */

// 1. Taxonomia de Momentos (para categorizar receitas individuais)
function sts_register_meal_taxonomy() {
    register_taxonomy('tipo_refeicao', 'post', [
        'labels' => [
            'name' => 'Momento da Refeição',
            'singular_name' => 'Momento da Refeição',
            'menu_name' => 'Momento da Refeição',
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);

    // Preencher valores padrão
    $termos = ['Café da Manhã', 'Almoço', 'Jantar', 'Lanche'];
    foreach ($termos as $termo) {
        if (!term_exists($termo, 'tipo_refeicao')) {
            wp_insert_term($termo, 'tipo_refeicao');
        }
    }
}
add_action('init', 'sts_register_meal_taxonomy');

// 2. Registro do CPT de Cardápio
function sts_register_cpt_cardapio() {
    $labels = array(
        'name'               => 'Cardápios',
        'singular_name'      => 'Cardápio',
        'menu_name'          => 'Planejador (MENU)',
        'add_new'            => 'Nova Semana',
        'add_new_item'       => 'Criar Cardápio Semanal',
        'edit_item'          => 'Editar Cardápio',
        'all_items'          => 'Todos os Cardápios',
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

// 3. Metabox de Slots de Refeição
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
        'segunda' => 'Segunda-feira', 'terca'   => 'Terça-feira', 'quarta'  => 'Quarta-feira',
        'quinta'  => 'Quinta-feira',  'sexta'   => 'Sexta-feira',  'sabado'  => 'Sábado', 'domingo' => 'Domingo'
    );
    
    $refeicoes = array(
        'cafe'   => '☕ Café da Manhã',
        'almoco' => '🍽️ Almoço',
        'lanche' => '🥪 Lanche',
        'jantar' => '🌙 Jantar'
    );

    $recipes = get_posts(['post_type' => 'post', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC']);
    $current_data = get_post_meta($post->ID, '_sts_cardapio_data', true) ?: [];

    echo '<style>
        .sts-cardapio-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; padding: 10px; background: #f9f9f9; }
        .sts-day-box { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .sts-day-title { font-weight: bold; border-bottom: 2px solid #ec5b13; margin-bottom: 12px; padding-bottom: 5px; color: #2c3e50; text-transform: uppercase; font-size: 11px; }
        .sts-meal-row { margin-bottom: 15px; }
        .sts-meal-label { display: block; font-size: 11px; margin-bottom: 4px; color: #7f8c8d; font-weight: 600; }
        .sts-recipe-select { width: 100%; height: 32px; font-size: 12px; }
        .sts-control-header { background: #fff; padding: 20px; border-bottom: 2px solid #eee; margin-bottom: 20px; display: flex; gap: 20px; align-items: center; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .sts-control-field { flex: 1; }
        .sts-control-label { display: block; font-weight: 800; text-transform: uppercase; font-size: 11px; color: #ec5b13; margin-bottom: 8px; letter-spacing: 1px; }
        .sts-control-select { width: 100%; padding: 8px; border-radius: 6px; border: 2px solid #eee; font-weight: 700; color: #2c3e50; }
    </style>';

    $current_mes = get_post_meta($post->ID, '_sts_cardapio_mes', true);
    $current_sem = get_post_meta($post->ID, '_sts_cardapio_semana', true);

    echo '<div class="sts-control-header">';
    echo '<div class="sts-control-field"><label class="sts-control-label">🗓️ Mês de Referência</label>';
    echo '<select name="sts_cardapio_mes" class="sts-control-select">';
    $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    foreach ($meses as $mes) { echo '<option value="'.$mes.'" '.selected($current_mes, $mes, false).'>'.$mes.'</option>'; }
    echo '</select></div>';

    echo '<div class="sts-control-field"><label class="sts-control-label">📅 Semana do Mês</label>';
    echo '<select name="sts_cardapio_semana" class="sts-control-select">';
    for($s=1; $s<=5; $s++) { echo '<option value="Semana '.$s.'" '.selected($current_sem, 'Semana '.$s, false).'>Semana '.$s.'</option>'; }
    echo '</select></div></div>';

    echo '<div class="sts-cardapio-grid">';
    foreach ($dias as $dia_key => $dia_label) {
        echo '<div class="sts-day-box"><div class="sts-day-title">' . $dia_label . '</div>';
        foreach ($refeicoes as $ref_key => $ref_label) {
            $selected_id = $current_data[$dia_key][$ref_key] ?? '';
            echo '<div class="sts-meal-row"><label class="sts-meal-label">' . $ref_label . '</label>';
            echo '<select name="sts_cardapio[' . $dia_key . '][' . $ref_key . ']" class="sts-recipe-select">';
            echo '<option value="">-- Selecione --</option>';
            foreach ($recipes as $recipe) { echo '<option value="' . $recipe->ID . '" ' . selected($selected_id, $recipe->ID, false) . '>' . esc_html($recipe->post_title) . '</option>'; }
            echo '</select></div>';
        }
        echo '</div>';
    }
    echo '</div>';

    echo '<script>
        jQuery(document).ready(function($) {
            var $mes = $("select[name=\'sts_cardapio_mes\']"), $sem = $("select[name=\'sts_cardapio_semana\']"), $title = $("#title");
            function sync() { if ($mes.val() && $sem.val()) { $title.val($mes.val() + " - " + $sem.val()).trigger("change"); } }
            $mes.on("change", sync); $sem.on("change", sync);
        });
    </script>';
}

// 4. Salvamento de Dados
function sts_save_cardapio_metadata($post_id) {
    if (!isset($_POST['sts_cardapio_nonce']) || !wp_verify_nonce($_POST['sts_cardapio_nonce'], 'sts_save_cardapio')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['sts_cardapio'])) update_post_meta($post_id, '_sts_cardapio_data', $_POST['sts_cardapio']);
    if (isset($_POST['sts_cardapio_mes'])) update_post_meta($post_id, '_sts_cardapio_mes', sanitize_text_field($_POST['sts_cardapio_mes']));
    if (isset($_POST['sts_cardapio_semana'])) update_post_meta($post_id, '_sts_cardapio_semana', sanitize_text_field($_POST['sts_cardapio_semana']));

    if (isset($_POST['sts_cardapio_mes']) && isset($_POST['sts_cardapio_semana'])) {
        $new_title = sanitize_text_field($_POST['sts_cardapio_mes']) . ' - ' . sanitize_text_field($_POST['sts_cardapio_semana']);
        remove_action('save_post', 'sts_save_cardapio_metadata');
        wp_update_post(['ID' => $post_id, 'post_title' => $new_title, 'post_name' => sanitize_title($new_title)]);
        add_action('save_post', 'sts_save_cardapio_metadata');
    }
}
// --- AJAX HANDLERS PARA O APP ---


function sts_ajax_get_cardapio_ingredients() {
    $ids = isset($_GET['ids']) ? explode(',', sanitize_text_field($_GET['ids'])) : [];
    if (empty($ids)) wp_send_json_error('Nenhum ID fornecido');

    $all_ingredients = [];
    foreach ($ids as $recipe_id) {
        $ing = get_post_meta($recipe_id, '_ingredientes', true);
        if (!$ing) continue;
        $lines = is_array($ing) ? $ing : explode("\n", $ing);
        foreach ($lines as $line) {
            if (!is_string($line)) continue;
            $sub_lines = explode("\n", $line);
            foreach ($sub_lines as $s) {
                $clean = trim(strip_tags($s));
                if ($clean) $all_ingredients[] = $clean;
            }
        }
    }
    wp_send_json_success(array_unique($all_ingredients));
}
add_action('wp_ajax_get_cardapio_ingredients', 'sts_ajax_get_cardapio_ingredients');
add_action('wp_ajax_nopriv_get_cardapio_ingredients', 'sts_ajax_get_cardapio_ingredients');


/**
 * 🛒 MOTOR DE LISTA DE COMPRAS (Shopping List Engine)
 */
function sts_get_cardapio_shopping_list($cardapio_id) {
    $data = get_post_meta($cardapio_id, '_sts_cardapio_data', true);
    if (!$data) return [];
    $all = [];
    foreach ($data as $dia) {
        foreach ($dia as $rid) {
            if (!$rid) continue;
            $ing = get_post_meta($rid, '_ingredientes', true);
            if (!$ing) continue;
            $lines = is_array($ing) ? $ing : explode("\n", $ing);
            foreach ($lines as $line) {
                if (!is_string($line)) continue;
                $sub_lines = explode("\n", $line);
                foreach ($sub_lines as $s) {
                    $clean = trim(strip_tags($s));
                    if ($clean) $all[] = $clean;
                }
            }
        }
    }
    return array_unique($all);
}

// Colunas Admin
add_filter('manage_sts_cardapio_posts_columns', function($columns) {
    $columns['sts_mes'] = '🗓️ Mês';
    $columns['sts_semana'] = '📅 Semana';
    return $columns;
});
add_action('manage_sts_cardapio_posts_custom_column', function($column, $post_id) {
    if ($column === 'sts_mes') echo '<strong>' . (get_post_meta($post_id, '_sts_cardapio_mes', true) ?: '-') . '</strong>';
    if ($column === 'sts_semana') echo '<span style="background:#ec5b13; color:#fff; padding:2px 8px; border-radius:4px; font-weight:bold; font-size:10px;">' . (get_post_meta($post_id, '_sts_cardapio_semana', true) ?: '-') . '</span>';
}, 10, 2);
