<?php
/**
 * Componente SEO Senior: Schema.org Article / NewsArticle / FAQ 
 * Foco total em E-E-A-T & Google Discover 2026
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Se já tiver ingredientes ou for explicitamente uma receita, não gera o Schema de Artigo
$has_ingredients = get_post_meta($post_id, '_ingredientes', true);
if (!empty($has_ingredients)) return;

$post_type = get_post_type($post_id);
$categories = get_the_category($post_id);
$cat_slugs = !empty($categories) ? wp_list_pluck($categories, 'slug') : [];

// Identificação Inteligente do Tipo
$is_faq = in_array('faq', $cat_slugs) || $post_type === 'faqs';
$is_news = in_array('noticias', $cat_slugs) || in_array('news', $cat_slugs);
$schema_type = $is_news ? "NewsArticle" : "Article";

// Dados do Autor (EEAT)
$author_id = get_the_author_meta('ID');
$author_name = get_the_author() ?: 'Redação Descomplicando Receitas';
$author_job = get_the_author_meta('job_title', $author_id) ?: 'Chef e Especialista Culinar';
$author_url = get_author_posts_url($author_id);
$author_avatar = get_avatar_url($author_id, ['size' => 120]);

$social_profiles = [
    "https://www.instagram.com/descomplicandoreceitas",
    "https://www.facebook.com/descomplicandoreceitas",
    "https://www.youtube.com/@descomplicandoreceitas",
    "https://www.pinterest.com/descomplicandoreceitas"
];

$schema = [
    "@context" => "https://schema.org/",
    "@graph" => [
        // 1. Organização
        [
            "@type" => "Organization",
            "@id" => home_url('/#organization'),
            "name" => get_bloginfo('name'),
            "url" => home_url('/'),
            "logo" => [
                "@type" => "ImageObject",
                "url" => get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png',
                "width" => 300,
                "height" => 300
            ],
            "sameAs" => $social_profiles
        ],
        // 2. Pessoa (Autor)
        [
            "@type" => "Person",
            "@id" => $author_url . '#person',
            "name" => $author_name,
            "jobTitle" => $author_job,
            "url" => $author_url,
            "image" => [
                "@type" => "ImageObject",
                "url" => $author_avatar
            ]
        ],
        // 3. O Conteúdo
        [
            "@type" => $schema_type,
            "headline" => get_the_title(),
            "description" => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            "image" => [ get_the_post_thumbnail_url($post_id, 'full') ?: '' ],
            "datePublished" => get_the_date('c'),
            "dateModified" => get_the_modified_date('c'),
            "author" => [ "@id" => $author_url . '#person' ],
            "publisher" => [ "@id" => home_url('/#organization') ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => get_permalink()
            ]
        ]
    ]
];

// Inserir FAQ se necessário
if ($is_faq) {
    $schema["@graph"][] = [
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

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
