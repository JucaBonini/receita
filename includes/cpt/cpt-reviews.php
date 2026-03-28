<?php
/**
 * Custom Post Type: Reviews
 */

function registrar_cpt_reviews() {
    $labels = array(
        'name'                  => 'Reviews',
        'singular_name'         => 'Review',
        'menu_name'             => 'Reviews',
        'name_admin_bar'        => 'Review',
        'archives'              => 'Arquivo de Reviews',
        'attributes'            => 'Atributos do Review',
        'parent_item_colon'     => 'Review Pai:',
        'all_items'             => 'Todos os Reviews',
        'add_new_item'          => 'Adicionar Novo Review',
        'add_new'               => 'Adicionar Novo',
        'new_item'              => 'Novo Review',
        'edit_item'             => 'Editar Review',
        'update_item'           => 'Atualizar Review',
        'view_item'             => 'Ver Review',
        'view_items'            => 'Ver Reviews',
        'search_items'          => 'Buscar Reviews',
        'not_found'             => 'Nenhum review encontrado',
        'not_found_in_trash'    => 'Nenhum review na lixeira',
        'featured_image'        => 'Imagem do Produto',
        'set_featured_image'    => 'Definir imagem do produto',
        'remove_featured_image' => 'Remover imagem do produto',
        'use_featured_image'    => 'Usar como imagem do produto',
        'insert_into_item'      => 'Inserir no review',
        'uploaded_to_this_item' => 'Enviado para este review',
        'items_list'            => 'Lista de Reviews',
        'items_list_navigation' => 'Navegação da lista de reviews',
        'filter_items_list'     => 'Filtrar lista de reviews',
    );

    $args = array(
        'label'                 => 'Review',
        'description'           => 'Análises e reviews de produtos',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions'),
        'taxonomies'            => array('categoria_reviews'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-star-filled',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'archive_slug'          => 'reviews',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'reviews',
        'rewrite'               => array(
            'slug' => 'review',
            'with_front' => false
        ),
    );

    register_post_type('reviews', $args);
}
add_action('init', 'registrar_cpt_reviews', 0);