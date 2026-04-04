<?php
/**
 * The template for displaying default single posts (Tailwind Modern 2026)
 * Template para: Artigos, Achadinhos, Reviews, FAQs, Glossário
 * INCLUDES: Dynamic Schema JSON-LD for SEO Authority
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $post_type = get_post_type();
    
    // Configurações específicas por CPT
    $is_achadinho = $post_type === 'achadinhos';
    $is_review = $post_type === 'reviews';
    $is_faq = $post_type === 'faqs';
    $is_glossario = $post_type === 'glossario';
    
    // Meta informações
    $tempo_leitura = get_field('tempo_leitura') ?: '5';
    $autor_id = get_the_author_meta('ID');
    $autor_nome = get_the_author();
    $autor_job = get_the_author_meta('job_title', $autor_id) ?: 'Especialista em Culinária';
    $autor_avatar = get_avatar_url($autor_id, ['size' => 120]);
    $date_published = get_the_date('c');
    $date_modified = get_the_modified_date('c');

    // Breadcrumbs Logic
    $cat_name = 'Blog';
    $cat_link = home_url('/blog');
    if ($is_achadinho) { $cat_name = 'Achadinhos'; $cat_link = get_post_type_archive_link('achadinhos'); }
    elseif ($is_review) { $cat_name = 'Reviews'; $cat_link = get_post_type_archive_link('reviews'); }
    elseif ($is_faq) { $cat_name = 'FAQ'; $cat_link = get_post_type_archive_link('faqs'); }
    elseif ($is_glossario) { $cat_name = 'Glossário'; $cat_link = get_post_type_archive_link('glossario'); }
    else {
        $cats = get_the_category();
        if(!empty($cats)) { $cat_name = $cats[0]->name; $cat_link = get_category_link($cats[0]->term_id); }
    }
?>

<!-- 🟢 DYNAMIC SEO SCHEMA JSON-LD -->
<script type="application/ld+json">
<?php
$schema = array(
    "@context" => "https://schema.org",
    "author" => array(
        "@type" => "Person",
        "name" => $autor_nome,
        "jobTitle" => $autor_job,
        "image" => $autor_avatar
    ),
    "headline" => get_the_title(),
    "image" => get_the_post_thumbnail_url($post_id, 'full') ?: (get_template_directory_uri() . '/assets/images/default-image.webp'),
    "datePublished" => $date_published,
    "dateModified" => $date_modified,
    "publisher" => array(
        "@type" => "Organization",
        "name" => get_bloginfo('name'),
        "logo" => array(
            "@type" => "ImageObject",
            "url" => get_template_directory_uri() . '/assets/images/logo.png' 
        )
    )
);

// Adaptive Schema Logic
if ($is_glossario) {
    $schema["@type"] = "DefinedTerm";
    $schema["description"] = get_the_excerpt() ?: 'Definição culinária detalhada no Glossário Descomplicando Receitas.';
    $schema["inDefinedTermSet"] = get_post_type_archive_link('glossario');
} elseif ($is_faq) {
    $schema["@type"] = "FAQPage";
    $schema["mainEntity"] = array(
        array(
            "@type" => "Question",
            "name" => get_the_title(),
            "acceptedAnswer" => array(
                "@type" => "Answer",
                "text" => wp_strip_all_tags(get_the_content())
            )
        )
    );
} elseif ($is_achadinho || $is_review) {
    $schema["@type"] = "Review";
    $schema["reviewBody"] = get_the_excerpt() ?: get_the_title();
    $schema["itemReviewed"] = array(
        "@type" => "Product",
        "name" => get_the_title(),
        "image" => get_the_post_thumbnail_url($post_id, 'large')
    );
} else {
    $schema["@type"] = "BlogPosting";
    $schema["articleBody"] = wp_strip_all_tags(get_the_content());
}

echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
</script>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" role="main">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Article Content Column -->
        <article class="lg:col-span-8">
            
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex text-sm text-slate-500 mb-6">
                <ol class="flex items-center space-x-2">
                    <li><a class="hover:text-primary transition-colors font-bold" href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="flex items-center space-x-2">
                        <span class="material-symbols-outlined text-xs">chevron_right</span>
                        <a class="hover:text-primary transition-colors font-bold" href="<?php echo esc_url($cat_link); ?>"><?php echo esc_html($cat_name); ?></a>
                    </li>
                </ol>
            </nav>

            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-8 text-slate-900 dark:text-white">
                <?php the_title(); ?>
            </h1>

            <!-- Author & Meta -->
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-slate-200 dark:border-slate-800">
                <div class="size-14 rounded-full overflow-hidden ring-2 ring-primary/20 bg-slate-200">
                    <img src="<?php echo esc_url($autor_avatar); ?>" alt="<?php echo esc_attr($autor_nome); ?>" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-lg text-slate-900 dark:text-white">Por <?php echo $autor_nome; ?></p>
                    <p class="text-sm text-slate-500">
                        <?php echo $autor_job; ?> • <?php echo get_the_date('d \d\e F, Y'); ?> • <?php echo $tempo_leitura; ?> min de leitura
                    </p>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="relative aspect-video rounded-[35px] overflow-hidden mb-12 shadow-2xl group bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-800">
                <?php 
                if (has_post_thumbnail()) {
                    the_post_thumbnail('full', [
                        'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105',
                        'alt' => get_the_title()
                    ]); 
                } else {
                    $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                    echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="eager" decoding="async">';
                }
                ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
            </div>

            <!-- Article Body -->
            <div class="prose sm:prose-xl dark:prose-invert max-w-none text-lg leading-relaxed space-y-8 prose-headings:text-primary prose-a:text-primary mb-12 selection:bg-primary/20">
                <?php if (has_excerpt()) : ?>
                    <p class="text-2xl text-slate-600 dark:text-slate-400 font-medium italic border-l-4 border-primary pl-6 py-4 bg-primary/5 rounded-r-3xl">
                        <?php echo get_the_excerpt(); ?>
                    </p>
                <?php endif; ?>
                
                <?php the_content(); ?>
            </div>

            <!-- Social Sharing -->
            <div class="mt-16 pt-8 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm font-bold uppercase tracking-widest text-slate-500 mb-6">Gostou da dica? Compartilhe:</p>
                <div class="flex flex-wrap gap-4">
                    <?php 
                    $share_url = urlencode(get_permalink());
                    $share_title = urlencode(get_the_title());
                    ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-[#1877F2] text-white rounded-2xl hover:bg-[#1877F2]/90 transition-all font-bold text-sm shadow-lg shadow-[#1877F2]/20">
                        <span class="material-symbols-outlined text-xl">share</span>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-[#1DA1F2] text-white rounded-2xl hover:bg-[#1DA1F2]/90 transition-all font-bold text-sm shadow-lg shadow-[#1DA1F2]/20">
                        <span class="material-symbols-outlined text-xl">post_add</span>
                        Twitter
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-[#25D366] text-white rounded-2xl hover:bg-[#25D366]/90 transition-all font-bold text-sm shadow-lg shadow-[#25D366]/20">
                        <span class="material-symbols-outlined text-xl">chat</span>
                        WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText('<?php the_permalink(); ?>'); alert('Link copiado!');" class="flex items-center gap-2 px-6 py-3 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-2xl hover:bg-slate-300 dark:hover:bg-slate-700 transition-all font-bold text-sm">
                        <span class="material-symbols-outlined text-xl">link</span>
                        Copiar Link
                    </button>
                </div>
            </div>

            <!-- Comments Section -->
            <section class="mt-20">
                <h3 class="text-2xl font-black mb-10 pb-4 border-b border-slate-100 dark:border-slate-800">
                    Comentários (<?php echo get_comments_number(); ?>)
                </h3>
                <?php comments_template(); ?>
            </section>

        </article>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-12">
            
            <!-- WhatsApp Channel Card -->
            <div class="bg-[#25D366]/5 dark:bg-[#25D366]/10 p-8 rounded-[40px] border border-[#25D366]/20 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 size-32 bg-[#25D366]/10 rounded-full blur-2xl"></div>
                <h4 class="text-2xl font-black mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#25D366]">chat</span>
                    Canal do WhatsApp
                </h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">Entre no nosso canal e receba truques de culinária e receitas exclusivas diretamente no seu celular.</p>
                <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H?utm_source=blog&utm_medium=sidebar_post&utm_campaign=whatsapp_channel" target="_blank" class="w-full py-4 bg-[#25D366] text-white font-black rounded-2xl hover:bg-[#128C7E] transition-all shadow-xl shadow-[#25D366]/20 transform active:scale-95 flex items-center justify-center gap-2">
                    ENTRAR NO CANAL
                </a>
            </div>

            <!-- Recommended Articles -->
            <div>
                <h4 class="text-xl font-black mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">auto_awesome</span>
                    Recomendados
                </h4>
                <div class="space-y-8">
                    <?php
                    $related_args = array(
                        'post_type' => $post_type,
                        'posts_per_page' => 3,
                        'post__not_in' => array($post_id),
                        'orderby' => 'rand'
                    );
                    $related_posts = new WP_Query($related_args);
                    if ($related_posts->have_posts()) : while ($related_posts->have_posts()) : $related_posts->the_post();
                    ?>
                    <a class="group flex gap-5 items-center" href="<?php the_permalink(); ?>">
                        <div class="size-24 rounded-2xl overflow-hidden shrink-0 shadow-lg group-hover:shadow-primary/20 transition-all bg-slate-100 dark:bg-slate-700">
                            <?php 
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500', 'alt' => get_the_title()]); 
                            } else {
                                $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                                echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" decoding="async">';
                            }
                            ?>
                        </div>
                        <div class="flex flex-col">
                            <h5 class="font-bold text-sm leading-snug group-hover:text-primary transition-colors line-clamp-2">
                                <?php the_title(); ?>
                            </h5>
                            <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-widest"><?php echo get_the_date(); ?></p>
                        </div>
                    </a>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>

            <!-- Popular Categories -->
            <div class="pt-6">
                <h4 class="text-xl font-black mb-6">Temas Populares</h4>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $categories = get_categories(array('number' => 8, 'orderby' => 'count', 'order' => 'DESC'));
                    foreach($categories as $category) {
                        echo '<a class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-primary/10 hover:text-primary border border-slate-100 dark:border-slate-700 rounded-xl text-sm font-bold transition-all" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                    }
                    ?>
                </div>
            </div>

            <!-- Sticky Ad Placeholder -->
            <div class="sticky top-24 pt-8">
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-8 text-center">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 block">Publicidade</span>
                    <div class="h-60 w-full flex items-center justify-center text-slate-300">
                        <span class="material-symbols-outlined text-4xl">ads_click</span>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>