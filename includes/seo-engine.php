<?php
/**
 * Motor de SEO Sênior (Plugin-Free 2026)
 * Gerencia Meta Tags, Open Graph, Twitter Cards e LD-JSON.
 */

function sts_render_seo_meta() {
    // 1. Verificação Crítica de Redirecionamento (Modo Aniquilação do Scout)
    if (is_singular()) {
        $forced_redirect = get_post_meta(get_the_ID(), '_sts_seo_redirect', true);
        if (!empty($forced_redirect)) {
            wp_redirect(esc_url_raw($forced_redirect), 301);
            exit;
        }
    }

    $site_name = get_bloginfo('name');
    $description = get_bloginfo('description');
    $home_url = home_url('/');
    $current_url = home_url(add_query_arg([], $GLOBALS['wp']->request));
    $image = get_template_directory_uri() . '/assets/images/og-default.jpg'; // Defina sua imagem padrão

    if (is_singular()) {
        $post = get_post();
        $title = get_the_title() . ' - ' . $site_name;
        $description = wp_trim_words(strip_shortcodes($post->post_content), 160);
        $current_url = get_permalink();
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
    } elseif (is_category()) {
        $cat = get_queried_object();
        $title = $cat->name . ' - ' . $site_name;
        $description = $cat->description ?: 'Melhores receitas de ' . $cat->name;
    } else {
        $title = $site_name . ' | ' . $description;
        // Fallback SEO para Home se o slogan estiver vazio
        if (empty($description) || $description === 'Só mais um site WordPress') {
            $description = 'Receitas Práticas, Rápidas e Deliciosas para o seu dia a dia. Aprenda a cozinhar pratos incríveis de forma descomplicada com a Chef Mary Rodrigues.';
        }
    }

    $title = esc_attr($title);
    $description = esc_attr($description);
    $image = esc_url($image);
    $current_url = esc_url($current_url);

    // Lógica Anti-Canibalização (Manual ou Web Stories -> Posts)
    $manual_canonical = is_singular() ? get_post_meta(get_the_ID(), '_sts_seo_canonical', true) : '';
    
    if (!empty($manual_canonical)) {
        $current_url = $manual_canonical;
    } elseif (is_singular('web-story')) {
        $story_slug = get_post_field('post_name', get_the_ID());
        $matching_post = get_posts([
            'name'        => $story_slug,
            'post_type'   => 'post',
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields'      => 'ids'
        ]);
        if (!empty($matching_post)) {
            $current_url = get_permalink($matching_post[0]);
        }
    }

    // No Index Check & Index Shield (AEO/SEO Fine-tuning)
    $noindex = false;
    if (is_singular()) {
        $noindex = (get_post_meta(get_the_ID(), '_sts_seo_noindex', true) === '1');
    }
    
    // Proteção contra conteúdo raso (Filtros e Busca)
    if (is_search() || is_archive() && !is_category() && !is_tag() && !is_author()) {
        $noindex = true;
    }

    ?>
    <!-- SEO Básico -->
    <?php if ($noindex) : ?>
    <meta name="robots" content="noindex, follow">
    <?php else : ?>
    <meta name="robots" content="max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <?php endif; ?>
    <meta name="description" content="<?php echo $description; ?>">
    <link rel="canonical" href="<?php echo $current_url; ?>">

    <!-- Open Graph (Facebook/WhatsApp) -->
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:url" content="<?php echo $current_url; ?>">
    <meta property="og:image" content="<?php echo $image; ?>">
    <meta property="og:image:secure_url" content="<?php echo $image; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image" content="<?php echo $image; ?>">
    <?php
}
