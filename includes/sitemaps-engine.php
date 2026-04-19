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
add_action('init', 'sts_sitemap_trigger', 1);

function sts_sitemap_trigger() {
    // Detecta se é um pedido de sitemap via URL bruta
    $uri = $_SERVER['REQUEST_URI'];
    $type = '';

    if (preg_match('/sitemap-([a-z0-9\-]+)\.xml/i', $uri, $matches)) {
        $type = $matches[1];
    } elseif (preg_match('/sitemap_index\.xml/i', $uri)) {
        $type = 'index';
    }

    if (!$type) return;

    // Desativa compressão e cache para evitar sujeira no XML
    if (function_exists('ini_set')) {
        @ini_set('zlib.output_compression', 'Off');
    }

    // LIMPEZA SUPREMA
    while (ob_get_level()) {
        ob_end_clean();
    }
    ob_start();

    header('Content-Type: text/xml; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    
    // Echo direto sem quebra de linha entre declaração e conteúdo
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    switch ($type) {
        case 'index':
            sts_render_sitemap_index();
            break;
        case 'category':
            sts_render_sitemap_categories();
            break;
        default:
            $allowed_types = sts_get_sitemap_types();
            if (in_array($type, $allowed_types)) {
                sts_render_generic_sitemap($type);
            } else {
                status_header(404);
                echo 'Sitemap not found';
            }
            break;
    }
    
    // Finaliza e garante que nada mais seja impresso
    ob_end_flush();
    exit;
}

// --- RENDERIZADORES ---

/**
 * Filtra tipos de post que NÃO devem ir para o Sitemap
 */
function sts_get_sitemap_types() {
    $types = get_post_types(['public' => true, 'exclude_from_search' => false], 'names');
    // Remove o que é lixo ou redundante
    $exclude = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request'];
    return array_diff($types, $exclude);
}

function sts_render_sitemap_index() {
    $post_types = sts_get_sitemap_types();
    $xml = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($post_types as $type) {
        $xml .= '<sitemap>';
        $xml .= '<loc>' . home_url("/sitemap-{$type}.xml/") . '</loc>';
        $xml .= '<lastmod>' . date('c') . '</lastmod>';
        $xml .= '</sitemap>';
    }
    $xml .= '<sitemap>';
    $xml .= '<loc>' . home_url('/sitemap-category.xml/') . '</loc>';
    $xml .= '<lastmod>' . date('c') . '</lastmod>';
    $xml .= '</sitemap>';
    $xml .= '</sitemapindex>';
    echo $xml;
}

function sts_render_generic_sitemap($post_type) {
    $query = new WP_Query(array(
        'post_type' => $post_type,
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'orderby' => 'modified',
        'order' => 'DESC'
    ));
    $priority = ($post_type === 'post') ? '1.0' : '0.8';
    $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    while ($query->have_posts()) {
        $query->the_post();
        $xml .= '<url>';
        $xml .= '<loc>' . get_permalink() . '</loc>';
        $xml .= '<lastmod>' . get_the_modified_date('c') . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= '</url>';
    }
    wp_reset_postdata();
    $xml .= '</urlset>';
    echo $xml;
}

function sts_render_sitemap_categories() {
    $categories = get_categories(['hide_empty' => true]);
    $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($categories as $cat) {
        $xml .= '<url>';
        $xml .= '<loc>' . get_category_link($cat->term_id) . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.6</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';
    echo $xml;
}
