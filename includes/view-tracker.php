<?php
/**
 * Sistema de Rastreamento Universal de Visualizações
 * Monitora Posts, Páginas e CPTs.
 */

// 1. AJAX Handler para contar visualizações (Assíncrono para Performance)
function sts_ajax_track_view() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id) wp_send_json_error('Invalid ID');

    // Ignorar admin para não inflar dados (Opcional)
    // if (current_user_can('edit_posts')) wp_send_json_success('Admin ignored');

    $views = get_post_meta($post_id, '_sts_post_views', true);
    $views = $views ? (int)$views + 1 : 1;
    update_post_meta($post_id, '_sts_post_views', $views);
    
    // Atualiza também a chave secundária se existir por compatibilidade
    update_post_meta($post_id, 'post_views_count', $views);

    wp_send_json_success($views);
}
add_action('wp_ajax_sts_track_view', 'sts_ajax_track_view');
add_action('wp_ajax_nopriv_sts_track_view', 'sts_ajax_track_view');

// 2. Injetar o ID do post no cabeçalho para o JS capturar
function sts_inject_view_tracking_data() {
    if (is_singular()) {
        echo '<meta name="sts-post-id" content="' . get_the_ID() . '">' . "\n";
    }
}
add_action('wp_head', 'sts_inject_view_tracking_data');

// 2. Adicionar coluna nos Posts e Páginas
function sts_add_views_column_to_admin($columns) {
    // Evita duplicidade no CPT de Indicações Mary
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'sts_indicacoes') {
        return $columns;
    }

    // Adiciona antes da data
    $new_columns = array();
    foreach($columns as $key => $value) {
        if($key == 'date') {
            $new_columns['sts_views'] = 'Visualizações';
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
}
add_filter('manage_posts_columns', 'sts_add_views_column_to_admin');
add_filter('manage_pages_columns', 'sts_add_views_column_to_admin');

// 3. Exibir o valor na coluna
function sts_display_views_column_content($column, $post_id) {
    if ($column === 'sts_views') {
        $views = get_post_meta($post_id, '_sts_post_views', true);
        echo '<span class="dashicons dashicons-visibility" style="margin-right:5px; color:#64748b;"></span>';
        echo '<strong>' . ($views ? number_format($views, 0, ',', '.') : '0') . '</strong>';
    }
}
add_action('manage_posts_custom_column', 'sts_display_views_column_content', 10, 2);
add_action('manage_pages_custom_column', 'sts_display_views_column_content', 10, 2);

// 4. Tornar a coluna ordenável
function sts_views_column_sortable($columns) {
    $columns['sts_views'] = 'sts_views';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'sts_views_column_sortable');
add_filter('manage_edit-page_sortable_columns', 'sts_views_column_sortable');

// 5. Ajuste na query de ordenação
function sts_views_orderby($query) {
    if (!is_admin()) return;

    $orderby = $query->get('orderby');
    if ('sts_views' == $orderby) {
        $query->set('meta_key', '_sts_post_views');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sts_views_orderby');
