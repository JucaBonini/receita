<?php
/**
 * Motor de Sitemap Nativo (Plugin-Free 2026)
 * Gera sitemaps dinâmicos para Posts, Páginas e Categorias.
 */

// 1. Registrar as Regras de Escrita (Rewrite Rules)
function sts_sitemap_rewrite_rules() {
    add_rewrite_rule('^sitemap_index\.xml/?$', 'index.php?sts_sitemap=index', 'top');
    add_rewrite_rule('^sitemap-(.+)\.xml/?$', 'index.php?sts_sitemap=$matches[1]', 'top');
}
add_action('init', 'sts_sitemap_rewrite_rules');

// 2. Registra o Roteamento do Sitemap
add_action('init', 'sts_sitemap_trigger', 99);

function sts_sitemap_trigger() {
    // Detecta se é um pedido de sitemap via URL bruta (previne injeções de outros plugins)
    $uri = $_SERVER['REQUEST_URI'];
    $type = '';

    if (preg_match('/sitemap-([a-z0-9\-]+)\.xml/i', $uri, $matches)) {
        $type = $matches[1];
        // Aliases para facilitar a vida do Google e do Usuário
        $aliases = [
            'webstories' => 'web-story',
            'stories'    => 'web-story',
            'posts'      => 'post',
            'pages'      => 'page'
        ];
        if (isset($aliases[$type])) {
            $type = $aliases[$type];
        }
    } elseif (preg_match('/sitemap_index\.xml/i', $uri)) {
        $type = 'index';
    }

    if (!$type) return;

    if (function_exists('ini_set')) {
        @ini_set('zlib.output_compression', 'Off');
    }

    // LIMPEZA SUPREMA
    while (ob_get_level()) {
        ob_end_clean();
    }
    ob_start();

    $xml_output = '<?xml version="1.0" encoding="UTF-8"?>';

    switch ($type) {
        case 'index':
            $xml_output .= sts_get_sitemap_index_xml();
            break;
        case 'category':
            $xml_output .= sts_get_sitemap_categories_xml();
            break;
        default:
            $allowed_types = sts_get_sitemap_types();
            if (in_array($type, $allowed_types)) {
                $xml_output .= sts_get_generic_sitemap_xml($type);
            } else {
                status_header(404);
                echo 'Sitemap "' . $type . '" not found. Allowed: ' . implode(', ', $allowed_types);
                exit;
            }
            break;
    }
    
    header('Content-Type: text/xml; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    echo trim($xml_output);
    exit;
}

// --- RENDERIZADORES ---

/**
 * Filtra tipos de post que NÃO devem ir para o Sitemap
 */
function sts_get_sitemap_types() {
    $types = get_post_types(['public' => true], 'names');
    
    // Força a inclusão de Web Stories caso o plugin as tenha registrado como privadas na busca
    if (post_type_exists('web-story')) {
        $types['web-story'] = 'web-story';
    }

    // Remove o que é lixo ou redundante
    $exclude = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation'];
    return array_diff($types, $exclude);
}

function sts_get_sitemap_index_xml() {
    $post_types = sts_get_sitemap_types();
    $xml = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($post_types as $type) {
        $xml .= '<sitemap><loc>' . home_url("/sitemap-{$type}.xml/") . '</loc><lastmod>' . date('c') . '</lastmod></sitemap>';
    }
    $xml .= '<sitemap><loc>' . home_url('/sitemap-category.xml/') . '</loc><lastmod>' . date('c') . '</lastmod></sitemap>';
    $xml .= '</sitemapindex>';
    return $xml;
}

function sts_get_generic_sitemap_xml($post_type) {
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'orderby' => 'modified',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_sts_seo_noindex',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key' => '_sts_seo_noindex',
                'value' => '1',
                'compare' => '!=',
            ),
        ),
    );

    if ($post_type === 'page') {
        $args['post_name__not_in'] = array('entrar', 'cadastrar', 'meu-painel', 'meu-perfil', 'perfil', 'login', 'register', 'checkout', 'cart');
    }

    $query = new WP_Query($args);
    $priority = ($post_type === 'post') ? '1.0' : '0.8';
    
    // NAMESPACE GOD MODE: Inclui suporte a IMAGENS
    $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
    
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $slug = get_post_field('post_name', $post_id);
        
        if ($post_type === 'page' && in_array($slug, array('entrar', 'cadastrar', 'meu-painel', 'perfil'))) continue;

        $xml .= '<url>';
        $xml .= '<loc>' . get_permalink() . '</loc>';
        $xml .= '<lastmod>' . get_the_modified_date('c') . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        
        // ADIÇÃO GOD MODE: Incluir Imagem de Destaque no Sitemap
        if (has_post_thumbnail($post_id)) {
            $thumb_id = get_post_thumbnail_id($post_id);
            $thumb_url = wp_get_attachment_image_url($thumb_id, 'full');
            $thumb_title = get_the_title($post_id);
            
            if ($thumb_url) {
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . esc_url($thumb_url) . '</image:loc>';
                $xml .= '<image:title>' . esc_xml($thumb_title) . '</image:title>';
                $xml .= '</image:image>';
            }
        }
        
        $xml .= '</url>';
    }
    wp_reset_postdata();
    $xml .= '</urlset>';
    return $xml;
}

function sts_get_sitemap_categories_xml() {
    $categories = get_categories(['hide_empty' => true]);
    $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
    foreach ($categories as $cat) {
        $xml .= '<url>';
        $xml .= '<loc>' . get_category_link($cat->term_id) . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.6</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';
    return $xml;
}
