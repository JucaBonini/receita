<?php
/**
 * Componente de SEO Senior: Schema.org Recipe (JSON-LD)
 * Foco total em E-E-A-T & Google Search Console 2026
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Formatação ISO 8601 para tempos
function dr_format_iso_duration($minutes) {
    if (is_string($minutes)) {
        preg_match_all('!\d+!', $minutes, $matches);
        $minutes = isset($matches[0][0]) ? $matches[0][0] : 20;
    }
    $min = (int)$minutes ?: 20;
    return 'PT' . $min . 'M';
}

$prep_iso  = dr_format_iso_duration($tempo_preparo);
$cook_iso  = dr_format_iso_duration($tempo_cozimento);
$total_iso = dr_format_iso_duration(sts_get_recipe_total_time($post_id));

// Redes Sociais da Organização (Mesmo sem plugin, definimos aqui para Autoridade)
$social_profiles = [
    "https://www.instagram.com/descomplicandoreceitas",
    "https://www.facebook.com/descomplicandoreceitas",
    "https://www.youtube.com/@descomplicandoreceitas"
];

$schema = [
    "@context" => "https://schema.org/",
    "@graph" => [
        // 1. Organização (O Blog)
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
        // 2. Pessoa (O Autor/Chef)
        [
            "@type" => "Person",
            "@id" => $author_url . '#person',
            "name" => $author_name,
            "jobTitle" => $author_job,
            "description" => get_the_author_meta('description', $author_id),
            "image" => [
                "@type" => "ImageObject",
                "url" => $author_avatar
            ],
            "url" => $author_url,
            "knowsAbout" => [ "Gastronomia", "Culinária Brasileira", "Nutrição" ],
            "hasCredential" => [
                [
                    "@type" => "EducationalOccupationalCredential",
                    "name" => get_the_author_meta('education', $author_id) ?: "Chef de Cozinha Profissional"
                ]
            ]
        ],
        // 3. A Receita em si
        [
            "@type" => "Recipe",
            "name" => get_the_title(),
            "image" => [ get_the_post_thumbnail_url($post_id, 'full') ?: '' ],
            "author" => [ "@id" => $author_url . '#person' ],
            "datePublished" => get_the_date('c'),
            "dateModified" => get_the_modified_date('c'),
            "description" => wp_trim_words(get_the_excerpt() ?: get_the_content(), 40),
            "prepTime" => $prep_iso,
            "cookTime" => $cook_iso,
            "totalTime" => $total_iso,
            "recipeYield" => $porcoes_meta ?: "4 porções",
            "recipeCategory" => !empty($main_cat) ? $main_cat->name : "Prato Principal",
            "recipeCuisine" => $cuisine_meta ?: "Brasileira",
            "keywords" => get_the_tag_list('', ', ', '', $post_id) ?: "receita, culinária",
            "publisher" => [ "@id" => home_url('/#organization') ],
            "nutrition" => [
                "@type" => "NutritionInformation",
                "calories" => ($calorias ?: '250') . " calories",
                "carbohydrateContent" => ($carboidratos ?: '30') . "g",
                "proteinContent" => ($proteinas ?: '10') . "g",
                "fatContent" => ($gorduras ?: '15') . "g"
            ],
            "recipeIngredient" => [],
            "recipeInstructions" => [],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => (float)$display_rating_avg,
                "reviewCount" => (int)$display_rating_count,
                "bestRating" => 5,
                "worstRating" => 1
            ]
        ]
    ]
];

// Inserir Ingredientes (Loop Otimizado)
if (!empty($ingredientes_raw)) {
    $itens = is_array($ingredientes_raw) ? $ingredientes_raw : explode("\n", $ingredientes_raw);
    foreach ($itens as $item) {
        if (trim($item)) $schema["@graph"][2]["recipeIngredient"][] = esc_html(trim($item));
    }
}

// Inserir Instruções (Loop Otimizado com Passos)
if (!empty($instrucoes_raw)) {
    $steps = is_array($instrucoes_raw) ? $instrucoes_raw : explode("\n", $instrucoes_raw);
    foreach ($steps as $i => $step) {
        if (trim($step)) {
            $schema["@graph"][2]["recipeInstructions"][] = [
                "@type" => "HowToStep",
                "text" => esc_html(trim($step)),
                "name" => "Passo " . ($i + 1),
                "url" => get_permalink() . "#step-" . ($i + 1)
            ];
        }
    }
}

// Inserir Vídeo Object (Se existir)
if (!empty($video_url)) {
    $schema["@graph"][2]["video"] = [
        "@type" => "VideoObject",
        "name" => get_the_title(),
        "description" => get_the_excerpt() ?: get_the_title(),
        "thumbnailUrl" => [ get_the_post_thumbnail_url($post_id, 'full') ],
        "contentUrl" => $video_url,
        "uploadDate" => get_the_date('c')
    ];
}

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
