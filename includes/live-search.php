<?php
/**
 * STS RECIPE - Sistema de Busca AJAX (Live Search)
 * Retorna resultados em tempo real conforme o usuário digita.
 */

function sts_ajax_live_search() {
    $search_term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    if (strlen($search_term) < 2) {
        wp_send_json_success(array());
    }

    $args = array(
        'post_type' => array('post', 'achadinhos', 'reviews'), // Busca em múltiplos tipos
        'posts_per_page' => 5,
        's' => $search_term,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Determinar o tipo amigável
            $type = get_post_type();
            $type_label = 'Receita';
            if ($type === 'achadinhos') $type_label = 'Produto';
            if ($type === 'reviews') $type_label = 'Avaliação';

            $results[] = array(
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://via.placeholder.com/80?text=Food',
                'type'  => $type_label,
            );
        }
    }

    wp_reset_postdata();
    wp_send_json_success($results);
}

add_action('wp_ajax_sts_live_search', 'sts_ajax_live_search');
add_action('wp_ajax_nopriv_sts_live_search', 'sts_ajax_live_search');
