<?php
/**
 * Componente de SEO: Schema.org Recipe (JSON-LD)
 * Versão: 2.0 (Foco em E-E-A-T & Google Discover 2026)
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Formatação ISO 8601 para tempos (Correção do Erro PT0M)
function dr_format_iso_duration($minutes) {
    $min = (int)$minutes;
    if ($min <= 0) $min = 20; // Fallback para não quebrar o Schema (Minutos padrão)
    return 'PT' . $min . 'M';
}

$prep_iso  = dr_format_iso_duration($tempo_preparo);
$cook_iso  = dr_format_iso_duration($tempo_cozimento);
$total_iso = dr_format_iso_duration((int)($tempo_preparo ?: 20) + (int)($tempo_cozimento ?: 10));

// Imagem (Prioriza Discover Large 1200px)
$image_url = get_the_post_thumbnail_url($post_id, 'full') ?: '';

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
        "name" => $author_name ?: get_the_author(),
        "jobTitle" => $author_job ?: "Especialista em Gastronomia",
        "url" => $author_url ?: get_author_posts_url(get_the_author_meta('ID'))
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
            "url" => get_template_directory_uri() . '/assets/images/logo.png' // Verifique se este caminho existe
        ]
    ],
    "description" => wp_trim_words(get_the_excerpt() ?: get_the_content(), 40),
    "prepTime" => $prep_iso,
    "cookTime" => $cook_iso,
    "totalTime" => $total_iso,
    "keywords" => !empty($kw_array) ? implode(', ', $kw_array) : "receita, culinária, fácil",
    "recipeYield" => $porcoes_meta ?: "4 porções",
    "recipeCategory" => !empty($main_cat) ? $main_cat->name : "Receitas",
    "recipeCuisine" => $cuisine_meta ?: "Brasileira",
    "nutrition" => [
        "@type" => "NutritionInformation",
        "calories" => ($calorias ?: '250') . " calories",
        "carbohydrateContent" => ($carboidratos ?: '30') . "g",
        "proteinContent" => ($proteinas ?: '10') . "g",
        "fatContent" => ($gorduras ?: '15') . "g"
    ],
    "recipeIngredient" => [],
    "recipeInstructions" => []
];

// Inserir Vídeo (Fator Crítico de Ranking 2026)
if (!empty($video_url)) {
    $schema["video"] = [
        "@type" => "VideoObject",
        "name" => get_the_title(),
        "description" => get_the_excerpt() ?: get_the_title(),
        "thumbnailUrl" => [ $image_url ],
        "contentUrl" => $video_url,
        "embedUrl" => str_contains($video_url, 'youtube.com') ? str_replace('watch?v=', 'embed/', $video_url) : $video_url,
        "uploadDate" => get_the_date('c')
    ];
}

if (!empty($diet_type)) {
    $schema["suitableForDiet"] = esc_html($diet_type);
}

// Inserir Ingredientes (Suporte para Array e String)
if (is_array($ingredientes_grp) && !empty($ingredientes_grp)) {
    foreach ($ingredientes_grp as $idx => $grupo) {
        if (isset($ingredientes_raw[$idx])) {
            $itens = explode("\n", $ingredientes_raw[$idx]);
            foreach ($itens as $item) {
                if (trim($item)) $schema["recipeIngredient"][] = esc_html(trim($item));
            }
        }
    }
} elseif (!empty($ingredientes_raw)) {
    // Caso seja apenas um campo de texto simples
    $itens = is_array($ingredientes_raw) ? $ingredientes_raw : explode("\n", $ingredientes_raw);
    foreach ($itens as $item) {
        if (is_string($item) && trim($item)) $schema["recipeIngredient"][] = esc_html(trim($item));
    }
}

// Inserir Instruções (Suporte para Array e String)
if (is_array($instrucoes_raw) && !empty($instrucoes_raw)) {
    foreach ($instrucoes_raw as $i => $step) {
        if (trim($step)) {
            $schema["recipeInstructions"][] = [
                "@type" => "HowToStep",
                "text" => esc_html(trim($step)),
                "name" => "Passo " . ($i + 1),
                "url" => get_permalink() . "#step-" . ($i + 1)
            ];
        }
    }
} elseif (!empty($instrucoes_raw) && is_string($instrucoes_raw)) {
    $steps = explode("\n", $instrucoes_raw);
    foreach ($steps as $i => $step) {
        if (trim($step)) {
            $schema["recipeInstructions"][] = [
                "@type" => "HowToStep",
                "text" => esc_html(trim($step)),
                "name" => "Passo " . ($i + 1)
            ];
        }
    }
}

// Avaliação (AggregateRating)
// Caminho do Meio: Usamos os valores calculados no single.php (que incluem o fallback do autor)
$schema["aggregateRating"] = [
    "@type" => "AggregateRating",
    "ratingValue" => $display_rating_avg,
    "reviewCount" => $display_rating_count,
    "bestRating" => "5",
    "worstRating" => "1"
];

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
