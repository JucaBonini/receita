<?php
/**
 * Componente de SEO: Schema.org Recipe (JSON-LD)
 * Versão: 2.0 (Foco em E-E-A-T & Google Discover 2026)
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Formatação ISO 8601 para tempos
function dr_format_iso_duration($minutes) {
    if (!$minutes || !is_numeric($minutes)) return 'PT0M';
    return 'PT' . (int)$minutes . 'M';
}

$prep_iso  = dr_format_iso_duration($tempo_preparo);
$cook_iso  = dr_format_iso_duration($tempo_cozimento);
$total_iso = dr_format_iso_duration((int)$tempo_preparo + (int)$tempo_cozimento);

// Imagem (Prioriza Discover Large 1200px)
$image_url = get_the_post_thumbnail_url($post_id, 'discover-large') ?: get_the_post_thumbnail_url($post_id, 'full');

// Keywords
$tags = get_the_tags($post_id);
$kw_array = [];
if($tags) { foreach($tags as $tag) $kw_array[] = $tag->name; }

$schema = [
    "@context" => "https://schema.org/",
    "@type" => "Recipe",
    "name" => get_the_title(),
    "image" => [ $image_url ],
    "author" => [
        "@type" => "Person",
        "name" => $author_name,
        "jobTitle" => $author_job,
        "url" => $author_url
    ],
    "datePublished" => get_the_date('c'),
    "dateModified" => get_the_modified_date('c'),
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => get_permalink()
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => get_bloginfo('name'),
        "logo" => [
            "@type" => "ImageObject",
            "url" => get_template_directory_uri() . '/assets/images/logotipo-dr-header.png'
        ]
    ],
    "description" => wp_trim_words(get_the_excerpt(), 30),
    "prepTime" => $prep_iso,
    "cookTime" => $cook_iso,
    "totalTime" => $total_iso,
    "keywords" => !empty($kw_array) ? implode(', ', $kw_array) : "receita, culinária",
    "recipeYield" => $porcoes_meta,
    "recipeCategory" => !empty($main_cat) ? $main_cat->name : "Receitas",
    "recipeCuisine" => $cuisine_meta,
    "nutrition" => [
        "@type" => "NutritionInformation",
        "servingSize" => $nutri_serving ?: "1 porção",
        "calories" => ($calorias ?: '250') . " calories",
        "carbohydrateContent" => ($carboidratos ?: '30') . "g",
        "proteinContent" => ($proteinas ?: '10') . "g",
        "fatContent" => ($gorduras ?: '15') . "g"
    ],
    "recipeIngredient" => [],
    "recipeInstructions" => []
];

if (!empty($diet_type)) {
    $schema["suitableForDiet"] = esc_html($diet_type);
}

if (!empty($video_url)) {
    $schema["video"] = [
        "@type" => "VideoObject",
        "name" => get_the_title(),
        "description" => get_the_excerpt(),
        "thumbnailUrl" => [ $image_url ],
        "contentUrl" => $video_url,
        "embedUrl" => $video_url, // Simplificado, idealmente extrair ID
        "uploadDate" => get_the_date('c')
    ];
}

// Inserir Ingredientes
if (is_array($ingredientes_grp)) {
    foreach ($ingredientes_grp as $idx => $grupo) {
        $itens = explode("\n", $ingredientes_raw[$idx]);
        foreach ($itens as $item) {
            if (trim($item)) $schema["recipeIngredient"][] = trim($item);
        }
    }
}

// Inserir Instruções (Steps)
if (is_array($instrucoes_raw)) {
    foreach ($instrucoes_raw as $i => $step) {
        if (trim($step)) {
            $schema["recipeInstructions"][] = [
                "@type" => "HowToStep",
                "text" => trim($step),
                "name" => "Passo " . ($i + 1),
                "url" => get_permalink() . "#step-" . ($i + 1)
            ];
        }
    }
}

// Avaliação (AggregateRating)
if ($rating_count > 0) {
    $schema["aggregateRating"] = [
        "@type" => "AggregateRating",
        "ratingValue" => $rating_avg,
        "reviewCount" => $rating_count,
        "bestRating" => "5",
        "worstRating" => "1"
    ];
}

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
