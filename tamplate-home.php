<?php
/*
Template Name: Home Page (Tailwind)
*/
get_header();
?>

<main>
    <!-- Hero Section (Destaque Principal) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="@container">
            <div class="flex flex-col gap-8 lg:flex-row items-center lg:items-stretch">
                
                <?php
                /**
                 * Lógica da Receita do Dia (Automática por 24h)
                 */
                // 1. Busca todos os posts marcados como destaque (ou todos se não houver)
                $candidates = get_posts(array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                    'meta_key' => 'destaque',
                    'meta_value' => '1'
                ));

                if (empty($candidates)) {
                    $candidates = get_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => 50,
                        'fields' => 'ids'
                    ));
                }

                $hero_post_id = 0;
                if (!empty($candidates)) {
                    // Semente baseada no dia atual (ex: 20231015)
                    // Isso garante que o "sorteio" seja o mesmo para todos no mesmo dia
                    $seed = (int)date('Ymd');
                    $index = $seed % count($candidates);
                    $hero_post_id = $candidates[$index];
                }

                // Query final para o post selecionado
                if ($hero_post_id) {
                    $hero_query = new WP_Query(array('p' => $hero_post_id));
                } else {
                    $hero_query = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 1));
                }

                if ($hero_query->have_posts()) : while ($hero_query->have_posts()) : $hero_query->the_post();
                    $hero_img = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: THEME_URI . '/assets/images/placeholder.jpg';
                ?>
                <div class="w-full lg:w-3/5 relative rounded-[32px] overflow-hidden group min-h-[450px] shadow-2xl border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-900">
                    <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('full', [
                            'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110',
                            'loading' => 'eager', 
                            'fetchpriority' => 'high',
                            'decoding' => 'async',
                            'alt' => get_the_title()
                        ]); 
                    } else {
                        $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                        echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="eager" decoding="async">';
                    }
                    ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    
                    <div class="absolute top-6 left-6 z-10">
                        <span class="bg-primary text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/20 flex items-center gap-2">
                           <span class="material-symbols-outlined text-sm">auto_awesome</span> Receita do Dia
                        </span>
                    </div>

                    <div class="absolute bottom-0 left-0 p-6 sm:p-12 text-white z-10 w-full">
                        <div class="flex items-center gap-2 text-primary text-sm font-bold mb-4">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            <?php echo date_i18n('d \d\e F, Y'); ?>
                        </div>
                        <h1 class="text-2xl sm:text-4xl md:text-5xl font-black mb-4 sm:mb-6 leading-[1.1] group-hover:text-primary transition-colors line-clamp-3 sm:line-clamp-none"><?php the_title(); ?></h1>
                        <p class="text-slate-200 text-lg mb-8 max-w-xl line-clamp-2 opacity-90 leading-relaxed font-medium"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                        <a href="<?php the_permalink(); ?>" class="bg-primary hover:bg-white hover:text-primary text-white px-10 py-5 rounded-2xl font-black inline-flex items-center gap-3 transition-all active:scale-95 shadow-2xl shadow-primary/40 transform -translate-y-0 group-hover:-translate-y-1">
                            VER DETALHES <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>                <!-- Tendências da Semana (Muda a cada 7 dias) -->
                <div class="w-full lg:w-2/5 flex flex-col gap-4">
                    <div class="bg-primary/5 dark:bg-primary/5 p-6 sm:p-8 rounded-[32px] border border-slate-100 dark:border-slate-800 flex-1 relative overflow-hidden group/trends">
                        <div class="absolute -top-10 -right-10 size-40 bg-primary/5 rounded-full blur-3xl group-hover/trends:bg-primary/10 transition-all duration-700"></div>
                        
                        <h2 class="text-xl sm:text-2xl font-black mb-6 sm:mb-8 flex items-center gap-3 leading-tight">
                            <span class="material-symbols-outlined text-primary text-2xl sm:text-3xl">local_fire_department</span>
                            Tendências da <span class="text-primary italic">Semana</span>
                        </h2>
                        
                        <div class="space-y-6">
                            <?php
                            // Lógica de Sorteio Semanal (Muda a cada Segunda-feira)
                            $week_seed = (int)date('W') + (int)date('Y'); // Semente única por semana/ano
                            
                            $pool_ids = get_posts(array(
                                'post_type' => 'post',
                                'posts_per_page' => 20,
                                'fields' => 'ids',
                                'post__not_in' => isset($hero_post_id) ? array($hero_post_id) : array()
                            ));

                            if (!empty($pool_ids)) {
                                // Sorteio determinístico baseado na semana
                                mt_srand($week_seed);
                                shuffle($pool_ids);
                                $trends_ids = array_slice($pool_ids, 0, 3);
                            } else {
                                $trends_ids = array();
                            }

                            if (!empty($trends_ids)) {
                                $trends_query = new WP_Query(array('post__in' => $trends_ids, 'orderby' => 'post__in'));
                                
                                while ($trends_query->have_posts()) : $trends_query->the_post();
                                $t_img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: THEME_URI . '/assets/images/placeholder.jpg';
                            ?>
                            <a class="flex gap-5 group items-center p-3 rounded-2xl hover:bg-white dark:hover:bg-slate-800 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-700 hover:shadow-xl hover:shadow-primary/5" href="<?php the_permalink(); ?>">
                                <div class="size-20 rounded-2xl overflow-hidden shrink-0 shadow-lg group-hover:shadow-primary/20 transition-all bg-slate-100 dark:bg-slate-900">
                                    <?php 
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700', 'alt' => get_the_title())); 
                                    } else {
                                        $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                                        echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" decoding="async">';
                                    }
                                    ?>
                                </div>
                                <div class="flex flex-col">
                                    <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                        <?php the_title(); ?>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs">schedule</span> <?php echo get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20 min'; ?></span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="text-primary"><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                            </a>
                            <?php endwhile; wp_reset_postdata(); } ?>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center italic opacity-70">
                                🔄 Próxima atualização na segunda-feira.
                             </p>
                        </div>
                    </div>
                </div>
</div>
            </div>
        </div>
    </section>
    
    <!-- Filtro de Dispensa (Mecanismo de Busca Avançado) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php get_template_part('template-parts/pantry-filter'); ?>
    </div>

    <!-- Anúncio Estratégico Display (Estratégia 2026) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Espaço para Publicidade (Ad Inserter) -->
    </div>

    <!-- Categorias -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-2xl font-bold mb-8">Navegar por Categoria</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            // Mapeamento de Slugs e Nomes solicitados pelo usuário
            $cat_config = array(
                'lanches'  => ['icon' => 'lunch_dining', 'color' => 'orange', 'label' => 'Lanches'],
                'bolos'    => ['icon' => 'cake', 'color' => 'pink', 'label' => 'Bolos'],
                'fitness'  => ['icon' => 'fitness_center', 'color' => 'blue', 'label' => 'Fitness'],
                'low-carb' => ['icon' => 'spa', 'color' => 'red', 'label' => 'Low Carb']
            );

            // Tenta pegar as categorias específicas ou nomes aproximados
            $categories_to_show = [];
            foreach ($cat_config as $slug => $data) {
                // Tenta pelo slug original
                $term = get_term_by('slug', $slug, 'category');
                
                // Se não achar, tenta slug alternativo (fit por exemplo)
                if (!$term && $slug === 'fitness') $term = get_term_by('slug', 'fit', 'category');
                
                // Se ainda não achar, tenta pelo nome literal
                if (!$term) $term = get_term_by('name', $data['label'], 'category');
                
                // Se ainda não achar, tenta sem acento (Bolos é igual, mas Low Carb pode variar)
                if (!$term && $slug === 'low-carb') $term = get_term_by('name', 'Lowcarb', 'category');

                if ($term) {
                    $categories_to_show[] = [
                        'term' => $term,
                        'icon' => $data['icon'],
                        'color' => $data['color'],
                        'label' => $term->name
                    ];
                }
            }

            // Fallback se não encontrar todas (mostra as mais populares para não ficar vazio)
            if (count($categories_to_show) < 4) {
                $popular_cats = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8, 'hide_empty' => true));
                foreach ($popular_cats as $pcat) {
                    if (count($categories_to_show) >= 4) break;
                    $already_in = false;
                    foreach ($categories_to_show as $existing) { if ($existing['term']->term_id == $pcat->term_id) { $already_in = true; break; } }
                    if (!$already_in) {
                        $categories_to_show[] = ['term' => $pcat, 'icon' => 'restaurant_menu', 'color' => 'slate', 'label' => $pcat->name];
                    }
                }
            }

            foreach ($categories_to_show as $cdata) :
                $t = $cdata['term'];
                $colors = [
                    'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
                    'pink'   => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                    'blue'   => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-600'],
                    'red'    => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'], // Low carb em verde/esmeralda
                    'slate'  => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500']
                ];
                $style = $colors[$cdata['color']] ?? $colors['slate'];
            ?>
            <a href="<?php echo get_category_link($t->term_id); ?>" class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-[20px] p-6 flex flex-col items-center justify-center text-center transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="w-16 h-16 <?php echo $style['bg']; ?> rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined <?php echo $style['text']; ?> text-3xl font-light"><?php echo $cdata['icon']; ?></span>
                </div>
                <span class="font-black text-slate-800 dark:text-slate-100 group-hover:text-primary transition-colors"><?php echo esc_html($cdata['label']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Últimas Receitas -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-black">Últimas Receitas</h2>
            <a class="text-primary font-bold flex items-center gap-1 hover:underline" href="<?php echo get_permalink(get_option('page_for_posts')); ?>">
                Ver tudo <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $latest_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 6
            ));

            if ($latest_query->have_posts()) : while ($latest_query->have_posts()) : $latest_query->the_post();
                $tempo = get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20 min';
                $dif = get_post_meta(get_the_ID(), '_dificuldade', true) ?: 'Fácil';
            ?>
            <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-2xl transition-all group flex flex-col h-full">
                <div class="relative aspect-video overflow-hidden bg-slate-100 dark:bg-slate-900">
                    <a href="<?php the_permalink(); ?>">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('recipe-card', array('class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'alt' => get_the_title())); 
                        } else {
                            $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                            echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">';
                        }
                        ?>
                    </a>
                    <button class="btn-favorite absolute top-4 right-4 p-2 bg-white/80 dark:bg-slate-900/80 rounded-full backdrop-blur hover:bg-white dark:hover:bg-slate-900 transition-colors z-10" data-post-id="<?php the_ID(); ?>" aria-label="Favoritar receita">
                        <span class="material-symbols-outlined text-primary">favorite</span>
                    </button>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-500 dark:text-slate-400 mb-3">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> <?php echo $tempo; ?></span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">bar_chart</span> <?php echo $dif; ?></span>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-primary transition-colors">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2 flex-1"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700 mt-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                            </div>
                            <span class="text-xs font-semibold"><?php the_author(); ?></span>
                        </div>
                        <span class="text-xs text-slate-400"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' atrás'; ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </section>

    <?php
    // Categorias solicitadas para destaque
    $destaque_categorias = array(
        array('slug' => 'doces-e-sobremesas', 'name' => 'Doces e Sobremesas', 'icon' => 'icecream', 'color' => 'text-pink-500'),
        array('slug' => 'culinaria-oriental', 'name' => 'Culinária Oriental', 'icon' => 'ramen_dining', 'color' => 'text-red-600'),
        array('slug' => 'receitas-veganas', 'name' => 'Receitas Veganas', 'icon' => 'eco', 'color' => 'text-emerald-500')
    );

    foreach ($destaque_categorias as $cat_info) :
        $term = get_term_by('slug', $cat_info['slug'], 'category');
        if (!$term) $term = get_term_by('name', $cat_info['name'], 'category');
        
        if ($term && $term->count > 0) :
            $cat_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 7,
                'cat' => $term->term_id
            ));

            if ($cat_query->have_posts()) :
    ?>
    <!-- Seção: <?php echo $term->name; ?> -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
        <div class="flex items-center justify-between mb-10 border-b border-slate-100 dark:border-slate-800 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined <?php echo $cat_info['color']; ?> text-3xl"><?php echo $cat_info['icon']; ?></span>
                </div>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white"><?php echo $term->name; ?></h2>
            </div>
            <a href="<?php echo get_category_link($term->term_id); ?>" class="group flex items-center gap-2 text-sm font-black text-primary uppercase tracking-widest hover:bg-primary hover:text-white px-4 py-2 rounded-xl transition-all">
                Ver Tudo <span class="material-symbols-outlined text-lg">chevron_right</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
                $count = 0;
                while ($cat_query->have_posts()) : $cat_query->the_post();
                    $count++;
                    $tempo = get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '30 min';
                    
                    if ($count === 1) : // O Destaque (Fica no topo ou ocupa mais espaço)
            ?>
                <!-- Card de Destaque -->
                <article class="md:col-span-2 lg:col-span-3 group relative h-[400px] sm:h-[500px] rounded-[32px] overflow-hidden shadow-2xl mb-4">
                    <a href="<?php the_permalink(); ?>" class="absolute inset-0 z-0 bg-slate-100 dark:bg-slate-900">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large', array('class' => 'w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110', 'alt' => get_the_title())); 
                        } else {
                            $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                            echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy" decoding="async">';
                        }
                        ?>
                    </a>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-1"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 sm:p-12 z-10 text-white max-w-3xl">
                        <span class="inline-block px-4 py-1.5 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest mb-4 shadow-lg shadow-primary/20">Receita em Destaque</span>
                        <h3 class="text-3xl sm:text-5xl font-black mb-6 leading-tight drop-shadow-sm">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
                        </h3>
                        <div class="flex flex-wrap items-center gap-6 text-sm font-bold opacity-90">
                            <span class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full"><span class="material-symbols-outlined text-sm">schedule</span> <?php echo $tempo; ?></span>
                            <span class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">person</span> Por <?php the_author(); ?></span>
                            <span class="hidden sm:inline-block opacity-40">|</span>
                            <span><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </article>
            <?php else : // As outras 6 receitas em grade ?>
                <!-- Card Padrão Grade -->
                <article class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:shadow-xl transition-all group flex flex-col">
                    <div class="relative aspect-video overflow-hidden bg-slate-100 dark:bg-slate-900">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('recipe-card', array('class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'alt' => get_the_title())); 
                            } else {
                                $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                                echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">';
                            }
                            ?>
                        </a>
                        <div class="absolute top-4 left-4">
                             <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-[10px] font-black uppercase text-primary flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">schedule</span> <?php echo $tempo; ?>
                             </div>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <h4 class="text-lg font-bold leading-snug group-hover:text-primary transition-colors line-clamp-2 mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <p class="text-slate-500 dark:text-slate-400 text-xs line-clamp-2 mb-4 flex-1"><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
                        <div class="pt-4 border-t border-slate-50 dark:border-slate-700 flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span><?php the_author(); ?></span>
                            <span><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </article>
            <?php 
                    endif;
                endwhile; 
            ?>
        </div>
    </section>
    <?php
            endif;
            wp_reset_postdata();
        endif;
    endforeach;
    ?>

    <!-- Seção Achadinhos Amazon -->
    <section class="bg-slate-50 dark:bg-slate-900/50 py-16 sm:py-24 mt-12 border-y border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-4 block">Afiliados Amazon</span>
                    <h2 class="text-3xl sm:text-5xl font-black mb-6 leading-tight">Achadinhos para sua <span class="text-primary italic">Cozinha</span></h2>
                    <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">Selecionamos a dedo os utensílios, eletrodomésticos e ingredientes que realmente fazem a diferença na nossa rotina culinária.</p>
                </div>
                <a href="<?php echo get_post_type_archive_link('achadinhos'); ?>" class="group flex items-center gap-2 font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                    Ver vitrine completa 
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $achadinhos_query = new WP_Query(array(
                    'post_type' => 'achadinhos',
                    'posts_per_page' => 4
                ));

                if ($achadinhos_query->have_posts()) : while ($achadinhos_query->have_posts()) : $achadinhos_query->the_post();
                    $link = get_post_meta(get_the_ID(), 'link_produto', true) ?: '#';
                    $preco = get_post_meta(get_the_ID(), 'valor_produto', true);
                ?>
                <div class="bg-white dark:bg-slate-800 rounded-[32px] p-4 border border-slate-100 dark:border-slate-700 hover:shadow-2xl transition-all group relative">
                    <!-- Badge de Oferta (Opcional) -->
                    <div class="absolute top-6 left-6 z-10">
                        <span class="bg-amber-400 text-black px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-wider shadow-sm">Recomendado</span>
                    </div>

                    <div class="aspect-square rounded-[24px] overflow-hidden mb-6 bg-slate-50 dark:bg-slate-900">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500')); ?>
                        <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <span class="material-symbols-outlined text-5xl">shopping_basket</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="px-2 pb-2">
                        <h3 class="text-lg font-bold mb-3 line-clamp-1 group-hover:text-primary transition-colors"><?php the_title(); ?></h3>
                        <div class="flex items-center justify-between mb-6">
                            <?php if ($preco) : ?>
                                <span class="text-xl font-black text-slate-900 dark:text-white">R$ <?php echo $preco; ?></span>
                            <?php else : ?>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ver Preço Atual</span>
                            <?php endif; ?>
                            <div class="flex text-amber-500">
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm) font-bold">star</span>
                            </div>
                        </div>

                        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="nofollow noopener" class="w-full bg-slate-900 dark:bg-slate-700 hover:bg-primary text-white py-4 rounded-2xl font-black text-xs text-center flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-primary/20">
                            VER NA AMAZON <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <!-- WhatsApp CTA (Opcional) -->
    <?php if (file_exists(get_template_directory() . '/template-parts/whatsapp.php')) include(get_template_directory() . '/template-parts/whatsapp.php'); ?>

</main>

<?php get_footer(); ?>
