<?php
/**
 * The template for displaying category pages (Tailwind Version)
 */
get_header(); 

$category = get_queried_object();
$category_id = $category->term_id;
$category_image = get_term_meta($category_id, 'category_image', true);
?>

<main class="category-page bg-background-light dark:bg-background-dark min-h-screen">
    
    <!-- Hero Section da Categoria -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative overflow-hidden rounded-b-[40px] bg-slate-900 text-white">
        <?php if ($category_image) : ?>
            <div class="absolute inset-0 opacity-40">
                <img src="<?php echo esc_url($category_image); ?>" alt="<?php single_cat_title(); ?>" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        
        <div class="relative z-10 text-center lg:text-left">
            <nav class="flex items-center gap-2 text-sm text-slate-300 mb-6 justify-center lg:justify-start">
                <a href="<?php echo home_url(); ?>" class="hover:text-primary transition-colors">Home</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-primary font-bold">Categorias</span>
            </nav>
            
            <h1 class="text-5xl sm:text-7xl font-black mb-6 leading-tight">
                <?php single_cat_title(); ?>
            </h1>
            
            <?php if (category_description()) : ?>
                <div class="max-w-2xl text-lg text-slate-300 mb-8 leading-relaxed">
                    <?php echo category_description(); ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-6 justify-center lg:justify-start items-center">
                <span class="bg-primary px-6 py-2 rounded-full text-sm font-bold shadow-lg shadow-primary/20">
                    <?php echo $category->count; ?> Receitas
                </span>
                <div class="flex -space-x-3">
                    <?php 
                    // Mostra avatares dos autores que postaram nesta categoria
                    $authors_query = new WP_Query(array('post_type' => 'post', 'cat' => $category_id, 'posts_per_page' => 5));
                    $displayed_authors = [];
                    while($authors_query->have_posts()) : $authors_query->the_post();
                        $aid = get_the_author_meta('ID');
                        if(!in_array($aid, $displayed_authors)) {
                            echo '<div class="size-10 rounded-full border-2 border-slate-900 overflow-hidden bg-slate-700">'.get_avatar($aid, 40).'</div>';
                            $displayed_authors[] = $aid;
                        }
                    endwhile; wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Anúncio Estratégico Archive (Estratégia 2026) -->
    <div class="max-w-7xl mx-auto px-4 mt-12 overflow-hidden flex justify-center">
        <!-- Espaço para Publicidade (Ad Inserter) -->
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Grid de Receitas -->
            <div class="lg:col-span-3">
                <?php if (have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php while (have_posts()) : the_post(); 
                            $dif_raw = get_post_meta(get_the_ID(), '_dificuldade', true) ?: get_post_meta(get_the_ID(), 'dificuldade', true);
                            // Filtro para não deixar aparecer o código "field_"
                            $dif = (strpos($dif_raw, 'field_') === false && !empty($dif_raw)) ? $dif_raw : 'Fácil';
                        ?>
                        <article class="bg-white dark:bg-slate-800 rounded-[40px] overflow-hidden border border-slate-100 dark:border-slate-700/50 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 group flex flex-col h-full">
                            <!-- Imagem principal com Zoom no Hover -->
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                    <?php if(has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 ease-out')); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-slate-300 text-6xl">image</span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                <!-- Badge de Dificuldade Premium (Lugar do 'DO CHEF') -->
                                <div class="absolute top-4 left-4 px-4 py-2 bg-white dark:bg-slate-900 rounded-xl shadow-lg z-10 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-widest">
                                        <?php echo esc_html($dif); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-8 md:p-10 flex flex-col flex-1">
                                <!-- Título com foco narrativo -->
                                <h3 class="text-2xl font-black mb-4 group-hover:text-primary transition-colors leading-tight tracking-tight">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <!-- Meta de Tempo (Badget elegante) -->
                                <div class="flex items-center gap-2 mb-6">
                                    <div class="flex items-center gap-1.5 px-3 py-1 bg-primary/5 text-primary rounded-full">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        <span class="text-xs font-bold uppercase tracking-tight"><?php echo sts_get_recipe_total_time(); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 rounded-full">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span class="text-xs font-bold uppercase tracking-tight"><?php echo get_post_views(get_the_ID()); ?></span>
                                    </div>
                                </div>

                                <p class="text-slate-600 dark:text-slate-400 text-base leading-relaxed mb-8 line-clamp-2"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                
                                <a href="<?php the_permalink(); ?>" class="mt-auto flex items-center justify-center gap-2 w-full py-4 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all group">
                                    Ver Receita Completa
                                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    </div>

                    <!-- Paginação Premium -->
                    <?php sts_pagination(); ?>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <!-- Search Widget -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold mb-4">Buscar em <?php single_cat_title(); ?></h4>
                    <form action="<?php echo home_url(); ?>" method="get" class="relative">
                        <input type="text" name="s" placeholder="O que você procura?" class="w-full bg-slate-100 dark:bg-slate-700 border-none rounded-2xl py-4 pl-4 pr-12 focus:ring-2 focus:ring-primary transition-all">
                        <button type="submit" class="absolute right-2 top-2 size-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>

                <!-- Trending in Category -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trending_up</span>
                        Mais Vistas
                    </h4>
                    <div class="space-y-6">
                        <?php
                        $pop_cat_query = new WP_Query(array(
                            'post_type' => 'post',
                            'cat' => $category_id,
                            'posts_per_page' => 4,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC'
                        ));
                        if($pop_cat_query->have_posts()) : while($pop_cat_query->have_posts()) : $pop_cat_query->the_post();
                        ?>
                        <a href="<?php the_permalink(); ?>" class="group flex gap-4 items-center">
                            <div class="size-16 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-100">
                                <?php the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform']); ?>
                            </div>
                            <h5 class="text-sm font-bold leading-tight group-hover:text-primary transition-colors line-clamp-2"><?php the_title(); ?></h5>
                        </a>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </div>
                </div>
                </div>

                <!-- Anúncio Dinâmico: Sidebar (Ad Inserter) -->
                <div class="flex justify-center overflow-hidden">
                </div>
            </aside>
        </div>
    </div>
</main>

<?php get_footer(); ?>