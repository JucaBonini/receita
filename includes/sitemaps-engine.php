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

// 2. Registrar as Variáveis do Sitemap
function sts_sitemap_query_vars($vars) {
    $vars[] = 'sts_sitemap';
    return $vars;
}
add_filter('query_vars', 'sts_sitemap_query_vars');

// 3. Roteador de Templates do Sitemap
function sts_sitemap_trigger() {
    $type = get_query_var('sts_sitemap');
    if (!$type) return;

    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    switch ($type) {
        case 'index':
            sts_render_sitemap_index();
            break;
        case 'posts':
            sts_render_sitemap_posts();
            break;
        case 'pages':
            sts_render_sitemap_pages();
            break;
        case 'categories':
            sts_render_sitemap_categories();
            break;
        default:
            $wp_query = new WP_Query(); // Reset dummy for 404
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include(get_query_template('404'));
            break;
    }
    exit;
}
add_action('template_redirect', 'sts_sitemap_trigger');

// --- RENDERIZADORES ---

function sts_render_sitemap_index() {
    $sitemaps = ['posts', 'pages', 'categories'];
    ?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <?php foreach ($sitemaps as $s) : ?>
        <sitemap>
            <loc><?php echo home_url("/sitemap-{$s}.xml/"); ?></loc>
            <lastmod><?php echo date('c'); ?></lastmod>
        </sitemap>
        <?php endforeach; ?>
    </sitemapindex>
    <?php
}

function sts_render_sitemap_posts() {
    $query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'orderby' => 'modified',
        'order' => 'DESC'
    ));
    ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
        <url>
            <loc><?php the_permalink(); ?></loc>
            <lastmod><?php echo get_the_modified_date('c'); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        <?php endwhile; wp_reset_postdata(); ?>
    </urlset>
    <?php
}

function sts_render_sitemap_pages() {
    $query = new WP_Query(array(
        'post_type' => 'page',
        'posts_per_page' => 100,
        'post_status' => 'publish'
    ));
    ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
        <url>
            <loc><?php the_permalink(); ?></loc>
            <lastmod><?php echo get_the_modified_date('c'); ?></lastmod>
            <priority>0.5</priority>
        </url>
        <?php endwhile; wp_reset_postdata(); ?>
    </urlset>
    <?php
}

function sts_render_sitemap_categories() {
    $categories = get_categories();
    ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <?php foreach ($categories as $cat) : ?>
        <url>
            <loc><?php echo get_category_link($cat->term_id); ?></loc>
            <changefreq>daily</changefreq>
            <priority>0.6</priority>
        </url>
        <?php endforeach; ?>
    </urlset>
    <?php
}
