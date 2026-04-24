<?php
/**
 * The template for displaying all single posts (Tailwind Premium 2026)
 */
get_header();

if (have_posts()) : while (have_posts()) : the_post();
    $post_id = get_the_ID();
    sts_set_post_views($post_id); // Contabilizar visualização

    // 1. Coleta de Dados (Independência do ACF - Sincronização 2026)
    $tempo_preparo    = get_post_meta($post_id, '_tempo_preparo', true) ?: get_post_meta($post_id, 'tempo', true); 
    $tempo_cozimento  = get_post_meta($post_id, '_tempo_cozimento', true) ?: get_post_meta($post_id, 'tempo_cozimento', true);
    $porcoes_meta     = get_post_meta($post_id, '_porcoes', true) ?: get_post_meta($post_id, 'rendimento', true);
    $dificuldade      = get_post_meta($post_id, '_dificuldade', true) ?: get_post_meta($post_id, 'dificuldade', true);
    $cuisine_meta     = get_post_meta($post_id, '_recipe_cuisine', true) ?: (get_post_meta($post_id, 'recipe_cuisine', true) ?: 'Brasileira'); 

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
    
    // Caminho do Meio: Se for 0, usamos a nota do autor (5.0 com 1 voto)
    $display_rating_avg   = $rating_count > 0 ? round($rating_total / $rating_count, 1) : 5.0;
    $display_rating_count = $rating_count > 0 ? $rating_count : 1;

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
        <div class="mb-10">
            <div class="mb-4 breadcrumb-container text-xs sm:text-sm">
                <?php echo custom_breadcrumb(); ?>
            </div>
            <style>
                @media (max-width: 640px) {
                    .breadcrumb-container a:last-of-type, 
                    .breadcrumb-container span:last-child {
                        display: none !important;
                    }
                    /* Remove o separador final se sobrar */
                    .breadcrumb-container span.sep:last-of-type {
                        display: none !important;
                    }
                }
            </style>

            <!-- ⚡ ADS MASTER: Premium Above Title (Faturamento Máximo) -->
            <?php if (function_exists('sts_render_ad')) sts_render_ad('single_above_title', 'my-4'); ?>
            
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 mb-6">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 dark:text-slate-100 leading-tight">
                    <?php the_title(); ?>
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-y-3 gap-x-6 text-sm text-slate-600 dark:text-slate-400">
                <div class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 rounded-full">
                    <span class="material-symbols-outlined text-xl">star</span>
                    <span class="font-bold"><?php echo $display_rating_avg; ?></span>
                    <span class="text-xs opacity-75">(<?php echo $display_rating_count; ?>)</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-slate-400 text-xl">schedule</span>
                    <span><?php the_modified_date('d/m/Y'); ?></span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-full text-xs font-bold border border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                    <span><?php echo get_post_views($post_id); ?></span>
                </div>
            </div>
        </div>

        <div class="aspect-video w-full rounded-[32px] overflow-hidden mb-10 shadow-2xl relative group bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-800">
            <?php 
            if (has_post_thumbnail()) {
                $thumb_id = get_post_thumbnail_id();
                $alt_text = get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                the_post_thumbnail('full', [
                    'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105',
                    'loading' => 'eager', 
                    'fetchpriority' => 'high',
                    'data-no-lazy' => '1',
                    'decoding' => 'sync',
                    'alt' => esc_attr($alt_text)
                ]); 
            } else {
                // Imagem de Fallback quando não há destaque (SEO & Google Discover)
                $default_image = get_template_directory_uri() . '/assets/images/default-image.webp';
                echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="eager" decoding="sync" fetchpriority="high" data-no-lazy="1">';
            }
            ?>
            
            <!-- Botão de Favorito Premium sobre a Imagem -->
            <button id="single-fav-btn" 
                    data-post-id="<?php the_ID(); ?>" 
                    class="btn-favorite absolute top-4 right-4 md:top-6 md:right-6 size-12 md:size-14 bg-white/60 dark:bg-slate-900/60 backdrop-blur-xl border border-white/20 dark:border-slate-700/50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-red-510 hover:bg-white dark:hover:bg-slate-900 shadow-xl transition-all duration-300 group/fav z-20 active:scale-90"
                    aria-label="Adicionar aos favoritos">
                <span class="material-symbols-outlined text-2xl md:text-3xl transition-all group-hover/fav:scale-110">favorite</span>
            </button>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        </div>

        <!-- Metadata Cards Grid -->
        <div class="grid grid-cols-2 min-[400px]:grid-cols-3 md:grid-cols-5 gap-2 sm:gap-4 mb-12">
            <div class="bg-white dark:bg-slate-800 p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">timer</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Preparo</span>
                <span class="text-sm sm:text-xl font-bold"><?php echo $tempo_preparo ?: '20'; ?>m</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">cooking</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Cozimento</span>
                <span class="text-sm sm:text-xl font-bold"><?php echo $tempo_cozimento ?: '30'; ?>m</span>
            </div>
            <div class="bg-primary/20 border-2 border-primary/40 p-3 sm:p-5 rounded-2xl flex flex-col items-center text-center shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-primary mb-2">schedule</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-primary font-black mb-1">Total</span>
                <span class="text-base sm:text-2xl font-black text-primary italic"><?php echo sts_get_recipe_total_time($post_id); ?></span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">restaurant</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Rende</span>
                <span class="text-sm sm:text-xl font-bold"><?php echo trim($porcoes_meta ?: '4'); ?></span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-primary mb-2">bar_chart</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Nível</span>
                <span class="text-sm sm:text-xl font-bold"><?php echo $dificuldade ?: 'Fácil'; ?></span>
            </div>
            <!-- Botão Salvar em PDF (4º Card) -->
            <button onclick="window.print()" class="bg-slate-50 dark:bg-slate-800/10 hover:bg-primary text-slate-900 dark:text-white hover:text-white p-3 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col items-center text-center transition-all group">
                <span class="material-symbols-outlined mb-2 group-hover:scale-110 transition-transform">picture_as_pdf</span>
                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider font-bold mb-1">Salvar</span>
                <span class="text-sm sm:text-xl font-bold">PDF</span>
            </button>
        </div>

        <!-- Video Section (If exists) -->
        <?php if (!empty($video_url)) : ?>
            <div class="mb-12 aspect-video rounded-[32px] overflow-hidden shadow-2xl bg-black">
                <?php 
                $embed_url = str_contains($video_url, 'youtube.com') ? str_replace('watch?v=', 'embed/', $video_url) : $video_url;
                ?>
                <iframe class="w-full h-full" src="<?php echo esc_url($embed_url); ?>" title="Vídeo da Receita: <?php echo esc_attr(get_the_title()); ?>" frameborder="0" allowfullscreen loading="lazy"></iframe>
            </div>
        <?php endif; ?>

        <!-- Content Structure -->
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- Main Content Area -->
            <div class="lg:col-span-2">
                
                <!-- Navigation Index (Pílulas com Ícones - Inteligente) -->
                <?php sts_render_recipe_pill_toc(); ?>

                <!-- AEO/GEO: Bloco de Autoridade para IAs (SearchGPT, Perplexity, Gemini) -->
                <div class="aeo-summary mb-10 p-6 sm:p-8 bg-primary/5 rounded-[32px] border border-primary/10 relative overflow-hidden group">
                    <div class="absolute -top-6 -right-6 size-24 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all"></div>
                    <div class="flex items-start gap-4">
                        <div class="size-10 rounded-xl bg-primary text-white flex items-center justify-center shrink-0 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-2xl font-light">tips_and_updates</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-primary mb-2">Resumo da Chef</h2>
                            <p class="text-lg sm:text-xl text-slate-800 dark:text-slate-200 font-medium leading-relaxed italic">
                                <?php 
                                // Otimizado para AEO/GEO: Resposta direta e curta para motores de IA
                                $aeo_summary = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25);
                                echo esc_html($aeo_summary);
                                ?>
                            </p>
                            <div class="mt-4 flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest bg-white/50 dark:bg-slate-900/50 w-fit px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-800">
                                <span class="material-symbols-outlined text-xs">verified</span> Garantia de Sucesso STS
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intro/Content -->
                <div class="prose sm:prose-xl dark:prose-invert max-w-none text-lg leading-relaxed space-y-8 prose-headings:text-primary prose-a:text-primary mb-12 selection:bg-primary/20">
                    <?php the_content(); ?>
                </div>

                <!-- Utensílios (Destaque) -->
                <?php if (!empty($utensilios)) : ?>
                <section class="mb-12 bg-slate-100 dark:bg-slate-800/50 p-6 rounded-2xl border-l-4 border-primary">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary">skillet</span>
                        <h2 class="text-xl font-bold italic uppercase tracking-wider text-slate-800 dark:text-slate-100">Utensílios Necessários</h2>
                    </div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach($utensilios as $u) : ?>
                            <li class="flex items-center gap-2 text-sm"><span class="w-1.5 h-1.5 bg-primary rounded-full"></span> <?php echo esc_html($u); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
                <?php endif; ?>

                <!-- Ad: Antes dos Ingredientes (Nativo) -->
                <?php if(function_exists('sts_show_ad_slot')) sts_show_ad_slot('ad_single_before_ingredients'); ?>

                <!-- Ingredients Section -->
                <section class="mb-12" id="ingredients">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">shopping_basket</span>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Ingredientes</h2>
                        </div>
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
                    <div class="flex items-center justify-between gap-4 mb-10">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-3xl">restaurant</span>
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Modo de Preparo</h2>
                        </div>
                        <button id="start-cooking-mode" class="flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full font-bold text-xs hover:bg-primary hover:text-white transition-all group">
                            <span class="material-symbols-outlined text-lg group-hover:animate-pulse">kitchen</span>
                            Modo Cozinha
                        </button>
                    </div>
                    <div class="space-y-10 relative before:absolute before:left-5 before:top-2 before:bottom-2 before:w-0.5 before:bg-primary/10 mb-16">
                        <?php 
                        if (is_array($instrucoes_raw)) : foreach($instrucoes_raw as $i => $passo) : if(trim($passo)) :
                        ?>
                        <div id="step-<?php echo $i + 1; ?>" class="relative pl-12 group scroll-mt-32">
                            <div class="absolute left-0 top-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform z-10"><?php echo $i + 1; ?></div>
                            <h3 class="text-lg font-bold mb-3 text-slate-800 dark:text-slate-200">Passo <?php echo $i + 1; ?></h3>
                            <div class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">
                                <?php echo nl2br(esc_html($passo)); ?>
                            </div>
                        </div>
                        <?php endif; endforeach; endif; ?>
                    </div>

                    <!-- E-E-A-T Mastery: Bloco de Autoridade da Mary Rodrigues -->
                    <div class="author-box-sts p-8 sm:p-10 bg-slate-50 dark:bg-slate-800/20 border border-slate-100 dark:border-slate-800 rounded-[40px] flex flex-col sm:flex-row items-center sm:items-start gap-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <span class="material-symbols-outlined text-8xl text-primary transform -rotate-12">restaurant_menu</span>
                        </div>
                        
                        <div class="size-32 rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-slate-900 shrink-0 transform group-hover:scale-105 transition-transform duration-500">
                             <img src="<?php echo get_avatar_url($author_id, ['size' => 120]); ?>" alt="<?php echo esc_attr($author_name); ?>" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        
                        <div class="flex-1 text-center sm:text-left relative z-10">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter"><?php echo $author_name; ?></h3>
                                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full border border-primary/20 w-fit mx-auto sm:mx-0">Especialista com +20 anos de Experiência</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed italic mb-6">
                                "<?php echo $author_name; ?> é a mente por trás do Descomplicando Receitas. Com mais de 20 anos de experiência dedicados ao domínio da culinária prática, sua missão é democratizar a gastronomia, provando que qualquer pessoa pode preparar refeições incríveis com ingredientes simples e técnicas infalíveis."
                            </p>
                            <div class="flex items-center justify-center sm:justify-start gap-4">
                                <a href="#" class="size-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:text-primary shadow-sm hover:shadow-xl transition-all"><i class="fa-brands fa-instagram text-xl"></i></a>
                                <a href="#" class="size-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:text-primary shadow-sm hover:shadow-xl transition-all"><i class="fa-brands fa-pinterest text-xl"></i></a>
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="ml-auto text-xs font-black uppercase tracking-widest text-primary flex items-center gap-1 hover:underline">VER TODAS AS RECEITAS <span class="material-symbols-outlined text-sm">arrow_right_alt</span></a>
                            </div>
                        </div>
                    </div>
                </section>

                <?php 
                // ⚡ ADS MASTER: Abaixo da Bio Autor (Momento de Confiança)
                // Anúncio movido para o rodapé entre vitrines

                // ⚡ ADS MASTER: Final da Receita (Encerramento)
                // Anúncio movido para baixo do widget de avaliação
                ?>

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
                        Total de <span id="rating-current-count" class="text-primary"><?php echo $display_rating_count; ?></span> <?php echo $display_rating_count > 1 ? 'avaliações' : 'avaliação'; ?> - Média <span id="rating-current-avg" class="text-primary"><?php echo $display_rating_avg; ?></span>
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

                <?php 
                // ⚡ ADS MASTER: Final da Receita (Momento de Saída/Conversão)
                if (function_exists('sts_render_ad')) sts_render_ad('single_after_recipe'); 
                ?>

                <!-- Vitrine de Afiliados removida deste ponto para evitar redundância (Foco na Vitrine de Rodapé 2.5.0) -->

                <!-- 🟢 Social Sharing (Engagement Boost) -->
                <div class="mb-12 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-slate-400 mb-6 flex items-center gap-4">
                        Gostou da receita? Compartilhe:
                        <span class="flex-1 h-px bg-slate-50 dark:bg-slate-800/10"></span>
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <?php 
                        $share_url = urlencode(get_permalink());
                        $share_title = urlencode(get_the_title());
                        ?>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="flex items-center gap-2 px-4 py-3 bg-[#1877F2] text-white rounded-2xl hover:bg-[#1877F2]/90 transition-all font-bold text-xs shadow-lg shadow-[#1877F2]/20 transform active:scale-95">
                            <span class="material-symbols-outlined text-lg">share</span>
                            FB
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="flex items-center gap-2 px-4 py-3 bg-[#1DA1F2] text-white rounded-2xl hover:bg-[#1DA1F2]/90 transition-all font-bold text-xs shadow-lg shadow-[#1DA1F2]/20 transform active:scale-95">
                            <span class="material-symbols-outlined text-lg">post_add</span>
                            X
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" target="_blank" class="flex items-center gap-2 px-4 py-3 bg-[#25D366] text-white rounded-2xl hover:bg-[#25D366]/90 transition-all font-bold text-xs shadow-lg shadow-[#25D366]/20 transform active:scale-95">
                            <span class="material-symbols-outlined text-lg">chat</span>
                            Whats
                        </a>
                        <button onclick="navigator.clipboard.writeText('<?php the_permalink(); ?>'); alert('Link da receita copiado!');" class="flex items-center gap-2 px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all font-bold text-xs transform active:scale-95">
                            <span class="material-symbols-outlined text-lg">link</span>
                            Copiar
                        </button>
                    </div>
                </div>

                <!-- 🚀 SEO GOD MODE: Recomendações Inteligentes movidas para fora do container principal para largura total -->

                <!-- Comments/Reviews Placeholder -->
                <section class="mt-8 pt-12 border-t border-slate-200 dark:border-slate-800">
                    <?php if (comments_open() || get_comments_number()) comments_template(); ?>
                </section>

            </div>

            <!-- Sidebar Area -->
            <aside class="space-y-8">
                <!-- Ad: Sidebar Sticky (Nativo) -->
                <div class="sticky top-24">
                    <?php if(function_exists('sts_show_ad_slot')) sts_show_ad_slot('ad_sidebar_sticky'); ?>
                </div>

                <!-- Author Box (Ultra-Premium E-E-A-T Card) -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[40px] shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                    <!-- Badge de Autoridade (Flutuante) -->
                    <div class="absolute -top-1 -right-1 bg-primary text-white text-[9px] font-black uppercase tracking-widest py-2 px-6 rotate-12 shadow-lg shadow-primary/20">
                        CHEF VERIFICADA
                    </div>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="relative">
                            <div class="size-20 rounded-full overflow-hidden border-4 border-slate-50 dark:border-slate-700 shadow-xl transform transition-transform group-hover:scale-105">
                                <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="w-full h-full object-cover" loading="lazy">
                            </div>
                            <!-- Icone de Check de Autoridade -->
                            <div class="absolute -bottom-1 -right-1 size-7 bg-primary rounded-full border-4 border-white dark:border-slate-800 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-xs">verified</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-xl text-slate-900 dark:text-white leading-tight mb-1"><?php echo $author_name; ?></h4>
                            <div class="flex flex-col">
                                <p class="text-[10px] font-black text-primary uppercase tracking-[0.15em] leading-tight">
                                    Curadoria Especialista
                                </p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                    <?php echo $author_job ?: 'Chef Executiva'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl mb-8 border border-slate-100 dark:border-slate-800">
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed italic">
                            "Minha missão é descomplicar a cozinha para que você tenha mais prazer no seu dia a dia."
                        </p>
                    </div>

                    <?php if ($author_expertise) : ?>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-8 line-clamp-4">
                            <?php echo $author_expertise; ?>
                        </p>
                    <?php endif; ?>
                    
                    <!-- Selos de Confiança -->
                    <div class="flex items-center gap-3 mb-8">
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700"></div>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Garantia de Qualidade</span>
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-8">
                        <div class="flex flex-col items-center p-3 rounded-2xl bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20 text-center">
                            <span class="material-symbols-outlined text-amber-500 text-xl mb-1">check_circle</span>
                            <span class="text-[8px] font-black text-amber-700 dark:text-amber-400 uppercase">Receita Testada</span>
                        </div>
                        <div class="flex flex-col items-center p-3 rounded-2xl bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/20 text-center">
                            <span class="material-symbols-outlined text-emerald-500 text-xl mb-1">verified_user</span>
                            <span class="text-[8px] font-black text-emerald-700 dark:text-emerald-400 uppercase">100% Autoral</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?php echo $author_url; ?>" class="flex items-center justify-center gap-2 py-4 rounded-2xl bg-slate-900 text-white hover:bg-primary transition-all text-[10px] font-black uppercase tracking-widest group/link shadow-lg shadow-slate-900/10">
                            Perfil Oficial
                        </a>
                        <a href="<?php echo $author_url; ?>#contato" class="flex items-center justify-center gap-2 py-4 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-primary hover:border-primary transition-all text-[10px] font-black uppercase tracking-widest">
                            Falar com a Mary
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

                <!-- Seção repetida removida para evitar redundância com a Sugestão da Chef Mary -->

            </aside>

        </div>

        <div class="print-footer hidden print:block">
            Receita retirada de: <?php echo get_bloginfo('name'); ?> - <?php the_permalink(); ?>
        </div>

        </article>
    </main>

    <section class="bg-slate-50 dark:bg-slate-900/50 py-16 sm:py-24 border-y border-slate-100 dark:border-slate-800 w-full overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-10 lg:px-12">
            <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-4 block">Loja de Indicações</span>
                    <h2 class="text-3xl sm:text-4xl font-black mb-6 leading-tight">Utensílios para sua <span class="text-primary italic">Cozinha</span></h2>
                    <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">Equipamentos e acessórios que a Chef Mary utiliza e recomenda para garantir o melhor resultado nas suas receitas.</p>
                </div>
                <a href="<?php echo home_url('/loja'); ?>" class="group flex items-center gap-2 font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                    Ver vitrine completa 
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                </a>
            </div>

            <div class="grid grid-cols-1 min-[420px]:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 sm:gap-8 text-left">
                <?php
                $indicacoes_footer_query = new WP_Query(array(
                    'post_type'      => 'sts_indicacoes',
                    'posts_per_page' => 12,
                    'orderby'        => 'rand'
                ));

                if ($indicacoes_footer_query->have_posts()) : while ($indicacoes_footer_query->have_posts()) : $indicacoes_footer_query->the_post();
                    $link = get_post_meta(get_the_ID(), '_sts_product_url', true) ?: '#';
                    $preco = get_post_meta(get_the_ID(), '_sts_product_price', true);
                    $marketplace = get_post_meta(get_the_ID(), '_sts_marketplace', true);
                ?>
                <div class="bg-white dark:bg-slate-800 rounded-[32px] p-4 border border-slate-100 dark:border-slate-700 hover:shadow-2xl transition-all group relative">
                    <div class="aspect-square rounded-[24px] overflow-hidden mb-5 bg-slate-50 dark:bg-slate-900">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500', 'loading' => 'lazy', 'alt' => get_the_title())); ?>
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
                                <span class="text-base font-black text-slate-900 dark:text-white"><?php echo $preco; ?></span>
                            <?php else : ?>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ver Preço</span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="nofollow noopener" class="w-full bg-slate-900 dark:bg-slate-700 hover:bg-primary text-white py-3 rounded-xl font-black text-[10px] text-center flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-primary/20">
                            VER OFERTA <span class="material-symbols-outlined text-[10px]">open_in_new</span>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <?php 
    // ⚡ ADS MASTER: Divisor de Vitrines (Ponto de Alta Conversão)
    if (function_exists('sts_render_ad')) sts_render_ad('single_top_author'); 
    ?>

    <!-- 🚀 SEO GOD MODE: Recomendações Inteligentes (Largura Total) -->
    <?php get_template_part('template-parts/smart-recommendations'); ?>

    <?php 
    // Floating Rating Bar (Scroll Triggered)
    get_template_part('template-parts/floating-rating');
endwhile; endif;
get_footer(); 
?>