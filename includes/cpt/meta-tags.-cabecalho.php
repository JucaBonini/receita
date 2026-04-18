<?php
/**
 * META TAGS DINÂMICAS - DESCOMPLICANDO RECEITAS
 * Limpeza agressiva para evitar redundância e melhorar SEO Performance
 */

function dr_configurar_meta_tags_completas() {
    if (is_admin()) return;
    
    // 1. OPEN GRAPH DINÂMICO
    echo '<!-- Open Graph -->' . "\n";
    $site_name = get_bloginfo('name');
    $current_url = home_url($_SERVER['REQUEST_URI']);
    
    // Título e Descrição Dinâmicos
    if (is_front_page() || is_home()) {
        $og_title = 'Receitas práticas, testadas e aprovadas.';
        $og_description = 'Descomplicando Receitas é o seu guia na cozinha! Receitas práticas, testadas e aprovadas.';
    } elseif (is_singular() || is_page()) {
        $og_title = get_the_title();
        $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(strip_shortcodes(wp_strip_all_tags(get_the_content())), 30);
    } elseif (is_category()) {
        $og_title = single_cat_title('', false) . ' - ' . get_bloginfo('name');
        $og_description = category_description() ?: 'Categoria de receitas no ' . $site_name;
    } else {
        $og_title = get_bloginfo('name');
        $og_description = get_bloginfo('description');
    }
    
    // Imagem Dinâmica (Otimizada para Discover/Social)
    $og_image = get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png';
    if (is_singular() && has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(get_the_ID(), 'discover-large') ?: get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    
    // Robots & Canonical
    $canonical_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request));
    if (is_front_page() || is_home()) {
        $canonical_url = home_url('/');
    } elseif (is_singular()) {
        $canonical_url = get_permalink();
    } elseif (is_category() || is_tag() || is_tax()) {
        $canonical_url = get_term_link(get_queried_object());
    }
    
    echo '<link rel="canonical" href="' . esc_url(trailingslashit($canonical_url)) . '" />' . "\n";
    echo '<meta name="robots" content="max-image-preview:large" />' . "\n";

    // OG Output
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($current_url) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    echo '<meta property="og:image:width" content="1200" />' . "\n";
    echo '<meta property="og:image:height" content="630" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    echo '<meta property="og:locale" content="pt_BR" />' . "\n";
    
    // 2. TWITTER CARD
    echo '<!-- Twitter Card -->' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    echo '<meta name="twitter:site" content="@descomplicandoreceitas" />' . "\n";
    
    // 3. META TAGS DE VERIFICAÇÃO (Mantenha as essenciais)
    echo '<!-- Verificação -->' . "\n";
    echo '<meta name="p:domain_verify" content="645852f757b84ed974209acf2794c0cd" />' . "\n";   
    echo '<meta name="msvalidate.01" content="E3BEF536136496E86D4C035E2C36E401" />' . "\n";
    
    // 4. GEO LOCATION Removed (Optional for global reach)

    
    // 5. META TAGS GERAIS
    echo '<meta name="description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    // 5. FAVICON
    echo '<link rel="icon" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.ico') . '" type="image/x-icon" />' . "\n";
}

add_action('wp_head', 'dr_configurar_meta_tags_completas', 1);

/**
 * SCHEMA PARA HOME PAGE (Unificado)
 * Centralizado aqui as informações da Organização
 */
function dr_schema_organizacao_home() {
    if (!is_front_page() && !is_home()) return;
    
    $logo_url = get_template_directory_uri() . '/assets/images/logotipo-dr-header.png';
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url(),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => $logo_url,
                'width' => 300,
                'height' => 300
            ),
            'sameAs' => [
                'https://www.facebook.com/descomplicandoreceitas',
                'https://www.instagram.com/descomplicandoreceitas',
                'https://www.pinterest.com/descomplicandoreceitas'
            ]
        ),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => home_url('?s={search_term_string}'),
            'query-input' => 'required name=search_term_string'
        ]
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'dr_schema_organizacao_home', 5);