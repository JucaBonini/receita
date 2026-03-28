<?php
/**
 * The template for displaying author profile pages (Tailwind Version 2026)
 */
get_header();

$author_id = get_queried_object_id();
$author = get_userdata($author_id);
$author_name = $author->display_name;
$author_desc = $author->description;
$author_avatar = get_avatar_url($author_id, ['size' => 200]);
$reg_date = $author->user_registered;
$exp_years = date('Y') - date('Y', strtotime($reg_date));

// E-E-A-T Fields
$job_title = get_the_author_meta('job_title', $author_id) ?: 'Especialista em Culinária';
$expertise = get_the_author_meta('expertise', $author_id);
$education = get_the_author_meta('education', $author_id);
$certifications = get_the_author_meta('certifications', $author_id);
$facebook = get_the_author_meta('facebook', $author_id);
$instagram = get_the_author_meta('instagram', $author_id);
$recipe_count = count_user_posts($author_id);

// JSON-LD Schema Person
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "<?php echo esc_js($author_name); ?>",
  "jobTitle": "<?php echo esc_js($job_title); ?>",
  "description": "<?php echo esc_js(wp_strip_all_tags($author_desc)); ?>",
  "image": "<?php echo esc_url($author_avatar); ?>",
  "url": "<?php echo esc_url(get_author_posts_url($author_id)); ?>",
  "worksFor": {
    "@type": "Organization",
    "name": "<?php bloginfo('name'); ?>"
  }
}
</script>

<main class="author-profile bg-background-light dark:bg-background-dark min-h-screen">
    
    <!-- Hero Profile -->
    <section class="max-w-7xl mx-auto px-4 pt-12">
        <div class="bg-white dark:bg-slate-800 rounded-[40px] p-8 md:p-12 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 relative overflow-hidden">
            <!-- Decorative Gradient -->
            <div class="absolute -top-24 -right-24 size-64 bg-primary/20 rounded-full blur-3xl opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-10">
                <!-- Avatar -->
                <div class="size-40 md:size-52 rounded-full border-4 border-primary overflow-hidden shadow-xl flex-shrink-0">
                    <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="w-full h-full object-cover">
                </div>
                
                <!-- Info -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-4">
                        <span class="bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-primary/20">Autor Verificado</span>
                        <div class="flex gap-4">
                            <?php if($instagram) echo '<a href="'.esc_url($instagram).'" class="text-slate-400 hover:text-primary transition-all"><span class="material-symbols-outlined text-xl">camera</span></a>'; ?>
                            <?php if($facebook) echo '<a href="'.esc_url($facebook).'" class="text-slate-400 hover:text-primary transition-all"><span class="material-symbols-outlined text-xl">public</span></a>'; ?>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-slate-100 mb-4 leading-tight"><?php echo $author_name; ?></h1>
                    <p class="text-xl text-primary font-bold mb-6"><?php echo $job_title; ?></p>
                    <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed mb-8">
                        <?php echo $author_desc ?: 'Especialista apaixonado por compartilhar receitas práticas e deliciosas que transformam o dia a dia na cozinha.'; ?>
                    </p>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-2xl mx-auto md:mx-0 pt-8 border-t border-slate-100 dark:border-slate-700">
                        <div class="flex flex-col">
                            <span class="text-3xl font-black text-slate-900 dark:text-slate-100"><?php echo $recipe_count; ?></span>
                            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Receitas</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-black text-slate-900 dark:text-slate-100"><?php echo $exp_years ?: '1'; ?>+</span>
                            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Anos Exp.</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-black text-slate-900 dark:text-slate-100">4.9</span>
                            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Media Nota</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-black text-slate-900 dark:text-slate-100">50k+</span>
                            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider"> Leitores</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Body Section -->
    <div class="max-w-7xl mx-auto px-4 py-20 flex flex-col lg:flex-row gap-16">
        
        <!-- Sidebar E-E-A-T Credentials -->
        <aside class="w-full lg:w-80 space-y-10 order-2 lg:order-1">
            
            <!-- Expertise Tags -->
            <?php if($expertise) : ?>
            <div class="bg-primary/5 p-8 rounded-3xl border border-primary/20">
                <h3 class="font-black text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">psychology</span> Expertise
                </h3>
                <div class="flex flex-wrap gap-2">
                    <?php 
                    $tags = explode(',', $expertise);
                    foreach($tags as $t) echo '<span class="bg-white dark:bg-slate-900 px-3 py-1 rounded-lg text-xs font-bold border border-primary/10">'.trim($t).'</span>';
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Education & Certs -->
            <div class="space-y-10">
                <?php if($education) : ?>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">school</span> Formação
                    </h3>
                    <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-l-2 border-primary/20 pl-4 py-1">
                        <?php echo nl2br(esc_html($education)); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($certifications) : ?>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">verified</span> Certificações
                    </h3>
                    <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-l-2 border-primary/20 pl-4 py-1">
                        <?php echo nl2br(esc_html($certifications)); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Profile Summary Box -->
            <div class="p-8 rounded-3xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <p class="text-[11px] text-slate-500 uppercase tracking-widest font-black mb-4">Compromisso Editorial</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed italic">
                    "Todas as receitas assinadas por <?php echo $author_name; ?> passam por um rigoroso processo de teste e revisão para garantir o melhor resultado na sua cozinha."
                </p>
            </div>

        </aside>

        <!-- Main Recipe List -->
        <main class="flex-1 order-1 lg:order-2">
            <h2 class="text-3xl font-black text-slate-900 dark:text-slate-100 mb-10 pb-6 border-b border-slate-100 dark:border-slate-700">
                Receitas Criadas por <?php echo $author_name; ?>
            </h2>
            
            <?php if (have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <?php while (have_posts()) : the_post(); 
                        $tempo = get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20 min';
                        $dif = get_post_meta(get_the_ID(), '_dificuldade', true) ?: 'Fácil';
                    ?>
                    <article class="group">
                        <div class="aspect-[16/10] rounded-3xl overflow-hidden mb-6 relative shadow-lg shadow-slate-200/50 dark:shadow-none">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']); ?>
                            </a>
                            <div class="absolute bottom-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-primary">
                                <?php echo $dif; ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> <?php echo $tempo; ?></span>
                            <span>•</span>
                            <span>Postado em <?php the_date('M Y'); ?></span>
                        </div>
                        <h3 class="text-2xl font-bold group-hover:text-primary transition-colors leading-tight mb-3 truncate">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-2 leading-relaxed"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-16 flex justify-center">
                    <?php the_posts_pagination(array(
                        'prev_text' => '<span class="material-symbols-outlined">west</span>',
                        'next_text' => '<span class="material-symbols-outlined">east</span>',
                        'mid_size'  => 2,
                    )); ?>
                </div>

            <?php else : ?>
                <p class="text-slate-500">Este autor ainda não publicou receitas.</p>
            <?php endif; ?>
        </main>

    </div>
</main>

<?php get_footer(); ?>