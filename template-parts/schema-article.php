<?php
/**
 * Componente SEO God Mode: Schema.org Article / NewsArticle / Graph
 * Foco em E-E-A-T e Indexação de Vídeo em Artigos/Reviews
 * Autor: Antigravity (Nível Especialista Mundial)
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Se já tiver ingredientes, o schema-recipe.php assume o controle
$has_ingredients = get_post_meta($post_id, '_ingredientes', true);
if (!empty($has_ingredients)) return;

$video_url = get_post_meta($post_id, '_video_url', true);
$post_type = get_post_type($post_id);
$categories = get_the_category($post_id);
$cat_slugs = !empty($categories) ? wp_list_pluck($categories, 'slug') : [];

// Identificação do Tipo
$is_faq = in_array('faq', $cat_slugs) || $post_type === 'faqs';
$is_news = in_array('noticias', $cat_slugs) || in_array('news', $cat_slugs);
$schema_type = $is_news ? "NewsArticle" : "Article";

// Dados do Autor e Site
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id) ?: 'Equipe Descomplicando Receitas';
$author_url = get_author_posts_url($author_id);
$author_avatar = get_avatar_url($author_id);
$site_name = get_bloginfo('name');
$site_url = home_url('/');
$logo_url = get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png';
$main_image = get_the_post_thumbnail_url($post_id, 'full') ?: get_template_directory_uri() . '/assets/images/default-image.webp';

$graph = [];

// 1. Organization
$graph[] = [
    "@type" => "Organization",
    "@id" => $site_url . "#organization",
    "name" => $site_name,
    "url" => $site_url,
    "logo" => [
        "@type" => "ImageObject",
        "@id" => $site_url . "#logo",
        "url" => $logo_url,
        "width" => 300,
        "height" => 300
    ]
];

// 2. WebPage
$graph[] = [
    "@type" => "WebPage",
    "@id" => get_permalink($post_id) . "#webpage",
    "url" => get_permalink($post_id),
    "name" => get_the_title(),
    "isPartOf" => ["@id" => $site_url . "#organization"],
    "datePublished" => get_the_date('c', $post_id),
    "dateModified" => get_the_modified_date('c', $post_id),
    "inLanguage" => get_locale()
];

// 3. VideoObject Logic
$video_obj = null;
if (!empty($video_url)) {
    $video_id = "";
    $thumbnail_fallback = $main_image;
    
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match)) {
        $video_id = $match[1];
        $video_obj = [
            "@type" => "VideoObject",
            "name" => get_the_title(),
            "description" => wp_trim_words(get_the_excerpt($post_id), 30),
            "thumbnailUrl" => [
                "https://img.youtube.com/vi/$video_id/maxresdefault.jpg",
                $thumbnail_fallback
            ],
            "uploadDate" => get_the_date('c', $post_id),
            "contentUrl" => $video_url,
            "embedUrl" => "https://www.youtube.com/embed/$video_id"
        ];
    } elseif (preg_match('/\.(mp4|webm|ogv)/i', $video_url)) {
        $video_obj = [
            "@type" => "VideoObject",
            "name" => get_the_title(),
            "description" => wp_trim_words(get_the_excerpt($post_id), 30),
            "thumbnailUrl" => [ $thumbnail_fallback ],
            "uploadDate" => get_the_date('c', $post_id),
            "contentUrl" => $video_url
        ];
    }
}

// 4. Article
$article = [
    "@type" => $schema_type,
    "@id" => get_permalink($post_id) . "#article",
    "isPartOf" => ["@id" => get_permalink($post_id) . "#webpage"],
    "author" => [
        "@type" => "Person",
        "name" => $author_name,
        "url" => $author_url,
        "image" => $author_avatar
    ],
    "headline" => get_the_title(),
    "datePublished" => get_the_date('c', $post_id),
    "dateModified" => get_the_modified_date('c', $post_id),
    "mainEntityOfPage" => ["@id" => get_permalink($post_id) . "#webpage"],
    "publisher" => ["@id" => $site_url . "#organization"],
    "image" => [ $main_image ],
    "description" => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40)
];

if ($video_obj) {
    $article["video"] = $video_obj;
}

$graph[] = $article;

// 5. FAQ (Opcional)
if ($is_faq) {
    $graph[] = [
        "@type" => "FAQPage",
        "mainEntity" => [[
            "@type" => "Question",
            "name" => get_the_title(),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => wp_strip_all_tags(get_the_content())
            ]
        ]]
    ];
}

echo "\n" . '<script type="application/ld+json">' . "\n";
echo json_encode(["@context" => "https://schema.org", "@graph" => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
echo "\n" . '</script>' . "\n";

