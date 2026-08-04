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

    if (is_front_page() || is_home()) {
        $tagline = get_bloginfo('description');
        $title = $site_name . ' | ' . ($tagline ?: 'Receitas práticas, testadas e aprovadas.');
        $description = $tagline;
        if (empty($description) || $description === 'Só mais um site WordPress' || trim($description) === '') {
            $description = 'Receitas Práticas, Rápidas e Deliciosas para o seu dia a dia. Aprenda a cozinhar pratos incríveis de forma descomplicada com a Chef Mary Rodrigues.';
        }
        $current_url = home_url('/');
    } elseif (is_singular()) {
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
    <meta property="og:locale" content="pt_BR">
    <?php if (is_singular()) : ?>
    <meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
    <meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">
    <meta property="article:author" content="<?php echo esc_attr(get_the_author()); ?>">
    <?php endif; ?>

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image" content="<?php echo $image; ?>">
    <meta name="twitter:site" content="@descomplicandoreceitas">

    <!-- Verificação -->
    <meta name="p:domain_verify" content="645852f757b84ed974209acf2794c0cd">
    <meta name="msvalidate.01" content="E3BEF536136496E86D4C035E2C36E401">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon.ico'); ?>" type="image/x-icon">
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

/**
 * ------------------------------------------------------------------
 * Componentes de SEO Migrados do Plugin (Plugin-Free 2026)
 * ------------------------------------------------------------------
 */

class STS_SEO_ImageSEO {
    public function __construct() {
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_image_attributes'], 10, 2);
    }

    public function add_image_attributes($attr, $attachment) {
        if (empty($attr['alt'])) {
            $post = get_post($attachment->post_parent);
            if ($post) {
                $attr['alt'] = $post->post_title;
            } else {
                $attr['alt'] = $attachment->post_title;
            }
        }

        if (empty($attr['title'])) {
            $attr['title'] = $attr['alt'];
        }

        return $attr;
    }
}
new STS_SEO_ImageSEO();

class STS_SEO_MetaBox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_seo_meta_box']);
        add_action('save_post', [$this, 'save_seo_data']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_seo_meta_box()
    {
        $screens = ['post', 'page', 'artigo', 'cardapio'];
        foreach ($screens as $screen) {
            add_meta_box(
                'sts_seo_meta_box',
                'SEO Engine Pro - Snippet Editor',
                [$this, 'render_meta_box'],
                $screen,
                'normal',
                'high'
            );
        }
    }

    public function render_meta_box($post)
    {
        $seo_title = get_post_meta($post->ID, '_sts_seo_title', true);
        $seo_desc = get_post_meta($post->ID, '_sts_seo_desc', true);
        $focus_kw = get_post_meta($post->ID, '_sts_focus_keyword', true);
        
        wp_nonce_field('sts_seo_save_action', 'sts_seo_nonce');
        ?>
        <div class="sts-seo-editor">
            <div style="display: flex; gap: 20px;">
                <!-- Left: Editor -->
                <div style="flex: 2;">
                    <!-- Snippet Preview -->
                    <div class="sts-seo-preview">
                        <div class="preview-url"><?php echo home_url('/'); ?>...</div>
                        <div class="preview-title" id="sts-preview-title"><?php echo $seo_title ?: get_the_title($post->ID); ?></div>
                        <div class="preview-desc" id="sts-preview-desc"><?php echo $seo_desc ?: 'Insira uma descrição meta para ver como este post aparecerá nos resultados de pesquisa.'; ?></div>
                    </div>

                    <div class="sts-seo-fields">
                        <div class="field-group">
                            <label>Palavra-chave Foco</label>
                            <input type="text" id="sts_focus_keyword_input" name="sts_focus_keyword" value="<?php echo esc_attr($focus_kw); ?>" placeholder="Ex: Bolo de Chocolate">
                        </div>

                        <div class="field-group">
                            <label>Título SEO</label>
                            <input type="text" id="sts_seo_title_input" name="sts_seo_title" value="<?php echo esc_attr($seo_title); ?>" placeholder="Título customizado para o Google">
                            <div class="char-count"><span id="title-count">0</span> / 60</div>
                        </div>

                        <div class="field-group">
                            <label>Meta Descrição</label>
                            <textarea id="sts_seo_desc_input" name="sts_seo_desc" rows="3" placeholder="Resumo para o Google (ideal: 120-155 chars com keyword + CTA)..."><?php echo esc_textarea($seo_desc); ?></textarea>
                            <div class="char-count" style="display:flex;align-items:center;gap:8px;margin-top:5px;">
                                <span id="desc-count" style="font-weight:700;">0</span>
                                <span style="color:#999">/</span>
                                <span style="color:#646970">155 chars</span>
                                <div style="flex:1;height:4px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                    <div id="desc-fill" style="height:100%;width:0%;border-radius:4px;transition:width .2s,background .2s;"></div>
                                </div>
                                <span id="desc-icon">⚪</span>
                            </div>
                            <div id="desc-tip" style="display:none;font-size:11px;margin-top:4px;padding:5px 10px;border-radius:5px;font-weight:600;"></div>
                        </div>

                        <div class="field-group" style="margin-top: 10px; padding: 10px; background: #fff5f5; border-radius: 6px; border: 1px solid #fed7d7;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #c53030;">
                                <input type="checkbox" name="sts_seo_noindex" value="1" <?php checked(get_post_meta($post->ID, '_sts_seo_noindex', true), '1'); ?>>
                                <b>Remover do Google (No Index)</b>
                            </label>
                            <p style="margin: 5px 0 0 24px; font-size: 10px; color: #9b2c2c;">Marque esta opção para que esta página NÃO apareça nos resultados de busca.</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Analysis -->
                <div style="flex: 1; min-width: 250px;">
                    <div class="sts-seo-analysis">
                        <h3>Análise SEO Pro</h3>
                        <ul id="sts-analysis-list">
                            <li id="check-kw-title">Palavra-chave no título</li>
                            <li id="check-title-len">Tamanho do título</li>
                            <li id="check-desc-len">Tamanho da descrição</li>
                            <li id="check-kw-content">Palavra-chave no conteúdo</li>
                            <li id="check-url">URL Amigável</li>
                        </ul>
                        <div class="seo-score">
                            <span>Score SEO</span>
                            <div class="score-bar"><div id="score-fill" style="width: 0%;"></div></div>
                            <b id="score-text">0/100</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .sts-seo-editor { padding: 10px; }
            .sts-seo-preview { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
            .preview-url { color: #202124; font-size: 14px; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .preview-title { color: #1a0dab; font-size: 20px; line-height: 1.3; margin-bottom: 3px; }
            .preview-desc { color: #4d5156; font-size: 14px; line-height: 1.58; }
            
            .sts-seo-fields { display: flex; flex-direction: column; gap: 15px; }
            .field-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px; color: #50575e; }
            .field-group input, .field-group textarea { width: 100%; padding: 8px; border: 1px solid #ccd0d4; border-radius: 4px; }
            .char-count { font-size: 10px; color: #646970; text-align: right; margin-top: 2px; }

            .sts-seo-analysis { background: #f6f7f7; padding: 15px; border-radius: 8px; border: 1px solid #ccd0d4; }
            .sts-seo-analysis h3 { margin: 0 0 15px 0; font-size: 14px; }
            .sts-seo-analysis ul { list-style: none; padding: 0; margin: 0; }
            .sts-seo-analysis li { font-size: 12px; padding-left: 20px; margin-bottom: 8px; position: relative; color: #50575e; }
            .sts-seo-analysis li::before { content: '○'; position: absolute; left: 0; color: #ccd0d4; font-weight: bold; }
            .sts-seo-analysis li.pass::before { content: '✓'; color: #00a32a; }
            .sts-seo-analysis li.fail::before { content: '✕'; color: #d63638; }
            .sts-seo-analysis li.warn::before { content: '⚠'; color: #dba617; }
            
            .seo-score { margin-top: 20px; padding-top: 15px; border-top: 1px solid #ccd0d4; }
            .seo-score span { font-size: 11px; display: block; margin-bottom: 5px; font-weight: 600; }
            .score-bar { background: #ccd0d4; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 5px; }
            #score-fill { height: 100%; background: #00a32a; transition: width 0.3s ease; }
            #score-text { font-size: 16px; color: #1d2327; }
        </style>

        <script>
            (function() {
                function updateSEOEngine() {
                    const container = document.querySelector('.sts-seo-editor');
                    if (!container) return;

                    const titleIn = container.querySelector('#sts_seo_title_input');
                    const descIn = container.querySelector('#sts_seo_desc_input');
                    const kwIn = container.querySelector('#sts_focus_keyword_input');
                    
                    if (!titleIn || !descIn || !kwIn) return;

                    const title = titleIn.value.trim() || "<?php echo esc_js(get_the_title($post->ID)); ?>";
                    const desc = descIn.value.trim();
                    const kw = kwIn.value.trim().toLowerCase();
                    let score = 0;

                    const tLen = title.length;
                    let titleStatus = 'fail';
                    if (tLen >= 30 && tLen <= 65) { titleStatus = 'pass'; score += 20; }
                    else if (tLen > 0) { titleStatus = 'warn'; score += 5; }
                    
                    const elTitleCheck = container.querySelector('#check-title-len');
                    if (elTitleCheck) elTitleCheck.className = titleStatus;
                    
                    const elTitleCnt = container.querySelector('#title-count');
                    if (elTitleCnt) elTitleCnt.textContent = tLen;

                    const dLen = desc.length;
                    let descStatus = 'fail';
                    if (dLen >= 120 && dLen <= 155) { descStatus = 'pass'; score += 20; }
                    else if (dLen > 0 && dLen < 120) { descStatus = 'warn'; score += 10; }
                    else if (dLen > 155) { descStatus = 'fail'; }

                    const elDescCheck = container.querySelector('#check-desc-len');
                    if (elDescCheck) elDescCheck.className = descStatus;

                    const elDescCnt = container.querySelector('#desc-count');
                    if (elDescCnt) {
                        elDescCnt.textContent = dLen;
                        const fill = container.querySelector('#desc-fill');
                        const icon = container.querySelector('#desc-icon');
                        const tip  = container.querySelector('#desc-tip');
                        const pct  = Math.min((dLen / 155) * 100, 100);
                        if (fill) fill.style.width = pct + '%';

                        if (dLen === 0) {
                            if (fill) fill.style.background = '#e2e8f0';
                            if (icon) icon.textContent = '⚠️';
                            elDescCnt.style.color = '#f59e0b';
                            if (tip) { tip.style.display='block'; tip.style.background='#fef3c7'; tip.style.color='#92400e'; tip.textContent='⚠️ Campo vazio — o Google vai criar o snippet sozinho, sem keyword nem CTA.'; }
                        } else if (dLen <= 120) {
                            if (fill) fill.style.background = '#f59e0b';
                            if (icon) icon.textContent = '🟡';
                            elDescCnt.style.color = '#b45309';
                            if (tip) { tip.style.display='block'; tip.style.background='#fffbeb'; tip.style.color='#78350f'; tip.textContent='🟡 Curto demais. Ideal: 120–155 chars com keyword e CTA.'; }
                        } else if (dLen <= 155) {
                            if (fill) fill.style.background = '#22c55e';
                            if (icon) icon.textContent = '✅';
                            elDescCnt.style.color = '#16a34a';
                            if (tip) tip.style.display = 'none';
                        } else {
                            if (fill) fill.style.background = '#ef4444';
                            if (icon) icon.textContent = '🔴';
                            elDescCnt.style.color = '#dc2626';
                            if (tip) { tip.style.display='block'; tip.style.background='#fee2e2'; tip.style.color='#991b1b'; tip.textContent='🔴 ' + dLen + ' chars — passou de 155! O Google vai IGNORAR esta descrição.'; }
                        }
                    }

                    const passKWTitle = kw && title.toLowerCase().includes(kw);
                    const elKWTitleCheck = container.querySelector('#check-kw-title');
                    if (elKWTitleCheck) elKWTitleCheck.className = passKWTitle ? 'pass' : 'fail';
                    if (passKWTitle) score += 20;

                    let content = '';
                    try {
                        if (window.wp && wp.data && wp.data.select) {
                            const editor = wp.data.select('core/editor');
                            if (editor) content = (editor.getEditedPostContent() || '').toLowerCase();
                        } else {
                            const contentEl = document.getElementById('content');
                            if (contentEl) content = contentEl.value.toLowerCase();
                        }
                    } catch(e) {}
                    
                    const passKWContent = kw && content.includes(kw);
                    const elKWContentCheck = container.querySelector('#check-kw-content');
                    if (elKWContentCheck) elKWContentCheck.className = passKWContent ? 'pass' : 'fail';
                    if (passKWContent) score += 20;

                    let permalink = '';
                    try {
                        if (window.wp && wp.data && wp.data.select) {
                            permalink = wp.data.select('core/editor').getPermalink() || '';
                        }
                    } catch(e) {}

                    let urlStatus = 'pass';
                    if (permalink) {
                        const isQuery = permalink.includes('?p=') || permalink.includes('&');
                        const hasDate = /\/\d{4}\/\d{2}\/\d{2}\//.test(permalink);
                        if (isQuery || hasDate) urlStatus = 'fail';
                    } else {
                        urlStatus = 'warn';
                    }

                    const elURLCheck = container.querySelector('#check-url');
                    if (elURLCheck) elURLCheck.className = urlStatus;
                    if (urlStatus === 'pass') score += 20;

                    const elPrevTitle = container.querySelector('#sts-preview-title');
                    const elPrevDesc = container.querySelector('#sts-preview-desc');
                    const elPrevUrl = container.querySelector('.preview-url');
                    if (elPrevTitle) elPrevTitle.textContent = title;
                    if (elPrevDesc) elPrevDesc.textContent = desc || 'Insira uma descrição meta...';
                    if (elPrevUrl && permalink) elPrevUrl.textContent = permalink;

                    const scoreFill = container.querySelector('#score-fill');
                    const scoreText = container.querySelector('#score-text');

                    if (scoreFill && scoreText) {
                        scoreFill.style.width = score + '%';
                        scoreText.textContent = score + '/100';
                        
                        let color = '#d63638';
                        if (score >= 80) color = '#00a32a';
                        else if (score >= 40) color = '#dba617';
                        scoreFill.style.setProperty('background', color, 'important');
                    }
                }

                document.addEventListener('input', function(e) {
                    if (e.target.id && e.target.id.startsWith('sts_')) {
                        updateSEOEngine();
                    }
                });

                if (window.wp && wp.data) {
                    wp.data.subscribe(updateSEOEngine);
                }

                setTimeout(updateSEOEngine, 1000);
            })();
        </script>
        <?php
    }

    public function save_seo_data($post_id)
    {
        if (!isset($_POST['sts_seo_nonce']) || !wp_verify_nonce($_POST['sts_seo_nonce'], 'sts_seo_save_action')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        if (isset($_POST['sts_seo_title'])) {
            update_post_meta($post_id, '_sts_seo_title', sanitize_text_field($_POST['sts_seo_title']));
        }
        if (isset($_POST['sts_seo_desc'])) {
            update_post_meta($post_id, '_sts_seo_desc', sanitize_textarea_field($_POST['sts_seo_desc']));
        }
        if (isset($_POST['sts_focus_keyword'])) {
            update_post_meta($post_id, '_sts_focus_keyword', sanitize_text_field($_POST['sts_focus_keyword']));
        }
        
        $noindex = isset($_POST['sts_seo_noindex']) ? '1' : '0';
        update_post_meta($post_id, '_sts_seo_noindex', $noindex);
    }

    public function enqueue_assets($hook)
    {
        if (!in_array($hook, ['post.php', 'post-new.php'])) return;
        
        wp_enqueue_script('wp-data');
        wp_enqueue_script('wp-editor');
    }
}
new STS_SEO_MetaBox();

