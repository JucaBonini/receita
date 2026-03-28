<?php
/**
 * Taxonomias personalizadas para os CPTs
 */

// Taxonomia para Achadinhos
function registrar_taxonomia_achadinhos() {
    $labels = array(
        'name'              => 'Categorias de Achadinhos',
        'singular_name'     => 'Categoria de Achadinho',
        'search_items'      => 'Buscar Categorias',
        'all_items'         => 'Todas as Categorias',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria Pai:',
        'edit_item'         => 'Editar Categoria',
        'update_item'       => 'Atualizar Categoria',
        'add_new_item'      => 'Adicionar Nova Categoria',
        'new_item_name'     => 'Novo Nome da Categoria',
        'menu_name'         => 'Categorias',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categoria-achadinhos'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_achadinhos', array('achadinhos'), $args);
}
add_action('init', 'registrar_taxonomia_achadinhos');

// Taxonomia para FAQs
function registrar_taxonomia_faqs() {
    $labels = array(
        'name'              => 'Categorias de FAQs',
        'singular_name'     => 'Categoria de FAQ',
        'search_items'      => 'Buscar Categorias',
        'all_items'         => 'Todas as Categorias',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria Pai:',
        'edit_item'         => 'Editar Categoria',
        'update_item'       => 'Atualizar Categoria',
        'add_new_item'      => 'Adicionar Nova Categoria',
        'new_item_name'     => 'Novo Nome da Categoria',
        'menu_name'         => 'Categorias FAQ',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categoria-faq'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_faqs', array('faqs'), $args);
}
add_action('init', 'registrar_taxonomia_faqs');

// Taxonomia para Reviews
function registrar_taxonomia_reviews() {
    $labels = array(
        'name'              => 'Categorias de Reviews',
        'singular_name'     => 'Categoria de Review',
        'search_items'      => 'Buscar Categorias',
        'all_items'         => 'Todas as Categorias',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria Pai:',
        'edit_item'         => 'Editar Categoria',
        'update_item'       => 'Atualizar Categoria',
        'add_new_item'      => 'Adicionar Nova Categoria',
        'new_item_name'     => 'Novo Nome da Categoria',
        'menu_name'         => 'Categorias Review',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categoria-review'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_reviews', array('reviews'), $args);
}
add_action('init', 'registrar_taxonomia_reviews');

// Flush rewrite rules quando necessário
function custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'custom_flush_rewrite_rules');