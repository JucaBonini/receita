<?php
/**
 * The template for displaying all single posts (Tailwind Premium 2026)
 */
get_header();

if (have_posts()) : while (have_posts()) : the_post();
    $post_id = get_the_ID();

    // 1. Coleta de Dados
    $tempo_preparo    = get_field('tempo') ?: get_post_meta($post_id, '_tempo_preparo', true); 
    $tempo_cozimento  = get_post_meta($post_id, '_tempo_cozimento', true);
    $porcoes_meta     = get_post_meta($post_id, '_porcoes', true);
    $dificuldade      = get_field('dificuldade') ?: get_post_meta($post_id, '_dificuldade', true);
    $cuisine_meta     = get_post_meta($post_id, '_recipe_cuisine', true) ?: 'Brasileira'; 

    $calorias         = get_post_meta($post_id, '_calorias', true);
    $carboidratos     = get_post_meta($post_id, '_carboidratos', true);
    $proteinas        = get_post_meta($post_id, '_proteinas', true);
    $gorduras         = get_post_meta($post_id, '_gorduras', true);
    
    $ingredientes_raw = get_post_meta($post_id, '_ingredientes', true);
    $ingredientes_grp = get_post_meta($post_id, '_ingredientes_grupo', true);
    $instrucoes_raw   = get_post_meta($post_id, '_instrucoes', true);
    $utensilios       = get_post_meta($post_id, '_utensilios', true);

    $rating_total = (int) get_post_meta($post_id, '_rating_total', true);
    $rating_count = (int) get_post_meta($post_id, '_rating_count', true);
    $rating_avg   = $rating_count > 0 ? round($rating_total / $rating_count, 1) : 4.8;

    $video_url    = get_post_meta($post_id, '_video_url', true);
    $diet_type    = get_post_meta($post_id, '_diet_type', true);
    $nutri_serving = get_post_meta($post_id, '_nutri_serving', true);
    $nutri_source = get_post_meta($post_id, '_nutri_source', true);
    
    // Preparação de dados do Autor (E-E-A-T)
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author();
    $author_job = get_the_author_meta('job_title', $author_id) ?: 'Chef e Especialista Culinar';
    $author_expertise = get_the_author_meta('expertise', $author_id);
    $author_avatar = get_avatar_url($author_id, ['size' => 120]);
    $author_url = get_author_posts_url($author_id);

    // Breadcrumbs
    $categories = get_the_category($post_id);
    $main_cat = !empty($categories) ? $categories[0] : null;

    /* ===========================
        SCHEMA RECIPE (JSON-LD)
    =========================== */
    include(get_template_directory() . '/template-parts/schema-recipe.php');
?>

<main class="max-w-6xl mx-auto px-4 py-8">
    <article>
        
        <!-- Top Meta (Breadcrumb & Title) -->
        <div class="mb-8">
            <?php echo custom_breadcrumb(); ?>
            
            <div class="flex items-center justify-between gap-4 mb-4">
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-slate-100 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Anúncio Dinâmico: Abaixo do Título -->
                <?php sts_display_ad('after_title'); ?>
                <button class="btn-favorite flex-shrink-0 size-14 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center hover:scale-110 transition-all text-primary group" data-post-id="<?php the_ID(); ?>" aria-label="Favoritar está receita">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600 dark:text-slate-400">
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-primary text-xl">star</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100"><?php echo $rating_avg; ?></span>
                    <span>(<?php echo $rating_count ?: rand(50, 200); ?> avaliações)</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-slate-400 text-xl">schedule</span>
                    <span>Atualizado em <?php the_modified_date('d/m/Y'); ?></span>
                </div>
            </div>
        </div>

        <!-- Hero Image (LCP Optimization) -->
        <div class="aspect-video w-full rounded-2xl overflow-hidden mb-8 shadow-xl relative group">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('full', [
                    'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105',
                    'loading' => 'eager', 
                    'fetchpriority' => 'high',
                    'decoding' => 'async'
                ]); 
            } else {
                echo '<div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center"><span class="material-symbols-outlined text-6xl text-slate-400">restaurant</span></div>';
            }
            ?>
        </div>

        <!-- Metadata Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-12">
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">timer</span>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Preparo</span>
                <span class="text-xl font-bold"><?php echo $tempo_preparo ?: '20'; ?>m</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">cooking</span>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Cozimento</span>
                <span class="text-xl font-bold"><?php echo $tempo_cozimento ?: '30'; ?>m</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">restaurant</span>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Rendimento</span>
                <span class="text-xl font-bold"><?php echo $porcoes_meta ?: '4'; ?></span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">bar_chart</span>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Dificuldade</span>
                <span class="text-xl font-bold"><?php echo $dificuldade ?: 'Fácil'; ?></span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">health_and_safety</span>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Dieta</span>
                <span class="text-xl font-bold text-xs truncate w-full"><?php echo $diet_type ?: 'Padrão'; ?></span>
            </div>
        </div>

        <!-- Video Section (If exists) -->
        <?php if (!empty($video_url)) : ?>
            <div class="mb-12 aspect-video rounded-[32px] overflow-hidden shadow-2xl bg-black">
                <?php 
                $embed_url = str_contains($video_url, 'youtube.com') ? str_replace('watch?v=', 'embed/', $video_url) : $video_url;
                ?>
                <iframe class="w-full h-full" src="<?php echo esc_url($embed_url); ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>

        <!-- Content Structure -->
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- Main Content Area -->
            <div class="lg:col-span-2">
                
                <!-- Intro/Content -->
                <div class="prose sm:prose-xl dark:prose-invert max-w-none text-lg leading-relaxed space-y-8 prose-headings:text-primary prose-a:text-primary mb-12 selection:bg-primary/20">
                    <?php if (has_excerpt()) : ?>
                        <p class="text-2xl text-slate-700 dark:text-slate-300 font-medium italic border-l-4 border-primary pl-6 py-4 bg-primary/5 rounded-r-2xl mb-10 leading-relaxed">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php the_content(); ?>
                </div>

                <!-- Utensílios (Destaque) -->
                <?php if (!empty($utensilios)) : ?>
                <section class="mb-12 bg-slate-100 dark:bg-slate-800/50 p-6 rounded-2xl border-l-4 border-primary">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">skillet</span>
                        Utensílios Necessários
                    </h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach($utensilios as $u) : ?>
                            <li class="flex items-center gap-2 text-sm"><span class="w-1.5 h-1.5 bg-primary rounded-full"></span> <?php echo esc_html($u); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
                <?php endif; ?>

                <!-- Ingredients Section -->
                <section class="mb-12" id="ingredients">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">shopping_basket</span>
                            Ingredientes
                        </h2>
                        <button onclick="window.print()" class="text-sm font-bold text-primary flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">print</span> Imprimir
                        </button>
                    </div>

                    <div class="space-y-6">
                        <?php if (is_array($ingredientes_grp)) : foreach ($ingredientes_grp as $idx => $grupo_nome) : ?>
                            <div class="ingredient-group">
                                <?php if (!empty($grupo_nome)) : ?>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-3 ml-1"><?php echo esc_html($grupo_nome); ?></h3>
                                <?php endif; ?>
                                
                                <div class="space-y-3">
                                    <?php 
                                    $itens = is_array($ingredientes_raw) ? explode("\n", $ingredientes_raw[$idx]) : [];
                                    foreach ($itens as $item) : if(trim($item)) :
                                    ?>
                                    <label class="flex items-center p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 cursor-pointer hover:border-primary/50 transition-colors group">
                                        <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary bg-transparent mr-4 cursor-pointer" type="checkbox"/>
                                        <span class="text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors"><?php echo esc_html($item); ?></span>
                                    </label>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </section>

                <!-- Instructions Section -->
                <section class="mb-12" id="instructions">
                    <h2 class="text-2xl font-bold flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">format_list_numbered</span>
                        Modo de Preparo
                    </h2>
                    <div class="space-y-8 relative before:absolute before:left-5 before:top-2 before:bottom-2 before:w-0.5 before:bg-primary/10">
                        <?php 
                        if (is_array($instrucoes_raw)) : foreach($instrucoes_raw as $i => $passo) : if(trim($passo)) :
                        ?>
                        <div class="relative pl-12 group">
                            <div class="absolute left-0 top-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform"><?php echo $i + 1; ?></div>
                            <h3 class="text-lg font-bold mb-2">Passo <?php echo $i + 1; ?></h3>
                            <div class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                <?php echo nl2br(esc_html($passo)); ?>
                            </div>
                        </div>
                        <?php endif; endforeach; endif; ?>
                    </div>
                </section>

                <!-- Avaliação da Receita Widget -->
                <section class="mb-12 bg-white dark:bg-slate-800 p-8 rounded-[32px] border border-slate-100 dark:border-slate-700 shadow-sm text-center relative overflow-hidden" id="rating-widget">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                    
                    <h2 class="text-2xl font-black mb-2 text-slate-900 dark:text-white">O que achou desta receita?</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Sua avaliação nos ajuda muito a melhorar!</p>
                    
                    <div class="flex items-center justify-center gap-2 mb-6" id="rating-stars">
                        <?php for($i=1; $i<=5; $i++) : ?>
                            <button type="button" class="star-btn p-1 group transform hover:scale-125 transition-all" data-value="<?php echo $i; ?>" aria-label="Avaliar com <?php echo $i; ?> estrelas">
                                <span class="material-symbols-outlined text-4xl text-slate-200 dark:text-slate-700 group-hover:text-amber-400 transition-colors pointer-events-none">star</span>
                            </button>
                        <?php endfor; ?>
                    </div>
                    
                    <div id="rating-info" class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Total de <span id="rating-current-count" class="text-primary"><?php echo $rating_count; ?></span> avaliações - Média <span id="rating-current-avg" class="text-primary"><?php echo $rating_avg; ?></span>
                    </div>

                    <!-- Mensagem de Sucesso (Oculta por padrão) -->
                    <div id="rating-success" class="hidden absolute inset-0 bg-white/95 dark:bg-slate-800/95 backdrop-blur flex flex-col items-center justify-center p-6 z-20">
                        <div class="size-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-emerald-600 text-3xl">check_circle</span>
                        </div>
                        <h3 class="text-xl font-bold mb-1">Obrigado por avaliar!</h3>
                        <p class="text-sm text-slate-500">Sua nota foi enviada com sucesso.</p>
                    </div>
                    
                    <input type="hidden" id="rating_post_id" value="<?php echo $post_id; ?>">
                </section>

                <!-- Comments/Reviews Placeholder -->
                <section class="mt-16 pt-12 border-t border-slate-200 dark:border-slate-800">
                    <?php if (comments_open() || get_comments_number()) comments_template(); ?>
                </section>

            </div>

            <!-- Sidebar Area -->
            <aside class="space-y-8">
                
                <!-- Author Box (Premium E-E-A-T Card) -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[32px] shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                    
                    <div class="flex items-center gap-5 mb-6 relative">
                        <div class="size-20 rounded-full overflow-hidden border-4 border-slate-50 dark:border-slate-700 shadow-md transform transition-transform group-hover:rotate-6">
                            <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-xl text-slate-900 dark:text-white leading-tight mb-1"><?php echo $author_name; ?></h4>
                            <p class="text-[10px] font-black text-primary uppercase tracking-[0.15em] leading-tight">
                                <?php echo nl2br(str_replace(' e ', "\ne ", $author_job)); ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($author_expertise) : ?>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-8 line-clamp-3">
                            <?php echo $author_expertise; ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-2 gap-3 pt-6 border-t border-slate-50 dark:border-slate-700/50">
                        <a href="<?php echo $author_url; ?>" class="flex items-center justify-center gap-2 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-primary hover:bg-primary/5 transition-all text-sm font-bold group/link">
                            <span class="material-symbols-outlined text-lg group-hover/link:scale-110 transition-transform">account_circle</span>
                            Perfil
                        </a>
                        <a href="<?php echo get_the_author_meta('user_url') ?: '#'; ?>" target="_blank" class="flex items-center justify-center gap-2 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-primary hover:bg-primary/5 transition-all text-sm font-bold group/link">
                            <span class="material-symbols-outlined text-lg group-hover/link:scale-110 transition-transform">language</span>
                            Web
                        </a>
                    </div>
                </div>

                <!-- Nutrition Section -->
                <?php if ($calorias || $carboidratos || $proteinas || $gorduras) : ?>
                <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10">
                    <h4 class="font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">nutrition</span>
                        Informação Nutricional
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b border-primary/10 pb-2">
                            <span class="text-slate-600 dark:text-slate-400">Calorias</span>
                            <span class="font-bold"><?php echo $calorias ?: '0'; ?> kcal</span>
                        </div>
                        <div class="flex justify-between border-b border-primary/10 pb-2">
                            <span class="text-slate-600 dark:text-slate-400">Proteínas</span>
                            <span class="font-bold"><?php echo $proteinas ?: '0'; ?>g</span>
                        </div>
                        <div class="flex justify-between border-b border-primary/10 pb-2">
                            <span class="text-slate-600 dark:text-slate-400">Gorduras</span>
                            <span class="font-bold"><?php echo $gorduras ?: '0'; ?>g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-400">Carbs</span>
                            <span class="font-bold"><?php echo $carboidratos ?: '0'; ?>g</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-4 leading-tight italic">* Valores estimados por porção.</p>
                </div>
                <?php endif; ?>

                <!-- Related Recipes -->
                <div>
                    <h4 class="font-bold mb-4 text-lg">Receitas que você vai Amar</h4>
                    <div class="space-y-4">
                        <?php
                        $related_query = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'post__not_in' => array($post_id),
                            'category__in' => wp_get_post_categories($post_id)
                        ));

                        if ($related_query->have_posts()) : while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                        <a class="group flex gap-3 items-center" href="<?php the_permalink(); ?>">
                            <div class="size-20 rounded-xl overflow-hidden flex-shrink-0">
                                <?php the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform']); ?>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm group-hover:text-primary transition-colors line-clamp-2"><?php the_title(); ?></h5>
                                <p class="text-xs text-slate-500"><?php echo get_post_meta(get_the_ID(), '_tempo_preparo', true) ?: '20'; ?> min • <?php echo get_post_meta(get_the_ID(), '_dificuldade', true) ?: 'Fácil'; ?></p>
                            </div>
                        </a>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </div>
                </div>

            </aside>

        </div>

        </article>
    </main>

    <!-- Seção Loja de Achadinhos (Full Width no final do post) -->
    <section class="bg-slate-50 dark:bg-slate-900/50 py-16 sm:py-24 border-y border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-4 block">Loja de Afiliados</span>
                    <h2 class="text-3xl sm:text-4xl font-black mb-6 leading-tight">Utensílios para sua <span class="text-primary italic">Cozinha</span></h2>
                    <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">Selecionamos os melhores produtos que usamos e recomendamos para preparar esta e outras receitas.</p>
                </div>
                <a href="<?php echo get_post_type_archive_link('achadinhos'); ?>" class="group flex items-center gap-2 font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                    Ver vitrine completa 
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 text-left">
                <?php
                $achadinhos_single = new WP_Query(array(
                    'post_type' => 'achadinhos',
                    'posts_per_page' => 8
                ));

                if ($achadinhos_single->have_posts()) : while ($achadinhos_single->have_posts()) : $achadinhos_single->the_post();
                    $link = get_post_meta(get_the_ID(), 'link_produto', true) ?: '#';
                    $preco = get_post_meta(get_the_ID(), 'valor_produto', true);
                ?>
                <div class="bg-white dark:bg-slate-800 rounded-[32px] p-4 border border-slate-100 dark:border-slate-700 hover:shadow-2xl transition-all group relative">
                    <div class="aspect-square rounded-[24px] overflow-hidden mb-5 bg-slate-50 dark:bg-slate-900">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500')); ?>
                        <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <span class="material-symbols-outlined text-4xl">shopping_basket</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="px-1 text-left">
                        <h3 class="text-sm font-bold mb-2 line-clamp-1 group-hover:text-primary transition-colors"><?php the_title(); ?></h3>
                        <div class="flex items-center justify-between mb-4">
                            <?php if ($preco) : ?>
                                <span class="text-base font-black text-slate-900 dark:text-white">R$ <?php echo $preco; ?></span>
                            <?php else : ?>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ver Preço</span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="nofollow noopener" class="w-full bg-slate-900 dark:bg-slate-700 hover:bg-primary text-white py-3 rounded-xl font-black text-[10px] text-center flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-primary/20">
                            COMPRAR <span class="material-symbols-outlined text-[10px]">open_in_new</span>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

<?php 
endwhile; endif;
get_footer(); 
?>