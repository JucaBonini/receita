<?php
/**
 * Template Name: Loja de Indicações
 * Description: Página de vitrine completa com foco em acessibilidade e performance (CWV).
 */

get_header(); ?>

<main id="main-content" class="min-h-screen bg-slate-50 dark:bg-slate-900 pt-10 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Premium Header Area -->
        <header class="mb-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-widest mb-6 border border-primary/20">
                <span class="material-symbols-outlined text-sm">verified</span>
                Curadoria Verificada pela Mary
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white leading-none mb-6">
                Loja de <span class="text-primary italic">Indicações</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                Encontre aqui todos os utensílios, acessórios e ingredientes que confio e utilizo no meu dia a dia na cozinha.
            </p>
        </header>

        <!-- Filtros Rápidos (Acessibilidade & UX) -->
        <?php
        $current_mkt = isset($_GET['mkt']) ? sanitize_text_field($_GET['mkt']) : 'todos';
        $marketplaces = array(
            'todos'         => 'Ver Tudo',
            'shopee'        => 'Shopee',
            'amazon'        => 'Amazon',
            'mercado_livre' => 'M. Livre'
        );
        ?>
        <nav class="flex flex-wrap items-center justify-center gap-3 mb-12" aria-label="Filtrar por loja">
            <?php foreach ($marketplaces as $slug => $label) : 
                $active = ($current_mkt === $slug);
                $url = ($slug === 'todos') ? get_permalink() : add_query_arg('mkt', $slug, get_permalink());
            ?>
                <a href="<?php echo esc_url($url); ?>" 
                   class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all
                          <?php echo $active 
                            ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-105' 
                            : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-100 dark:border-slate-700'; ?>">
                    <?php echo $label; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Vitrine Grid -->
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
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                <?php 
                $count = 0;
                while ($loja_query->have_posts()) : $loja_query->the_post(); 
                    $count++;
                    $url = get_post_meta(get_the_ID(), '_sts_product_url', true);
                    $price = get_post_meta(get_the_ID(), '_sts_product_price', true);
                    $mkt = get_post_meta(get_the_ID(), '_sts_marketplace', true);
                    
                    // Optimizações CWV: Prioridade de carregamento para os primeiros itens
                    $priority = ($count <= 4 && $paged === 1) ? 'high' : 'low';
                    $loading = ($count <= 4 && $paged === 1) ? 'eager' : 'lazy';

                    // Acessibilidade Cromática & Labels
                    $btn_config = array(
                        'shopee' => ['bg' => 'bg-[#D73211]', 'text' => 'text-white', 'label' => 'Na Shopee'], // Laranja escuro para AA
                        'amazon' => ['bg' => 'bg-[#FF9900]', 'text' => 'text-black', 'label' => 'Na Amazon'],
                        'mercado_livre' => ['bg' => 'bg-[#FFE600]', 'text' => 'text-[#2d3277]', 'label' => 'No ML'],
                        'default' => ['bg' => 'bg-slate-900', 'text' => 'text-white', 'label' => 'Ver Oferta']
                    );
                    $config = $btn_config[$mkt] ?? $btn_config['default'];
                ?>
                <article class="bg-white dark:bg-slate-800 rounded-[30px] md:rounded-[40px] p-3 md:p-5 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col group transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="aspect-square rounded-[20px] md:rounded-[30px] overflow-hidden bg-slate-100 dark:bg-slate-900 mb-4 md:mb-6 relative">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', [
                                'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700',
                                'fetchpriority' => $priority,
                                'loading' => $loading
                            ]); ?>
                        <?php endif; ?>
                        
                        <?php if ($price) : ?>
                            <div class="absolute bottom-3 right-3 bg-primary text-white p-2 md:px-4 md:py-2 rounded-xl text-[10px] md:text-xs font-black shadow-xl">
                                <?php echo $price; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h2 class="px-1 text-[11px] md:text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight line-clamp-2 mb-4 md:mb-8 h-8 md:h-10 leading-tight">
                        <?php the_title(); ?>
                    </h2>

                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener nofollow" 
                       class="mt-auto w-full flex items-center justify-center gap-2 py-4 md:py-5 px-3 <?php echo $config['bg']; ?> <?php echo $config['text']; ?> rounded-[18px] md:rounded-[24px] text-[9px] md:text-xs font-black uppercase tracking-widest transition-all hover:brightness-110">
                        <?php echo $config['label']; ?>
                        <span class="material-symbols-outlined text-sm md:text-lg">shopping_bag</span>
                    </a>
                </article>
                <?php endwhile; ?>
            </div>

            <!-- Paginação Premium -->
            <div class="mt-20 flex justify-center">
                <?php
                echo paginate_links(array(
                    'total'        => $loja_query->max_num_pages,
                    'current'      => $paged,
                    'format'       => '?paged=%#%',
                    'show_all'     => false,
                    'type'         => 'plain',
                    'prev_next'    => true,
                    'prev_text'    => '<span class="material-symbols-outlined">west</span>',
                    'next_text'    => '<span class="material-symbols-outlined">east</span>',
                    'class'        => 'flex items-center gap-4'
                ));
                ?>
            </div>

            <!-- Schema JSON-LD (SEO & Rich Results) -->
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "CollectionPage",
              "name": "Loja de Indicações da Mary",
              "description": "Lista de produtos e utensílios de cozinha recomendados por Mary.",
              "url": "<?php the_permalink(); ?>",
              "mainEntity": {
                "@type": "ItemList",
                "itemListElement": [
                  <?php 
                  $schema_count = 0;
                  while ($loja_query->have_posts()) : $loja_query->the_post(); 
                    $schema_count++;
                  ?>
                  {
                    "@type": "ListItem",
                    "position": <?php echo $schema_count; ?>,
                    "name": "<?php the_title(); ?>",
                    "url": "<?php echo get_post_meta(get_the_ID(), '_sts_product_url', true); ?>"
                  }<?php echo ($schema_count < $loja_query->post_count) ? ',' : ''; ?>
                  <?php endwhile; ?>
                ]
              }
            }
            </script>

        <?php else : ?>
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-[40px] border-2 border-dashed border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-6xl text-slate-200 mb-4">inventory_2</span>
                <p class="text-slate-500 font-bold uppercase tracking-widest">Nenhuma indicação cadastrada para este filtro.</p>
            </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</main>

<style>
/* Paginação Styles */
.pagination { @apply flex items-center justify-center gap-2; }
.pagination .page-numbers { 
    @apply size-10 md:size-12 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-slate-400 font-black text-xs hover:bg-primary hover:text-white transition-all shadow-sm;
}
.pagination .page-numbers.current { 
    @apply bg-primary text-white shadow-lg shadow-primary/20; 
}
</style>

<?php get_footer(); ?>
