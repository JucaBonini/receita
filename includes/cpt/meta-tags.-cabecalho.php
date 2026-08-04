<?php
/**
 * META TAGS DINÂMICAS - DESCOMPLICANDO RECEITAS
 * Limpeza agressiva para evitar redundância e melhorar SEO Performance
 *//**
 * OBSOLETO: Todas as metatags principais agora são gerenciadas unicamente
 * pelo sts_render_seo_meta() no includes/seo-engine.php para evitar duplicidade crítica.
 */
function dr_configurar_meta_tags_completas() {
    // Desativado para evitar conflito de robots/canonical com o motor de SEO oficial
}
// add_action('wp_head', 'dr_configurar_meta_tags_completas', 1);

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