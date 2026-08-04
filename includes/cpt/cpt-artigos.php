<?php
/**
 * Custom Post Type: Artigos
 */

if (!function_exists('registrar_cpt_artigos')) {

function registrar_cpt_artigos() {
    $labels = array(
        'name'                  => 'Artigos',
        'singular_name'         => 'Artigo',
        'menu_name'             => 'Artigos',
        'name_admin_bar'        => 'Artigo',
        'archives'              => 'Arquivo de Artigos',
        'attributes'            => 'Atributos do Artigo',
        'parent_item_colon'     => 'Artigo Pai:',
        'all_items'             => 'Todos os Artigos',
        'add_new_item'          => 'Adicionar Novo Artigo',
        'add_new'               => 'Adicionar Novo',
        'new_item'              => 'Novo Artigo',
        'edit_item'             => 'Editar Artigo',
        'update_item'           => 'Atualizar Artigo',
        'view_item'             => 'Ver Artigo',
        'view_items'            => 'Ver Artigos',
        'search_items'          => 'Buscar Artigos',
        'not_found'             => 'Nenhum artigo encontrado',
        'not_found_in_trash'    => 'Nenhum artigo na lixeira',
        'featured_image'        => 'Imagem Destacada',
        'set_featured_image'    => 'Definir imagem destacada',
        'remove_featured_image' => 'Remover imagem destacada',
        'use_featured_image'    => 'Usar como imagem destacada',
        'insert_into_item'      => 'Inserir no artigo',
        'uploaded_to_this_item' => 'Enviado para este artigo',
        'items_list'            => 'Lista de Artigos',
        'items_list_navigation' => 'Navegação da lista de artigos',
        'filter_items_list'     => 'Filtrar lista de artigos',
    );

    $args = array(
        'label'                 => 'Artigo',
        'description'           => 'Artigos e conteúdos informativos',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author'),
        'taxonomies'            => array('category', 'post_tag'), // Usando categorias e tags padrão
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-media-document',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'archive_slug'          => 'artigos',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'artigos',
        'rewrite'               => array(
            'slug' => 'artigos',
            'with_front' => false,
            'pages' => true,
            'feeds' => true,
        ),
        'query_var'             => true,
    );

    register_post_type('artigos', $args);
}
add_action('init', 'registrar_cpt_artigos', 0);

} // Fim do if !function_exists

// Flush rules quando o tema for ativado
if (!function_exists('flush_rewrite_rules_artigos')) {
    function flush_rewrite_rules_artigos() {
        registrar_cpt_artigos();
        flush_rewrite_rules();
    }
    add_action('after_switch_theme', 'flush_rewrite_rules_artigos');
}
// Forçar single-default.php para CPTs específicos
function custom_single_template($template) {
    $cpts = array('artigos', 'achadinhos', 'reviews', 'faqs', 'glossario');
    
    if (is_singular($cpts) && !locate_template('single-' . get_post_type() . '.php')) {
        $new_template = locate_template(array('single-default.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'custom_single_template');