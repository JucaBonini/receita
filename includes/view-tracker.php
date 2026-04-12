<?php
/**
 * Sistema de Rastreamento Universal de Visualizações
 * Monitora Posts, Páginas e CPTs.
 */

// 1. Rastrear a visualização quando a página é carregada
function sts_track_all_views() {
    // Apenas em posts, páginas ou CPTs individuais, e não para usuários logados com poder de edição (evita inflar dados)
    if ( !is_singular() ) return;
    
    // Opcional: Descomente a linha abaixo se quiser ignorar as suas próprias visitas
    // if ( is_user_logged_in() && current_user_can('edit_posts') ) return;

    global $post;
    $post_id = $post->ID;
    $views = get_post_meta($post_id, '_sts_post_views', true);
    $views = $views ? (int)$views + 1 : 1;
    update_post_meta($post_id, '_sts_post_views', $views);
}
add_action('wp_head', 'sts_track_all_views');

// 2. Adicionar coluna nos Posts e Páginas
function sts_add_views_column_to_admin($columns) {
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
