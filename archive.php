<?php
/**
 * The template for displaying archive pages (Tailwind Version)
 */
get_header(); ?>

<main class="archive-page bg-background-light dark:bg-background-dark min-h-screen">
    
    <!-- Hero Section do Archive -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-b border-primary/5">
        <div class="archive-header text-center lg:text-left">
            <h1 class="text-4xl sm:text-6xl font-black text-slate-900 dark:text-slate-100 mb-6 leading-tight">
                <?php
                if (is_category()) :
                    printf(__('Receitas para <span class="text-primary">%s</span>', 'text-domain'), single_cat_title('', false));
                elseif (is_tag()) :
                    printf(__('Tags: <span class="text-primary">%s</span>', 'text-domain'), single_tag_title('', false));
                elseif (is_author()) :
                    printf(__('Autor: <span class="text-primary">%s</span>', 'text-domain'), get_the_author());
                elseif (is_post_type_archive('achadinhos')) :
                    _e('<span class="text-primary">Achadinhos </span> de Amazon', 'text-domain');
                elseif (is_post_type_archive('reviews')) :
                    _e('<span class="text-primary">Reviews </span> de Produtos', 'text-domain');
                elseif (is_post_type_archive()) :
                    printf(__('Arquivo de <span class="text-primary">%s</span>', 'text-domain'), post_type_archive_title('', false));
                elseif (is_tax()) :
                    printf(__('Explorando <span class="text-primary">%s</span>', 'text-domain'), single_term_title('', false));
                elseif (is_day()) :
                    printf(__('Receitas de <span class="text-primary">%s</span>', 'text-domain'), get_the_date());
                elseif (is_month()) :
                    printf(__('Receitas de <span class="text-primary">%s</span>', 'text-domain'), get_the_date(_x('F Y', 'monthly archives date format', 'text-domain')));
                elseif (is_year()) :
                    printf(__('Receitas de <span class="text-primary">%s</span>', 'text-domain'), get_the_date(_x('Y', 'yearly archives date format', 'text-domain')));
                else :
                    _e('Nossas <span class="text-primary">Receitas</span>', 'text-domain');
                endif;
                ?>
            </h1>
            
            <div class="archive-description text-lg text-slate-500 max-w-2xl lg:ml-0">
                <?php the_archive_description(); ?>
            </div>

            <div class="mt-8 flex flex-wrap gap-4 justify-center lg:justify-start">
                <span class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                    <strong><?php echo $wp_query->found_posts; ?></strong> Receitas Encontradas
                </span>
            </div>
        </div>
    </section>

    <!-- Anúncio Estratégico Archive (Estratégia 2026) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        <!-- Espaço para Publicidade (Ad Inserter) -->
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-3">
                <?php echo custom_breadcrumb(); ?>
                
                <?php if (have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8" id="archive-container">
                        <?php while (have_posts()) : the_post(); 
                            $tempo = get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20 min';
                            $dif = get_post_meta(get_the_ID(), '_dificuldade', true) ?: 'Fácil';
                        ?>
                        <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-2xl transition-all group flex flex-col h-full">
                            <div class="relative aspect-video overflow-hidden bg-slate-100 dark:bg-slate-900">
                                <a href="<?php the_permalink(); ?>">
                                    <?php 
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('recipe-card', array(
                                            'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                            'alt' => get_the_title()
                                        )); 
                                    } else {
                                        $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                                        echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">';
                                    }
                                    ?>
                                </a>
                                <button class="absolute top-4 right-4 p-2 bg-white/80 dark:bg-slate-900/80 rounded-full backdrop-blur hover:bg-white dark:hover:bg-slate-900 transition-colors">
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
                                <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                
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
                        <?php endwhile; ?>
                    </div>

                    <!-- Paginação -->
                    <div class="pagination mt-12 py-8 flex justify-center border-t border-slate-200 dark:border-slate-800">
                        <?php
                        echo paginate_links(array(
                            'prev_text' => '<span class="material-symbols-outlined">chevron_left</span> Anterior',
                            'next_text' => 'Próximo <span class="material-symbols-outlined">chevron_right</span>',
                            'type' => 'plain',
                            'class' => 'flex gap-4 font-bold text-primary active:scale-95 transition-all'
                        ));
                        ?>
                    </div>

                <?php else : ?>
                    <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">search_off</span>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-200 mb-2">Nenhuma receita encontrada</h3>
                        <p class="text-slate-500 mb-8">Não encontramos receitas para este filtro no momento.</p>
                        <a href="<?php echo home_url(); ?>" class="bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-bold inline-block shadow-lg shadow-primary/20 transition-all">Explorar outras receitas</a>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                
                <!-- Widget Categorias -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">category</span>
                        Categorias
                    </h3>
                    <ul class="space-y-3">
                        <?php
                        $categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
                        foreach ($categories as $category) :
                        ?>
                            <li>
                                <a href="<?php echo get_category_link($category->term_id); ?>" class="flex items-center justify-between text-sm group hover:text-primary transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span class="w-1 h-1 bg-slate-300 dark:bg-slate-600 rounded-full group-hover:bg-primary"></span>
                                        <?php echo esc_html($category->name); ?>
                                    </span>
                                    <span class="text-xs bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all"><?php echo esc_html($category->count); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Anúncio Dinâmico: Sidebar (Ad Inserter) -->
                <?php // sts_display_ad('sidebar'); ?>

                <!-- WhatsApp Sidebar Card -->
                <div class="bg-[#25D366]/5 dark:bg-[#25D366]/10 p-6 rounded-2xl border border-[#25D366]/10 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 size-24 bg-[#25D366]/10 rounded-full blur-2xl"></div>
                    <h4 class="font-bold mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#25D366] text-xl">chat</span>
                        Canal do WhatsApp
                    </h4>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">Dicas de culinária e receitas rápidas no seu celular.</p>
                    <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H?utm_source=blog&utm_medium=sidebar_archive&utm_campaign=whatsapp_channel" target="_blank" class="w-full bg-[#25D366] text-white font-black py-3 rounded-xl transition-all shadow-lg shadow-[#25D366]/10 hover:shadow-[#25D366]/20 flex items-center justify-center gap-2 text-xs">
                        ENTRAR NO CANAL
                    </a>
                </div>

            </aside>
        </div>
    </div>
</main>

<?php get_footer(); ?>