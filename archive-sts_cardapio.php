<?php
/**
 * Archive Template: Redirecionamento de Cardápio Ativo
 * Endedeço: /cardapio-da-semana/
 */

$args = array(
    'post_type'      => 'sts_cardapio',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'date_query'     => array(
        array(
            'before'    => current_time('mysql'),
            'inclusive' => true,
        ),
    ),
);

$latest_cardapio = new WP_Query($args);

if ($latest_cardapio->have_posts()) {
    $latest_cardapio->the_post();
    wp_redirect(get_permalink());
    exit;
} else {
    // Caso nenhum cardápio tenha sido criado ainda
    wp_redirect(home_url('/'));
    exit;
}
