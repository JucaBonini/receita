<?php
/**
 * Componente SEO: Schema.org Article / NewsArticle / FAQ / Review
 * Versão: 2.5 (Foco em E-E-A-T & Google Discover 2026)
 * Redireciona logicamente posts que não são receitas para o Schema ideal.
 */

if (!isset($post_id)) $post_id = get_the_ID();
$post_type = get_post_type($post_id);
$categories = get_the_category($post_id);
$cat_slugs = !empty($categories) ? wp_list_pluck($categories, 'slug') : [];

// Identificação do Tipo de Schema
$is_faq    = in_array('faq', $cat_slugs) || in_array('perguntas-frequentes', $cat_slugs) || $post_type === 'faqs';
$is_review = in_array('review', $cat_slugs) || in_array('analise', $cat_slugs) || $post_type === 'reviews';
$is_news   = in_array('noticias', $cat_slugs) || in_array('news', $cat_slugs);

// Configuração Base (Article/NewsArticle)
$schema_type = $is_news ? "NewsArticle" : "Article";

// Author Social Profiles (EEAT)
$author_id = get_the_author_meta('ID');
$social_links = [
    "https://www.instagram.com/descomplicandoreceitas",
    "https://www.facebook.com/descomplicandoreceitas"
];

$schema = [
    "@context" => "https://schema.org",
    "@type" => $schema_type,
    "headline" => get_the_title(),
    "description" => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
    "image" => [
        get_the_post_thumbnail_url($post_id, 'full') ?: get_template_directory_uri() . '/assets/images/default-image.webp'
    ],
    "datePublished" => get_the_date('c'),
    "dateModified" => get_the_modified_date('c'),
    "author" => [
        "@type" => "Person",
        "name" => get_the_author(),
        "url" => get_author_posts_url($author_id),
        "jobTitle" => get_the_author_meta('job_title', $author_id) ?: "Especialista em Gastronomia",
        "sameAs" => $social_links
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => get_bloginfo('name'),
        "logo" => [
            "@type" => "ImageObject",
            "url" => get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png'
        ]
    ],
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => get_permalink()
    ]
];

// Adaptive Logic: FAQPage
if ($is_faq) {
    $schema = [
        "@context" => "https://schema.org",
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

// Adaptive Logic: Review
if ($is_review) {
    $schema["@type"] = "Review";
    $schema["reviewAspect"] = "Culinária e Utensílios";
    $schema["reviewBody"] = get_the_excerpt() ?: get_the_title();
    $schema["itemReviewed"] = [
        "@type" => "Product",
        "name" => get_the_title(),
        "image" => get_the_post_thumbnail_url($post_id, 'large')
    ];
    $schema["reviewRating"] = [
        "@type" => "Rating",
        "ratingValue" => "5",
        "bestRating" => "5"
    ];
}

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
