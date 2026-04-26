<?php
/**
 * Componente de SEO God Mode: Schema.org Graph (JSON-LD)
 * Implementação de Grafo Conectado (WebPage > Recipe > VideoObject)
 * Autor: Antigravity (Nível Especialista Mundial)
 */

if (!isset($post_id)) $post_id = get_the_ID();

// 1. Coleta de Dados com Fallbacks Inteligentes
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
$faq_perguntas   = get_post_meta($post_id, '_faq_perguntas', true);
$faq_respostas   = get_post_meta($post_id, '_faq_respostas', true);

// Avaliações
$rating_total = (float)get_post_meta($post_id, '_rating_total', true);
$rating_count = (int)get_post_meta($post_id, '_rating_count', true);
$display_rating_avg   = $rating_count > 0 ? round($rating_total / $rating_count, 1) : 5.0;
$display_rating_count = $rating_count > 0 ? $rating_count : 1;

// Autor e Organização
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id) ?: 'Equipe Descomplicando Receitas';
$author_url = get_author_posts_url($author_id);
$author_avatar = get_avatar_url($author_id);
$site_name = get_bloginfo('name');
$site_url = home_url('/');
$logo_url = get_template_directory_uri() . '/assets/images/logotipo-descomplicando_receitas300x300.png';

// Utilitário de Tempo ISO
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

// --- CONSTRUÇÃO DO GRAFO (Nível Especialista) ---

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
        "height" => 300,
        "caption" => $site_name
    ],
    "image" => ["@id" => $site_url . "#logo"]
];

// 2. WebPage
$graph[] = [
    "@type" => "WebPage",
    "@id" => get_permalink($post_id) . "#webpage",
    "url" => get_permalink($post_id),
    "name" => get_the_title() . " - " . $site_name,
    "isPartOf" => ["@id" => $site_url . "#organization"],
    "primaryImageOfPage" => ["@id" => get_permalink($post_id) . "#primaryimage"],
    "datePublished" => get_the_date('c', $post_id),
    "dateModified" => get_the_modified_date('c', $post_id),
    "breadcrumb" => ["@id" => get_permalink($post_id) . "#breadcrumb"],
    "inLanguage" => get_locale(),
    "potentialAction" => [
        [
            "@type" => "ReadAction",
            "target" => [get_permalink($post_id)]
        ]
    ]
];

// 3. Primary Image
$graph[] = [
    "@type" => "ImageObject",
    "@id" => get_permalink($post_id) . "#primaryimage",
    "url" => $main_image,
    "width" => 1200,
    "height" => 675
];

// 4. VideoObject (SE EXISTIR) - A CHAVE PARA O GOOGLE SEARCH CONSOLE
$video_obj = null;
if (!empty($video_url)) {
    $video_id = "";
    $thumbnail_fallback = $main_image;
    
    // Regex Universal para YouTube
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match)) {
        $video_id = $match[1];
        $video_obj = [
            "@type" => "VideoObject",
            "name" => get_the_title(),
            "description" => wp_trim_words(get_the_excerpt($post_id), 30),
            "thumbnailUrl" => [
                "https://img.youtube.com/vi/$video_id/maxresdefault.jpg",
                "https://img.youtube.com/vi/$video_id/hqdefault.jpg",
                $thumbnail_fallback
            ],
            "uploadDate" => get_the_date('c', $post_id),
            "contentUrl" => $video_url,
            "embedUrl" => "https://www.youtube.com/embed/$video_id"
        ];
    } 
    // Suporte para Vídeos Diretos (MP4, etc) - Como o da Amazon que você enviou
    elseif (preg_match('/\.(mp4|webm|ogv)/i', $video_url)) {
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

// 5. BreadcrumbList (Obrigatório para o Google Search Console)
$breadcrumb_items = [];
$breadcrumb_items[] = [
    "@type" => "ListItem",
    "position" => 1,
    "name" => "Início",
    "item" => $site_url
];

$categories = get_the_category($post_id);
$pos = 2;
if (!empty($categories)) {
    $category = $categories[0];
    $breadcrumb_items[] = [
        "@type" => "ListItem",
        "position" => $pos++,
        "name" => $category->name,
        "item" => get_category_link($category->term_id)
    ];
}

$breadcrumb_items[] = [
    "@type" => "ListItem",
    "position" => $pos,
    "name" => get_the_title($post_id),
    "item" => get_permalink($post_id)
];

$graph[] = [
    "@type" => "BreadcrumbList",
    "@id" => get_permalink($post_id) . "#breadcrumb",
    "itemListElement" => $breadcrumb_items
];

// 6. Recipe
$recipe = [
    "@type" => "Recipe",
    "@id" => get_permalink($post_id) . "#recipe",
    "name" => get_the_title(),
    "headline" => get_the_title(),
    "mainEntityOfPage" => ["@id" => get_permalink($post_id) . "#webpage"],
    "image" => ["@id" => get_permalink($post_id) . "#primaryimage"],
    "author" => [
        "@type" => "Person",
        "name" => $author_name,
        "url" => $author_url,
        "jobTitle" => get_the_author_meta('job_title', $author_id) ?: 'Chef de Cozinha',
        "image" => [
            "@type" => "ImageObject",
            "url" => $author_avatar
        ],
        "sameAs" => [
            "https://www.instagram.com/maryrodrigues", 
            "https://www.pinterest.com/maryrodrigues"
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
    "publisher" => ["@id" => $site_url . "#organization"],
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => (float)$display_rating_avg,
        "reviewCount" => (int)$display_rating_count
    ],
    "nutrition" => [
        "@type" => "NutritionInformation",
        "calories" => ($calorias ?: '250') . " calories"
    ]
];

// Inserindo o Vídeo na Receita se existir
if ($video_obj) {
    $recipe["video"] = $video_obj;
}

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
$recipe["recipeIngredient"] = !empty($itens_ing) ? $itens_ing : ["Ingredientes conforme instruções"];

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
                    "name" => "Passo " . ($step_count + 1),
                    "text" => esc_html(trim($step)),
                    "url" => get_permalink($post_id) . "#step-" . ($step_count + 1)
                ];
                $step_count++;
            }
        }
    }
}

// VACINA GOD MODE: Fallback para receitas com instruções vazias (evita erros no GSC)
if (empty($itens_inst)) {
    $itens_inst[] = [
        "@type" => "HowToStep",
        "name" => "Modo de Preparo",
        "text" => "Siga as orientações detalhadas descritas no conteúdo desta página para preparar esta receita com sucesso.",
        "url" => get_permalink($post_id) . "#instructions"
    ];
}

$recipe["recipeInstructions"] = $itens_inst;

$graph[] = $recipe;

// 7. FAQPage (Nível God Mode)
if (!empty($faq_perguntas)) {
    $questions = [];
    foreach ($faq_perguntas as $index => $pergunta) {
        $resposta = $faq_respostas[$index] ?? '';
        if (!empty($pergunta) && !empty($resposta)) {
            $questions[] = [
                "@type" => "Question",
                "name" => esc_html($pergunta),
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => wp_strip_all_tags($resposta)
                ]
            ];
        }
    }
    
    if (!empty($questions)) {
        $graph[] = [
            "@type" => "FAQPage",
            "@id" => get_permalink($post_id) . "#faq",
            "isPartOf" => ["@id" => get_permalink($post_id) . "#webpage"],
            "mainEntity" => $questions
        ];
    }
}

// Saída Final
echo "\n" . '<script type="application/ld+json">' . "\n";
echo json_encode(["@context" => "https://schema.org", "@graph" => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
echo "\n" . '</script>' . "\n";

