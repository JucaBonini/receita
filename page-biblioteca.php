<?php 
/**
 * Template Name: Biblioteca de E-books
 */
get_header(); ?>

<main class="bg-slate-50 dark:bg-slate-950 min-h-screen pt-12 pb-24">
    
    <header class="max-w-[1440px] mx-auto px-6 mb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic leading-none">BIBLIOTECA <span class="text-primary not-italic">DIGITAL</span></h1>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-2">Sua Coleção Exclusiva de Conteúdos e Guias</p>
            </div>
            <div class="flex gap-2">
                <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                    <span class="size-2 bg-emerald-500 rounded-full"></span>
                    <span class="text-[9px] font-black text-emerald-600 uppercase italic">Acesso Livre</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                    <span class="size-2 bg-amber-500 rounded-full"></span>
                    <span class="text-[9px] font-black text-amber-600 uppercase italic">Material Premium</span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-[1440px] mx-auto px-6">
        
        <?php 
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type'      => 'ebook',
            'posts_per_page' => 15,
            'paged'          => $paged
        );
        $ebook_query = new WP_Query($args);
        ?>

        <!-- Grid Senior Flexível: 1 col mobile, 4-5 desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8">
            
            <?php if ($ebook_query->have_posts()) : while ($ebook_query->have_posts()) : $ebook_query->the_post(); 
                $subtitle = get_post_meta(get_the_ID(), '_ebook_subtitle', true);
                $type = get_post_meta(get_the_ID(), '_ebook_type', true) ?: 'free';
                $price = get_post_meta(get_the_ID(), '_ebook_price', true);
                
                $border_class = ($type === 'paid') 
                    ? 'border-amber-500/20 hover:border-amber-500/50 hover:shadow-amber-500/10' 
                    : 'border-emerald-500/20 hover:border-emerald-500/50 hover:shadow-emerald-500/10';
                $badge_class = ($type === 'paid') ? 'bg-amber-500' : 'bg-emerald-500';
            ?>

            <article class="group bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border <?php echo $border_class; ?> shadow-sm hover:-translate-y-2 transition-all duration-500 flex flex-col">
                
                <div class="relative aspect-[3/4] overflow-hidden grayscale-[0.2] group-hover:grayscale-0 transition-all duration-500">
                    <?php if (has_post_thumbnail()) : the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110']); endif; ?>
                    
                    <!-- Selos de Alta Visibilidade (Acessibilidade + Design) -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2 pointer-events-none">
                        <?php if ($type === 'paid') : ?>
                            <div style="background-color: #f59e0b !important; color: #ffffff !important;" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] shadow-[0_4px_15px_rgba(245,158,11,0.5)] flex items-center gap-2 border border-white/20">
                                <span class="material-symbols-outlined text-[14px]">stars</span> PREMIUM
                            </div>
                        <?php else : ?>
                            <div style="background-color: #16a34a !important; color: #ffffff !important;" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] shadow-[0_4px_15px_rgba(22,163,74,0.4)] flex items-center gap-2 border border-white/20">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> GRÁTIS
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <h2 class="text-sm md:text-base font-black text-slate-800 dark:text-white uppercase tracking-tighter line-clamp-2 leading-[1.1] mb-2 group-hover:text-primary transition-colors">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <?php if ($subtitle) : ?>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest line-clamp-1 mb-6 opacity-60 italic"><?php echo $subtitle; ?></p>
                    <?php endif; ?>

                    <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex justify-between items-center">
                        <?php if ($type === 'paid') : ?>
                            <span class="text-sm font-black text-slate-900 dark:text-white tracking-widest">R$ <?php echo esc_html($price) ?: '9,90'; ?></span>
                        <?php else : ?>
                            <span class="text-sm font-black text-emerald-500 uppercase italic tracking-widest">Livre</span>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="size-8 bg-slate-900 dark:bg-slate-800 text-white rounded-lg flex items-center justify-center hover:bg-primary hover:scale-110 transition-all shadow-lg active:scale-95">
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </article>

            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>

        <nav class="mt-20">
            <?php 
                echo paginate_links(array(
                    'total'   => $ebook_query->max_num_pages,
                    'current' => $paged,
                    'type'    => 'list',
                    'class'   => 'pagi-compact'
                )); 
            ?>
        </nav>

    </div>
</main>

<style>
.pagi-compact ul { display: flex; justify-content: center; gap: 8px; }
.pagi-compact li * {
    width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
    border-radius: 10px; font-weight: 900; font-size: 10px;
    background: #fff; color: #94a3b8; border: 1px solid #f1f5f9; transition: all 0.3s;
}
.pagi-compact li .current { background: #0f172a; color: #fff; border-color: #0f172a; }
.dark .pagi-compact li * { background: #0f172a; border-color: #1e293b; color: #475569; }
.dark .pagi-compact li .current { background: #ef4444; color: #fff; border-color: transparent; }
</style>

<?php get_footer(); ?>
