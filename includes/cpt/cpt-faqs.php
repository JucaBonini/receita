<?php
/**
 * Custom Post Type: FAQs
 */

function registrar_cpt_faqs() {
    $labels = array(
        'name'                  => 'FAQs',
        'singular_name'         => 'FAQ',
        'menu_name'             => 'FAQs',
        'name_admin_bar'        => 'FAQ',
        'archives'              => 'Arquivo de FAQs',
        'attributes'            => 'Atributos da FAQ',
        'parent_item_colon'     => 'FAQ Pai:',
        'all_items'             => 'Todas as FAQs',
        'add_new_item'          => 'Adicionar Nova FAQ',
        'add_new'               => 'Adicionar Nova',
        'new_item'              => 'Nova FAQ',
        'edit_item'             => 'Editar FAQ',
        'update_item'           => 'Atualizar FAQ',
        'view_item'             => 'Ver FAQ',
        'view_items'            => 'Ver FAQs',
        'search_items'          => 'Buscar FAQs',
        'not_found'             => 'Nenhuma FAQ encontrada',
        'not_found_in_trash'    => 'Nenhuma FAQ na lixeira',
        'featured_image'        => 'Imagem Ilustrativa',
        'set_featured_image'    => 'Definir imagem ilustrativa',
        'remove_featured_image' => 'Remover imagem ilustrativa',
        'use_featured_image'    => 'Usar como imagem ilustrativa',
        'insert_into_item'      => 'Inserir na FAQ',
        'uploaded_to_this_item' => 'Enviado para esta FAQ',
        'items_list'            => 'Lista de FAQs',
        'items_list_navigation' => 'Navegação da lista de FAQs',
        'filter_items_list'     => 'Filtrar lista de FAQs',
    );

    $args = array(
        'label'                 => 'FAQ',
        'description'           => 'Perguntas Frequentes e suas respostas',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
        'taxonomies'            => array('categoria_faqs'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-editor-help',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'archive_slug'          => 'faqs',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'faqs',
        'rewrite'               => array(
            'slug' => 'faq',
            'with_front' => false
        ),
    );

    register_post_type('faqs', $args);
}
add_action('init', 'registrar_cpt_faqs', 0);