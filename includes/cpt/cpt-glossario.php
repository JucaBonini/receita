<?php
/**
 * Custom Post Type: Glossário
 */

if (!function_exists('registrar_cpt_glossario')) {
    function registrar_cpt_glossario() {
        $labels = array(
            'name'                  => 'Glossário',
            'singular_name'         => 'Termo',
            'menu_name'             => 'Glossário',
            'name_admin_bar'        => 'Termo de Glossário',
            'archives'              => 'Arquivo de Glossário',
            'attributes'            => 'Atributos do Termo',
            'parent_item_colon'     => 'Termo Pai:',
            'all_items'             => 'Todos os Termos',
            'add_new_item'          => 'Adicionar Novo Termo',
            'add_new'               => 'Adicionar Novo',
            'new_item'              => 'Novo Termo',
            'edit_item'             => 'Editar Termo',
            'update_item'           => 'Atualizar Termo',
            'view_item'             => 'Ver Termo',
            'view_items'            => 'Ver Termos',
            'search_items'          => 'Buscar Termos',
            'not_found'             => 'Nenhum termo encontrado',
            'not_found_in_trash'    => 'Nenhum termo na lixeira',
            'featured_image'        => 'Imagem do Termo',
            'set_featured_image'    => 'Definir imagem do termo',
            'remove_featured_image' => 'Remover imagem do termo',
            'use_featured_image'    => 'Usar como imagem do termo',
            'insert_into_item'      => 'Inserir no termo',
            'uploaded_to_this_item' => 'Enviado para este termo',
            'items_list'            => 'Lista de Termos',
            'items_list_navigation' => 'Navegação da lista de termos',
            'filter_items_list'     => 'Filtrar lista de termos',
        );

        $args = array(
            'label'                 => 'Glossário',
            'description'           => 'Dicionário de termos culinários, ingredientes e técnicas',
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author'),
            'taxonomies'            => array('tipo_glossario', 'alfabeto'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 7,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'archive_slug'          => 'glossario',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => 'glossario',
            'rewrite'               => array(
                'slug' => 'glossario',
                'with_front' => false,
                'pages' => true,
                'feeds' => true,
            ),
            'query_var'             => true,
        );

        register_post_type('glossario', $args);
    }
}
add_action('init', 'registrar_cpt_glossario', 20);

// Registrar Taxonomia: Alfabeto (A-Z)
if (!function_exists('registrar_taxonomia_alfabeto')) {
    function registrar_taxonomia_alfabeto() {
        $labels = array(
            'name'              => 'Alfabeto',
            'singular_name'     => 'Letra',
            'search_items'      => 'Buscar Letras',
            'all_items'         => 'Todas as Letras',
            'parent_item'       => 'Letra Pai',
            'parent_item_colon' => 'Letra Pai:',
            'edit_item'         => 'Editar Letra',
            'update_item'       => 'Atualizar Letra',
            'add_new_item'      => 'Adicionar Nova Letra',
            'new_item_name'     => 'Novo Nome de Letra',
            'menu_name'         => 'Alfabeto',
        );

        $args = array(
            'hierarchical'      => true, // Para aparecer como checkboxes igual na imagem
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'alfabeto'),
        );

        register_taxonomy('alfabeto', array('glossario'), $args);
    }
    add_action('init', 'registrar_taxonomia_alfabeto', 0);
}

// Registrar Taxonomia: Tipos de Glossário
if (!function_exists('registrar_taxonomia_glossario')) {
    function registrar_taxonomia_glossario() {
        $labels = array(
            'name'              => 'Tipos de Glossário',
            'singular_name'     => 'Tipo',
            'search_items'      => 'Buscar Tipos',
            'all_items'         => 'Todos os Tipos',
            'parent_item'       => 'Tipo Pai',
            'parent_item_colon' => 'Tipo Pai:',
            'edit_item'         => 'Editar Tipo',
            'update_item'       => 'Atualizar Tipo',
            'add_new_item'      => 'Adicionar Novo Tipo',
            'new_item_name'     => 'Novo Nome de Tipo',
            'menu_name'         => 'Tipos',
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'tipo-glossario'),
        );

        register_taxonomy('tipo_glossario', array('glossario'), $args);
    }
    add_action('init', 'registrar_taxonomia_glossario', 0);
}

// Auto-popular o Alfabeto com A-Z
function popular_alfabeto_glossario() {
    if (!taxonomy_exists('alfabeto')) return;
    
    $letras = range('A', 'Z');
    foreach ($letras as $letra) {
        if (!term_exists($letra, 'alfabeto')) {
            wp_insert_term($letra, 'alfabeto');
        }
    }
}
add_action('admin_init', 'popular_alfabeto_glossario');
