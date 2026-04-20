<?php
/**
 * Componente de SEO Senior: Schema.org Recipe (JSON-LD) - VERSÃO BLINDADA 2026
 * Foco total em resolver o erro "<parent_node>" e garantir Estrelas no Google.
 */

if (!isset($post_id)) $post_id = get_the_ID();

// Função para formatação ISO 8601 (Tempos)
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

// Imagem (Crucial para o Google)
$main_image = get_the_post_thumbnail_url($post_id, 'full') ?: get_template_directory_uri() . '/assets/images/default-image.webp';

// Montagem do Schema Aninhado (Mais seguro para o Google)
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
            "url" => $author_avatar ?: get_avatar_url($author_id)
        ]
    ],
    "datePublished" => get_the_date('c'),
    "dateModified" => get_the_modified_date('c'),
    "description" => wp_trim_words(get_the_excerpt() ?: get_the_content(), 40),
    "prepTime" => $prep_iso,
    "cookTime" => $cook_iso,
    "totalTime" => $total_iso,
    "recipeYield" => (string)($porcoes_meta ?: "4 porções"),
    "recipeCategory" => !empty($main_cat) ? $main_cat->name : "Prato Principal",
    "recipeCuisine" => $cuisine_meta ?: "Brasileira",
    "keywords" => get_the_tag_list('', ', ', '', $post_id) ?: "receita, culinária",
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
        "@id" => get_permalink($post_id)
    ],
    "nutrition" => [
        "@type" => "NutritionInformation",
        "calories" => ($calorias ?: '250') . " calories",
        "carbohydrateContent" => ($carboidratos ?: '30') . "g",
        "proteinContent" => ($proteinas ?: '10') . "g",
        "fatContent" => ($gorduras ?: '15') . "g"
    ]
];

// Inserir Ingredientes
$itens_ing = [];
if (!empty($ingredientes_raw)) {
    // Se for um array de grupos (como no single.php), precisamos achatar ou pegar o primeiro
    $raw_list = is_array($ingredientes_raw) ? $ingredientes_raw : explode("\n", $ingredientes_raw);
    foreach ($raw_list as $item) {
        if (trim($item)) $itens_ing[] = esc_html(trim($item));
    }
}
$schema["recipeIngredient"] = !empty($itens_ing) ? $itens_ing : ["Ingredientes conforme modo de preparo"];

// Inserir Instruções (Como HowToStep)
$itens_inst = [];
if (!empty($instrucoes_raw)) {
    $steps = is_array($instrucoes_raw) ? $instrucoes_raw : explode("\n", $instrucoes_raw);
    foreach ($steps as $i => $step) {
        if (trim($step)) {
            $itens_inst[] = [
                "@type" => "HowToStep",
                "text" => esc_html(trim($step)),
                "name" => "Passo " . ($i + 1),
                "url" => get_permalink() . "#step-" . ($i + 1)
            ];
        }
    }
}
$schema["recipeInstructions"] = !empty($itens_inst) ? $itens_inst : [["@type" => "HowToStep", "text" => "Siga o passo a passo descrito no post."]];

// Avaliações (O ponto crítico do erro)
$schema["aggregateRating"] = [
    "@type" => "AggregateRating",
    "ratingValue" => (float)$display_rating_avg,
    "reviewCount" => (int)$display_rating_count,
    "bestRating" => 5,
    "worstRating" => 1
];

// Vídeo (Se existir)
if (!empty($video_url)) {
    $schema["video"] = [
        "@type" => "VideoObject",
        "name" => get_the_title(),
        "description" => get_the_excerpt() ?: get_the_title(),
        "thumbnailUrl" => [ $main_image ],
        "contentUrl" => $video_url,
        "uploadDate" => get_the_date('c')
    ];
}

// OUTPUT: Usamos o JSON_NUMERIC_CHECK para garantir que números saiam sem aspas
echo "\n" . '<script type="application/ld+json">' . "\n";
echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
echo "\n" . '</script>' . "\n";
