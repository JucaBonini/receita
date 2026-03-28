<?php
/**
 * The template for displaying search results (Tailwind Version)
 */
get_header(); ?>

<main class="search-page bg-background-light dark:bg-background-dark min-h-screen">
    
    <!-- Hero Section da Busca -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="inline-flex items-center justify-center size-16 bg-primary/10 rounded-2xl mb-6">
            <span class="material-symbols-outlined text-primary text-3xl">search</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-slate-100 mb-6">
            Você buscou por: <span class="text-primary">"<?php echo esc_html(get_search_query()); ?>"</span>
        </h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto">
            Encontramos <strong class="text-slate-900 dark:text-slate-100"><?php echo $wp_query->found_posts; ?></strong> <?php echo $wp_query->found_posts == 1 ? 'resultado' : 'resultados'; ?> para sua pesquisa.
        </p>
        
        <!-- Re-search Box -->
        <div class="mt-10 max-w-lg mx-auto">
            <form action="<?php echo home_url(); ?>" method="get" class="relative group">
                <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Tentar outra busca..." class="w-full bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 py-4 px-6 rounded-2xl focus:border-primary focus:ring-0 transition-all text-lg shadow-xl shadow-slate-200/50 dark:shadow-none">
                <button type="submit" class="absolute right-2 top-2 size-12 bg-primary text-white rounded-xl flex items-center justify-center hover:scale-105 transition-transform shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </form>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while (have_posts()) : the_post(); 
                    $tempo = get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20 min';
                    $dif = get_post_meta(get_the_ID(), '_dificuldade', true) ?: 'Fácil';
                ?>
                <article class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:shadow-2xl transition-all group flex flex-col h-full">
                    <div class="relative aspect-video overflow-hidden">
                        <a href="<?php the_permalink(); ?>">
                            <?php if(has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center"><span class="material-symbols-outlined text-4xl text-slate-300">image</span></div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] bg-primary/10 text-primary font-black uppercase tracking-widest px-2 py-0.5 rounded-md">
                                <?php echo get_post_type() == 'post' ? 'Receita' : 'Página'; ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold mb-3 group-hover:text-primary transition-colors leading-tight">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-2 mb-6"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-700 flex justify-between items-center text-xs text-slate-400">
                            <span class="flex items-center gap-1 font-bold text-slate-500"><span class="material-symbols-outlined text-sm">schedule</span> <?php echo $tempo; ?></span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">history</span> <?php the_time('d/m/Y'); ?></span>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <!-- Paginação -->
            <div class="mt-16 flex justify-center">
                <?php the_posts_pagination(array(
                    'prev_text' => '<span class="material-symbols-outlined">west</span>',
                    'next_text' => '<span class="material-symbols-outlined">east</span>',
                )); ?>
            </div>

        <?php else : ?>
            
            <!-- No Results -->
            <div class="max-w-2xl mx-auto py-20 text-center bg-white dark:bg-slate-800 rounded-[40px] border-2 border-dashed border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="material-symbols-outlined text-7xl text-slate-200 mb-6 block">search_off</span>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Poxa, não encontramos nada!</h3>
                <p class="text-slate-500 mb-10 px-8 leading-relaxed">Não encontramos nenhuma receita ou conteúdo para sua busca. Tente buscar por ingredientes simples como "chocolate", "frango" ou categorias.</p>
                
                <div class="flex flex-wrap justify-center gap-3 px-8">
                    <?php 
                    $pop = ['Bolo', 'Salada', 'Jantar Rápido', 'Sobremesa', 'Café da Manhã'];
                    foreach($pop as $p) : ?>
                        <a href="<?php echo home_url('/?s='.urlencode($p)); ?>" class="px-4 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-primary/10 hover:text-primary rounded-full text-sm font-bold transition-all border border-slate-100 dark:border-slate-600">
                            <?php echo $p; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>