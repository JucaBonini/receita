<?php
/**
 * Template Name: Loja de Indicações
 * Description: Página de vitrine completa com foco em acessibilidade e performance (CWV).
 */

get_header(); ?>

<main id="main-content" class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24">
    
    <!-- 🟢 SEÇÃO 1: CABEÇALHO (Isolado e com fundo limpo) -->
    <section class="relative pt-16 md:pt-24 pb-8 md:pb-12 bg-white dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            
            <h1 class="text-5xl md:text-8xl font-black text-slate-700 dark:text-white leading-[0.9] tracking-tighter mb-10">
                Loja de <span class="text-primary italic">Indicações</span>
            </h1>
            
            <p class="text-lg md:text-2xl text-slate-500 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto mb-16">
                Produtos e utensílios que <span class="text-slate-900 dark:text-white font-bold underline decoration-primary/40 decoration-4 underline-offset-4">eu confio e utilizo</span> no dia a dia.
            </p>

            <!-- Filtros de Marketplace -->
            <nav class="flex flex-wrap items-center justify-center gap-3 md:gap-5" aria-label="Filtrar por loja">
                <?php
                $current_mkt = isset($_GET['mkt']) ? sanitize_text_field($_GET['mkt']) : 'todos';
                $marketplaces = array(
                    'todos'         => '🎁 Ver Tudo',
                    'shopee'        => '🛍️ Shopee',
                    'amazon'        => '📦 Amazon',
                    'mercado_livre' => '🤝 M. Livre'
                );
                
                foreach ($marketplaces as $slug => $label) : 
                    $active = ($current_mkt === $slug);
                    $url = ($slug === 'todos') ? get_permalink() : add_query_arg('mkt', $slug, get_permalink());
                ?>
                    <a href="<?php echo esc_url($url); ?>" 
                       class="px-6 md:px-10 py-4 md:py-5 rounded-[22px] text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300
                              <?php echo $active 
                                ? 'bg-primary text-white shadow-2xl shadow-primary/40 scale-105' 
                                : 'bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-primary border border-slate-200 dark:border-slate-800'; ?>">
                        <?php echo $label; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

        </div>
    </section>

    <!-- 🟢 SEÇÃO 2: VITRINE DE PRODUTOS (Nova Seção, Nova Section) -->
    <section class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-6">
            
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type'      => 'sts_indicacoes',
                'posts_per_page' => 12,
                'paged'          => $paged
            );

            if ($current_mkt !== 'todos') {
                $args['meta_query'] = array(
                    array(
                        'key'     => '_sts_marketplace',
                        'value'   => $current_mkt,
                        'compare' => '='
                    )
                );
            }

            $loja_query = new WP_Query($args);

            if ($loja_query->have_posts()) : ?>
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-10">
                    <?php 
                    $count = 0;
                    while ($loja_query->have_posts()) : $loja_query->the_post(); 
                        $count++;
                        $url = get_post_meta(get_the_ID(), '_sts_product_url', true);
                        $price = get_post_meta(get_the_ID(), '_sts_product_price', true);
                        $mkt = get_post_meta(get_the_ID(), '_sts_marketplace', true);
                        
                        $count_lcp = ($count <= 4 && $paged === 1);

                        // Cores Blindadas via HEX (Garante que apareça independente do CSS)
                        $mkt_hex = '#0f172a'; // Slate-900 (Default)
                        $txt_hex = '#ffffff';
                        $btn_label = 'VER OFERTA';
                        
                        if ($mkt === 'shopee') {
                            $mkt_hex = '#D73211';
                            $btn_label = 'IR PARA SHOPEE';
                        } elseif ($mkt === 'amazon') {
                            $mkt_hex = '#FF9900';
                            $txt_hex = '#000000';
                            $btn_label = 'IR PARA AMAZON';
                        } elseif ($mkt === 'mercado_livre') {
                            $mkt_hex = '#FFE600';
                            $txt_hex = '#2d3277';
                            $btn_label = 'IR PARA M. LIVRE';
                        }
                    ?>
                    <article class="bg-white dark:bg-slate-800 rounded-[32px] md:rounded-[48px] p-4 md:p-6 shadow-[0_8px_40px_rgba(0,0,0,0.06)] border border-slate-100 dark:border-slate-700 flex flex-col group transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="aspect-square rounded-[24px] md:rounded-[36px] overflow-hidden bg-slate-50 dark:bg-slate-900 mb-6 md:mb-8 relative border border-slate-100 dark:border-slate-800">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', [
                                    'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110',
                                    'fetchpriority' => $count_lcp ? 'high' : 'low',
                                    'loading' => $count_lcp ? 'eager' : 'lazy'
                                ]); ?>
                            <?php endif; ?>
                            
                            <?php if ($price) : ?>
                                <div class="absolute bottom-4 right-4 bg-primary text-white px-4 py-2 rounded-[14px] text-[10px] md:text-sm font-black shadow-2xl">
                                    <?php echo $price; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h2 class="px-2 text-xs md:text-base font-black text-slate-700 dark:text-white uppercase tracking-tight line-clamp-2 mb-6 md:mb-8 h-10 md:h-12 leading-tight">
                            <?php the_title(); ?>
                        </h2>

                        <!-- Botão Blindado com Cor Fixa -->
                        <a href="<?php echo $url ? esc_url($url) : '#'; ?>" target="_blank" rel="noopener nofollow" 
                           style="background-color: <?php echo $mkt_hex; ?>; color: <?php echo $txt_hex; ?>;"
                           class="mt-auto w-full flex items-center justify-center gap-2 py-5 md:py-6 px-4 rounded-[22px] md:rounded-[32px] text-[10px] md:text-xs font-black uppercase tracking-widest transition-all hover:brightness-110 shadow-lg active:scale-95">
                            <span><?php echo $btn_label; ?></span>
                            <span class="material-symbols-outlined text-sm md:text-xl">shopping_cart</span>
                        </a>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Paginação -->
                <div class="mt-24 flex justify-center">
                    <?php
                    echo paginate_links(array(
                        'total'        => $loja_query->max_num_pages,
                        'current'      => $paged,
                        'format'       => '?paged=%#%',
                        'prev_text'    => '<span class="material-symbols-outlined">west</span>',
                        'next_text'    => '<span class="material-symbols-outlined">east</span>',
                        'type'         => 'plain',
                    ));
                    ?>
                </div>

                <!-- Schema SEO -->
                <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "CollectionPage",
                  "name": "Loja de Indicações da Mary",
                  "mainEntity": {
                    "@type": "ItemList",
                    "itemListElement": [
                      <?php 
                      $s_count = 0;
                      while ($loja_query->have_posts()) : $loja_query->the_post(); 
                        $s_count++;
                      ?>
                      {
                        "@type": "ListItem",
                        "position": <?php echo $s_count; ?>,
                        "name": "<?php the_title(); ?>"
                      }<?php echo ($s_count < $loja_query->post_count) ? ',' : ''; ?>
                      <?php endwhile; ?>
                    ]
                  }
                }
                </script>

            <?php else : ?>
                <div class="text-center py-32 bg-white dark:bg-slate-800 rounded-[48px] border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-7xl text-slate-200 mb-6">shopping_bag</span>
                    <p class="text-slate-400 font-black uppercase tracking-[0.2em]">Sua vitrine está pronta para receber indicações.</p>
                </div>
            <?php endif; wp_reset_postdata(); ?>

        </div>
    </section>

</main>

<style>
/* Paginação Customizada Professional */
.page-numbers { 
    @apply size-12 md:size-16 flex items-center justify-center rounded-[20px] bg-white dark:bg-slate-800 text-slate-400 font-black text-xs md:text-sm mx-1 transition-all shadow-sm border border-slate-100 dark:border-slate-700;
}
.page-numbers.current { 
    @apply bg-primary text-white shadow-xl shadow-primary/30 border-primary scale-110; 
}
.page-numbers:hover:not(.current) {
    @apply text-primary border-primary bg-primary/5;
}
</style>

<?php get_footer(); ?>
