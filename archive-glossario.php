<?php
/**
 * The template for displaying Alphabetical Glossary (FIXED & COLORED)
 */

get_header();

// 1. Data Collection
$terms_query = new WP_Query(array(
    'post_type' => 'glossario',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
));

$grouped_terms = array();
$all_alphabet = range('A', 'Z');

if ($terms_query->have_posts()) {
    while ($terms_query->have_posts()) {
        $terms_query->the_post();
        $title = get_the_title();
        $char = mb_strtoupper(mb_substr($title, 0, 1));
        $norm = iconv('UTF-8', 'ASCII//TRANSLIT', $char);
        if (!preg_match('/[A-Z]/', $norm)) $norm = '#';
        $grouped_terms[$norm][] = array(
            'id' => get_the_ID(),
            'title' => $title,
            'link' => get_permalink(),
            'excerpt' => get_the_excerpt(),
            'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'large') ?: (get_template_directory_uri() . '/assets/images/default-image.webp')
        );
    }
    wp_reset_postdata();
}
ksort($grouped_terms);
?>

<main class="bg-white dark:bg-slate-950 min-h-screen font-sans pb-40" role="main">
    
    <!-- 🟢 BREADCRUMBS & TITLE -->
    <div class="bg-white dark:bg-slate-900 pt-10 pb-6 border-b border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <nav aria-label="Breadcrumb" class="flex items-center justify-center space-x-2 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-4">
                <a href="<?php echo home_url(); ?>" class="hover:text-primary">PORTAL</a>
                <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                <span class="text-slate-900 dark:text-white">GLOSSÁRIO</span>
            </nav>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 dark:text-white text-center leading-none tracking-tight">Glossário</h1>
            <p class="text-slate-400 text-center text-sm font-bold mt-6 tracking-wide">Descubra o significado dos segredos da culinária.<br><?php echo $terms_query->found_posts; ?> termos catalogados.</p>
        </div>
    </div>

    <!-- 🟢 2. THE FIXED & COLORED ALPHABET BAR -->
    <nav id="alphabet-nav" aria-label="Navegação em Índice" 
         class="sticky top-0 z-[999] w-full bg-white/95 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 overflow-x-auto scrollbar-hide py-4">
            <div class="flex justify-center items-center gap-2 min-w-max px-4">
                <?php foreach ($all_alphabet as $letter) : 
                    $active = isset($grouped_terms[$letter]);
                ?>
                    <a href="<?php echo $active ? '#' . $letter : 'javascript:void(0)'; ?>" 
                       class="size-10 sm:size-11 flex items-center justify-center rounded-md font-black text-xs sm:text-sm border transition-all duration-300
                              <?php echo $active 
                               ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20 hover:scale-110' 
                               : 'bg-transparent text-slate-200 border-transparent pointer-events-none cursor-default dark:text-slate-800'; ?>">
                        <?php echo $letter; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- 🟢 3. CONTENT FEED -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-20">
        <div class="space-y-40">
            <?php foreach ($grouped_terms as $letter => $terms) : ?>
                <section id="<?php echo $letter; ?>" class="scroll-mt-32 group/letter">
                    
                    <!-- Letter Header -->
                    <div class="flex items-center gap-6 mb-12">
                        <div class="size-20 bg-white dark:bg-slate-900 text-6xl font-black text-primary flex items-center justify-center rounded-2xl shadow-xl border border-slate-50 dark:border-slate-800 group-hover/letter:scale-110 transition-transform duration-500">
                            <?php echo $letter; ?>
                        </div>
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></div>
                    </div>

                    <!-- Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        <?php foreach ($terms as $term) : ?>
                            <article class="bg-white dark:bg-slate-900 rounded-[40px] p-8 shadow-sm border border-slate-100 dark:border-slate-800/10 hover:shadow-3xl transition-all duration-500 group flex flex-col h-full">
                                
                                <div class="flex gap-6 items-start mb-8">
                                    <div class="size-24 rounded-3xl overflow-hidden bg-slate-50 dark:bg-slate-950 shrink-0 shadow-lg">
                                        <img src="<?php echo esc_url($term['thumb']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-125" loading="lazy" alt="<?php echo esc_attr($term['title']); ?>">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3 leading-tight group-hover:text-primary transition-colors">
                                            <a href="<?php echo esc_url($term['link']); ?>"><?php echo esc_html($term['title']); ?></a>
                                        </h3>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                            <?php echo esc_html($term['excerpt'] ?: 'Descubra a definição detalhada e aplicações práticas deste termo no nosso guia culinário exclusivo.'); ?>
                                        </p>
                                    </div>
                                </div>

                                <footer class="mt-auto pt-6 border-t border-slate-50 dark:border-slate-800/50 flex justify-between items-center">
                                    <a href="<?php echo esc_url($term['link']); ?>" class="text-[11px] font-black uppercase tracking-widest text-primary flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                                        VER DEFINIÇÃO
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                    <button onclick="navigator.share({ title: '<?php echo esc_attr($term['title']); ?>', url: '<?php echo esc_url($term['link']); ?>' })" class="p-2 text-slate-300 dark:text-slate-600 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">share</span>
                                    </button>
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php get_footer(); ?>
