<?php
/**
 * Seção de Recomendações Inteligentes - SEO God Mode
 * Foco: Transferência de Autoridade e Retenção de Usuário
 */

if (!isset($post_id)) $post_id = get_the_ID();

// 1. Definição das "Power Pages" (Suas receitas de elite para transferir autoridade)
$power_pages_slugs = [
    'peras-assadas-com-gorgonzola-e-mel',
    'como-fazer-caldo-cabeca-de-galo',
    'camarao-no-abacaxi',
    'cebola-em-conserva',
    'conserva-de-pimenta-cumari'
];

$categories = wp_get_post_categories($post_id);
if (empty($categories)) return;

// 2. Lógica de Seleção Inteligente
$recommendations_ids = [];

// Passo A: Tentar encontrar Power Pages na mesma categoria
$power_query = new WP_Query(array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'post_name__in'  => $power_pages_slugs,
    'category__in'   => $categories,
    'post__not_in'   => array($post_id),
    'posts_per_page' => 1
));

if ($power_query->have_posts()) {
    while ($power_query->have_posts()) {
        $power_query->the_post();
        $recommendations_ids[] = get_the_ID();
    }
    wp_reset_postdata();
}

// Passo B: Completar as 3 vagas com posts aleatórios da mesma categoria
$remaining_slots = 3 - count($recommendations_ids);
if ($remaining_slots > 0) {
    $random_query = new WP_Query(array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'category__in'   => $categories,
        'post__not_in'   => array_merge(array($post_id), $recommendations_ids),
        'posts_per_page' => $remaining_slots,
        'orderby'        => 'rand'
    ));

    if ($random_query->have_posts()) {
        while ($random_query->have_posts()) {
            $random_query->the_post();
            $recommendations_ids[] = get_the_ID();
        }
        wp_reset_postdata();
    }
}

// 3. Renderização Final
if (!empty($recommendations_ids)) :
    $recommendations = new WP_Query(array(
        'post_type' => 'post',
        'post__in' => $recommendations_ids,
        'orderby' => 'post__in'
    ));
?>
<section class="smart-recommendations mt-16 mb-12 py-12 border-t border-slate-100 dark:border-slate-800">
    <div class="flex flex-col md:flex-row items-center justify-between mb-10 gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="size-2 bg-primary rounded-full animate-ping"></span>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">Sugestão da Chef Mary</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white italic">Você também vai <span class="text-primary">Amar...</span></h3>
        </div>
        <div class="hidden md:block">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Baseado no seu gosto</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while ($recommendations->have_posts()) : $recommendations->the_post(); 
            $r_id = get_the_ID();
            $r_slug = get_post_field('post_name', $r_id);
            $is_power = in_array($r_slug, $power_pages_slugs);
            $time = sts_get_recipe_total_time($r_id) ?: '25 min';
        ?>
        <a href="<?php the_permalink(); ?>" class="group relative bg-white dark:bg-slate-800 rounded-[32px] overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col">
            
            <div class="aspect-[4/3] overflow-hidden relative">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110']); ?>
                <?php endif; ?>
                
                <?php if ($is_power) : ?>
                    <div class="absolute top-4 left-4 px-3 py-1 bg-amber-400 text-amber-900 text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg border border-white/20 backdrop-blur-sm z-10">
                        ⭐ Favorita do Público
                    </div>
                <?php else : ?>
                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/80 dark:bg-slate-900/80 text-slate-600 dark:text-slate-300 text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg border border-white/20 backdrop-blur-sm z-10">
                        🔥 Em Alta
                    </div>
                <?php endif; ?>

                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center gap-3 mb-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> <?php echo $time; ?></span>
                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                    <span class="flex items-center gap-1 text-primary"><span class="material-symbols-outlined text-sm">local_fire_department</span> Sucesso</span>
                </div>

                <h4 class="text-lg font-black text-slate-800 dark:text-white leading-tight mb-4 group-hover:text-primary transition-colors line-clamp-2">
                    <?php the_title(); ?>
                </h4>

                <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-700 flex items-center justify-between">
                    <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Ver Receita</span>
                    <div class="size-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </div>
            </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>
