<?php
/**
 * Componente de SEO Senior: Schema.org Recipe (JSON-LD) - VERSÃO AUTO-SUFICIENTE
 * Busca os dados diretamente do banco para evitar erros de variáveis vazias.
 */

if (!isset($post_id)) $post_id = get_the_ID();

// 1. Busca de Metadados (Garantindo que os dados existam)
$tempo_preparo   = get_post_meta($post_id, '_tempo_preparo', true);
$tempo_cozimento = get_post_meta($post_id, '_tempo_cozimento', true);
$porcoes_meta    = get_post_meta($post_id, '_porcoes', true);
$calorias        = get_post_meta($post_id, '_calorias', true);
$carboidratos    = get_post_meta($post_id, '_carboidratos', true);
$proteinas       = get_post_meta($post_id, '_proteinas', true);
$gorduras        = get_post_meta($post_id, '_gorduras', true);
$video_url       = get_post_meta($post_id, '_video_url', true);
$ingredientes_raw = get_post_meta($post_id, '_ingredientes', true);
$instrucoes_raw  = get_post_meta($post_id, '_instrucoes', true);

// Avaliações (Crucial)
$rating_total = (float)get_post_meta($post_id, '_rating_total', true);
$rating_count = (int)get_post_meta($post_id, '_rating_count', true);
$display_rating_avg   = $rating_count > 0 ? round($rating_total / $rating_count, 1) : 5.0;
$display_rating_count = $rating_count > 0 ? $rating_count : 1;

// Autor
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id) ?: 'Equipe Descomplicando Receitas';
$author_url = get_author_posts_url($author_id);
$author_avatar = get_avatar_url($author_id);

// Tempos ISO
if (!function_exists('dr_format_iso_duration')) {
    function dr_format_iso_duration($minutes) {
        if (is_string($minutes)) {
            preg_match_all('!\d+!', $minutes, $matches);
            $minutes = isset($matches[0][0]) ? $matches[0][0] : 20;
        }
        $min = (int)$minutes ?: 20;
        return 'PT' . $min . 'M';
    }
}
$prep_iso  = dr_format_iso_duration($tempo_preparo);
$cook_iso  = dr_format_iso_duration($tempo_cozimento);
$total_iso = dr_format_iso_duration(sts_get_recipe_total_time($post_id));

$main_image = get_the_post_thumbnail_url($post_id, 'full') ?: get_template_directory_uri() . '/assets/images/default-image.webp';

// Montagem do Schema
$schema = [
    "@context" => "https://schema.org/",
    "@type" => "Recipe",
    "@id" => get_permalink($post_id) . "#recipe",
    "name" => get_the_title(),
    "image" => [ $main_image ],
    "author" => [
        "@type" => "Person",
        "name" => $author_name,
        "url" => $author_url,
        "image" => [
            "@type" => "ImageObject",
            "url" => $author_avatar
        ]
    ],
    "datePublished" => get_the_date('c', $post_id),
    "dateModified" => get_the_modified_date('c', $post_id),
    "description" => wp_trim_words(get_the_excerpt($post_id) ?: get_post_field('post_content', $post_id), 40),
    "prepTime" => $prep_iso,
    "cookTime" => $cook_iso,
    "totalTime" => $total_iso,
    "recipeYield" => (string)($porcoes_meta ?: "4 porções"),
    "recipeCategory" => "Receita",
    "publisher" => [
        "@type" => "Organization",
        "name" => get_bloginfo('name'),
        "logo" => [
            "@type" => "ImageObject",
            "url" => get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png'
        ]
    ],
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => (float)$display_rating_avg,
        "reviewCount" => (int)$display_rating_count,
        "bestRating" => 5,
        "worstRating" => 1
    ],
    "nutrition" => [
        "@type" => "NutritionInformation",
        "calories" => ($calorias ?: '250') . " calories",
        "carbohydrateContent" => ($carboidratos ?: '30') . "g",
        "proteinContent" => ($proteinas ?: '10') . "g",
        "fatContent" => ($gorduras ?: '15') . "g"
    ]
];

// Ingredientes
$itens_ing = [];
if (!empty($ingredientes_raw)) {
    $raw_list = is_array($ingredientes_raw) ? $ingredientes_raw : explode("\n", $ingredientes_raw);
    foreach ($raw_list as $line_block) {
        $lines = explode("\n", $line_block);
        foreach ($lines as $item) {
            if (trim($item)) $itens_ing[] = esc_html(trim($item));
        }
    }
}
$schema["recipeIngredient"] = !empty($itens_ing) ? $itens_ing : ["Ingredientes conforme instruções"];

// Instruções
$itens_inst = [];
if (!empty($instrucoes_raw)) {
    $step_count = 0;
    $steps = is_array($instrucoes_raw) ? $instrucoes_raw : explode("\n", $instrucoes_raw);
    foreach ($steps as $step_block) {
        $lines = explode("\n", $step_block);
        foreach ($lines as $step) {
            if (trim($step)) {
                $itens_inst[] = [
                    "@type" => "HowToStep",
                    "text" => esc_html(trim($step)),
                    "name" => "Passo " . ($step_count + 1)
                ];
                $step_count++;
            }
        }
    }
}
$schema["recipeInstructions"] = !empty($itens_inst) ? $itens_inst : [["@type" => "HowToStep", "text" => "Siga o passo a passo."]];

echo "\n" . '<script type="application/ld+json">' . "\n";
echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
echo "\n" . '</script>' . "\n";
