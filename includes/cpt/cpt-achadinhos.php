<?php
/**
 * Custom Post Type: Achadinhos da Amazon
 */

// Verificar se a função já não foi declarada
if (!function_exists('registrar_cpt_achadinhos')) {

function registrar_cpt_achadinhos() {
    $labels = array(
        'name'                  => 'Achadinhos da Amazon',
        'singular_name'         => 'Achadinho',
        'menu_name'             => 'Achadinhos Amazon',
        'name_admin_bar'        => 'Achadinho',
        'archives'              => 'Arquivo de Achadinhos',
        'attributes'            => 'Atributos do Achadinho',
        'parent_item_colon'     => 'Achadinho Pai:',
        'all_items'             => 'Todos os Achadinhos',
        'add_new_item'          => 'Adicionar Novo Achadinho',
        'add_new'               => 'Adicionar Novo',
        'new_item'              => 'Novo Achadinho',
        'edit_item'             => 'Editar Achadinho',
        'update_item'           => 'Atualizar Achadinho',
        'view_item'             => 'Ver Achadinho',
        'view_items'            => 'Ver Achadinhos',
        'search_items'          => 'Buscar Achadinhos',
        'not_found'             => 'Nenhum achadinho encontrado',
        'not_found_in_trash'    => 'Nenhum achadinho na lixeira',
        'featured_image'        => 'Imagem do Produto',
        'set_featured_image'    => 'Definir imagem do produto',
        'remove_featured_image' => 'Remover imagem do produto',
        'use_featured_image'    => 'Usar como imagem do produto',
        'insert_into_item'      => 'Inserir no achadinho',
        'uploaded_to_this_item' => 'Enviado para este achadinho',
        'items_list'            => 'Lista de Achadinhos',
        'items_list_navigation' => 'Navegação da lista de achadinhos',
        'filter_items_list'     => 'Filtrar lista de achadinhos',
    );

    $args = array(
        'label'                 => 'Achadinho',
        'description'           => 'Produtos achados na Amazon com boas ofertas',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions'),
        'taxonomies'            => array('categoria_achadinhos'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 9,
        'menu_icon'             => 'dashicons-amazon',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'archive_slug'          => 'achadinhos-amazon',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'achadinhos',
        'rewrite'               => array(
            'slug' => 'achadinhos',
            'with_front' => false
        ),
    );

    register_post_type('achadinhos', $args);
}
add_action('init', 'registrar_cpt_achadinhos', 0);

} // Fim do if !function_exists