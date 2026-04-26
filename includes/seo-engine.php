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

        // Meta description: prioriza excerpt (campo SEO), fallback em 150 chars do conteúdo
        $raw_excerpt = get_the_excerpt();
        if (!empty($raw_excerpt)) {
            // Excerpt já é curto; garante o limite de 155 chars
            $description = mb_strimwidth(strip_tags($raw_excerpt), 0, 155, '...');
        } else {
            // Fallback: primeiros 150 chars do conteúdo limpo (sem shortcodes e tags)
            $clean_content = strip_tags(strip_shortcodes($post->post_content));
            $clean_content = preg_replace('/\s+/', ' ', $clean_content); // normaliza espaços
            $description   = mb_strimwidth(trim($clean_content), 0, 150, '...');
        }

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
    <?php
    // og:title: versão social (mais descritiva que a SERP)
    $og_title = is_singular() ? get_the_title() . ' 🍽️ ' . $site_name : $title;
    $og_title = esc_attr($og_title);
    ?>
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
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

/**
 * STS SEO Alert — Contador de Meta Description no Editor (God Mode)
 * Exibe contador em tempo real no campo Excerpt + aviso admin se exceder 155 chars.
 */
function sts_seo_meta_description_alert() {
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->base, ['post', 'page'])) return;
    ?>
    <style>
        #sts-excerpt-counter {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 12px;
            font-family: monospace;
            font-weight: 700;
            transition: color 0.3s;
        }
        #sts-excerpt-counter .sts-count-bar {
            flex: 1;
            height: 4px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }
        #sts-excerpt-counter .sts-count-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.2s, background 0.2s;
        }
        #sts-excerpt-tip {
            font-size: 11px;
            margin-top: 4px;
            padding: 6px 10px;
            border-radius: 6px;
            display: none;
            font-weight: 600;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var excerptBox = document.getElementById('excerpt');
        if (!excerptBox) return;

        // Injeta o contador abaixo do textarea
        var wrapper = document.createElement('div');
        wrapper.id = 'sts-excerpt-counter';
        wrapper.innerHTML =
            '<span id="sts-char-count">0</span>' +
            '<span style="color:#94a3b8">/</span>' +
            '<span style="color:#64748b">155 chars</span>' +
            '<div class="sts-count-bar"><div class="sts-count-fill" id="sts-fill"></div></div>' +
            '<span id="sts-status-icon">✅</span>';
        excerptBox.parentNode.insertBefore(wrapper, excerptBox.nextSibling);

        var tip = document.createElement('div');
        tip.id = 'sts-excerpt-tip';
        wrapper.parentNode.insertBefore(tip, wrapper.nextSibling);

        function update() {
            var len = excerptBox.value.length;
            var pct = Math.min((len / 155) * 100, 100);
            var fill = document.getElementById('sts-fill');
            var count = document.getElementById('sts-char-count');
            var icon  = document.getElementById('sts-status-icon');

            count.textContent = len;

            if (len === 0) {
                fill.style.width = '0%';
                fill.style.background = '#e2e8f0';
                icon.textContent = '⚠️';
                count.style.color = '#f59e0b';
                tip.style.display = 'block';
                tip.style.background = '#fef3c7';
                tip.style.color = '#92400e';
                tip.textContent = '⚠️ Excerpt vazio — o Google vai gerar o snippet automaticamente (sem keyword nem CTA).';
            } else if (len <= 130) {
                fill.style.width = pct + '%';
                fill.style.background = '#22c55e';
                icon.textContent = '✅';
                count.style.color = '#16a34a';
                tip.style.display = 'none';
            } else if (len <= 155) {
                fill.style.width = pct + '%';
                fill.style.background = '#f59e0b';
                icon.textContent = '🟡';
                count.style.color = '#b45309';
                tip.style.display = 'block';
                tip.style.background = '#fffbeb';
                tip.style.color = '#78350f';
                tip.textContent = '🟡 Quase no limite. Ideal: até 130 chars para não cortar em mobile.';
            } else {
                fill.style.width = '100%';
                fill.style.background = '#ef4444';
                icon.textContent = '🔴';
                count.style.color = '#dc2626';
                tip.style.display = 'block';
                tip.style.background = '#fee2e2';
                tip.style.color = '#991b1b';
                tip.textContent = '🔴 ' + len + ' chars — passou de 155! O Google vai IGNORAR este texto e criar o próprio snippet.';
            }
        }

        excerptBox.addEventListener('input', update);
        update(); // roda ao carregar
    });
    </script>
    <?php
}
add_action('admin_footer-post.php', 'sts_seo_meta_description_alert');
add_action('admin_footer-post-new.php', 'sts_seo_meta_description_alert');

/**
 * Admin notice: avisa sobre posts sem excerpt ou com excerpt longo no momento do save.
 */
function sts_seo_excerpt_admin_notice() {
    global $post;
    if (!isset($post) || !in_array($post->post_type, ['post', 'page'])) return;

    $excerpt = $post->post_excerpt;
    $len     = mb_strlen(strip_tags($excerpt));

    if (empty($excerpt)) {
        echo '<div class="notice notice-warning is-dismissible"><p>
            <strong>⚠️ STS SEO:</strong> Este post não tem <strong>Excerpt (resumo)</strong>.
            O Google vai gerar o snippet do jeito que quiser — sem sua keyword nem CTA.
            <a href="#postexcerpt"><strong>→ Adicionar agora</strong></a>
        </p></div>';
    } elseif ($len > 155) {
        echo '<div class="notice notice-error is-dismissible"><p>
            <strong>🔴 STS SEO:</strong> O Excerpt tem <strong>' . $len . ' caracteres</strong> — passa do limite de 155.
            O Google vai ignorar e criar o próprio snippet.
            <a href="#postexcerpt"><strong>→ Editar agora</strong></a>
        </p></div>';
    }
}
add_action('admin_notices', 'sts_seo_excerpt_admin_notice');
