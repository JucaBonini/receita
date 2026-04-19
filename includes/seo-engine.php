<?php
/**
 * Motor de SEO Sênior (Plugin-Free 2026)
 * Gerencia Meta Tags, Open Graph, Twitter Cards e LD-JSON.
 */

function sts_render_seo_meta() {
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
    }

    $title = esc_attr($title);
    $description = esc_attr($description);
    $image = esc_url($image);
    $current_url = esc_url($current_url);

    // No Index Check
    $noindex = false;
    if (is_singular()) {
        $noindex = (get_post_meta(get_the_ID(), '_sts_seo_noindex', true) === '1');
    }

    ?>
    <!-- SEO Básico -->
    <?php if ($noindex) : ?>
    <meta name="robots" content="noindex, follow">
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
