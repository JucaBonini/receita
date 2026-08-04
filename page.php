<?php
/**
 * The template for displaying all pages (Tailwind Version)
 */
get_header(); ?>

<main class="page-template bg-background-light dark:bg-background-dark min-h-screen pb-20">
    
    <?php while (have_posts()) : the_post(); ?>
    <!-- Page Hero -->
    <section class="max-w-4xl mx-auto px-4 pt-16 pb-12 text-center">
        <nav class="flex items-center justify-center gap-2 text-xs font-bold text-primary uppercase tracking-widest mb-6">
            <a href="<?php echo home_url(); ?>">Home</a>
            <span class="material-symbols-outlined text-[10px]">chevron_right</span>
            <span>Página</span>
        </nav>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-slate-100 mb-8 leading-tight">
            <?php the_title(); ?>
        </h1>
        <?php if (has_excerpt()) : ?>
            <div class="text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Featured Image (Optional) -->
    <?php if (has_post_thumbnail()) : ?>
    <section class="max-w-5xl mx-auto px-4 mb-16">
        <div class="aspect-[21/9] rounded-[40px] overflow-hidden shadow-2xl">
            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <section class="max-w-3xl mx-auto px-4">
        <article class="prose prose-lg dark:prose-invert max-w-none prose-slate prose-headings:font-black prose-headings:text-slate-900 dark:prose-headings:text-white prose-p:text-slate-600 dark:prose-p:text-slate-400 prose-a:text-primary prose-img:rounded-3xl shadow-sm bg-white dark:bg-slate-800 p-8 md:p-12 rounded-[40px] border border-slate-100 dark:border-slate-700">
            <?php the_content(); ?>
            
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links mt-8 pt-8 border-t border-slate-100"><span class="page-links-title font-bold mr-2">' . __('Páginas:', 'text-domain') . '</span>',
                'after' => '</div>',
                'link_before' => '<span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-md mx-1">',
                'link_after' => '</span>',
            ));
            ?>
        </article>

        <!-- Page Actions -->
        <div class="mt-12 flex flex-wrap items-center justify-center gap-6">
            <button onclick="window.print()" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">print</span> Imprimir Página
            </button>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>
            <a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">mail</span> Compartilhar
            </a>
        </div>
    </section>
    <?php endwhile; ?>

</main>

<?php get_footer(); ?>