<?php
/**
 * Template Name: Visualização do Cardápio Semanal (App Style)
 * Template Post Type: sts_cardapio
 */

get_header();

$cardapio_data = get_post_meta(get_the_ID(), '_sts_cardapio_data', true) ?: array();
$dias_labels = array(
    'segunda' => 'Segunda',
    'terca'   => 'Terça',
    'quarta'  => 'Quarta',
    'quinta'  => 'Quinta',
    'sexta'   => 'Sexta',
    'sabado'  => 'Sábado',
    'domingo' => 'Domingo'
);

// Coletar IDs de todas as receitas para a lista de compras
$all_recipe_ids = array();
foreach ($cardapio_data as $dia) {
    foreach ($dia as $recipe_id) {
        if ($recipe_id) $all_recipe_ids[] = $recipe_id;
    }
}
$all_recipe_ids = array_unique($all_recipe_ids);
?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pb-24">
    
    <!-- App Header -->
    <header class="sticky top-[72px] z-40 w-full bg-primary text-white shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-dark/50 to-transparent pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-6 py-8 relative">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Planejador Semanal 2026</span>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="size-8 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all text-sm">
                        <span class="material-symbols-outlined text-base">print</span>
                    </button>
                    <button onclick="navigator.share({title: '<?php the_title(); ?>', url: window.location.href})" class="size-8 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all text-sm">
                        <span class="material-symbols-outlined text-base">share</span>
                    </button>
                </div>
            </div>
            <h1 class="text-3xl font-black leading-tight mb-2"><?php the_title(); ?></h1>
            <p class="text-white/80 text-sm italic"><?php echo get_the_excerpt(); ?></p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Grid de Dias -->
        <div class="space-y-6" id="meal-plan-content">
            <?php foreach ($dias_labels as $key => $label) : 
                $day_meals = isset($cardapio_data[$key]) ? $cardapio_data[$key] : array();
                $is_today = (strtolower(date_i18n('l')) == $key);
            ?>
                <section class="day-card animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="size-12 rounded-2xl <?php echo $is_today ? 'bg-primary text-white' : 'bg-white dark:bg-slate-800 text-slate-400'; ?> shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center justify-center transition-all">
                            <span class="text-[9px] font-black uppercase tracking-tighter"><?php echo substr($label, 0, 3); ?></span>
                            <span class="text-lg font-black leading-none"><?php echo date_i18n('d'); // Placeholder para data real caso queira simular ?></span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-xs"><?php echo $label; ?></h3>
                            <div class="flex gap-1 mt-1">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                <span class="size-1.5 rounded-full bg-amber-400"></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php 
                        $meals_meta = array(
                            'cafe' => array('label' => 'Café da Manhã', 'icon' => 'coffee'),
                            'almoco' => array('label' => 'Almoço', 'icon' => 'restaurant'),
                            'jantar' => array('label' => 'Jantar', 'icon' => 'nights_stay')
                        );

                        foreach ($meals_meta as $m_key => $m_info) : 
                            $recipe_id = isset($day_meals[$m_key]) ? $day_meals[$m_key] : null;
                            if ($recipe_id) :
                                $recipe = get_post($recipe_id);
                                $thumb = get_the_post_thumbnail_url($recipe_id, 'medium');
                                $tempo = get_post_meta($recipe_id, '_tempo_preparo', true) ?: '20 min';
                        ?>
                            <div class="bg-white dark:bg-slate-800 rounded-[32px] p-4 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all group overflow-hidden">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="material-symbols-outlined text-primary text-base"><?php echo $m_info['icon']; ?></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo $m_info['label']; ?></span>
                                </div>
                                <div class="relative size-full aspect-square rounded-2xl overflow-hidden mb-3">
                                    <img src="<?php echo $thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                                <h4 class="font-black text-slate-900 dark:text-white text-[11px] leading-tight mb-2 line-clamp-2 uppercase tracking-tight"><?php echo $recipe->post_title; ?></h4>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-[9px] font-medium text-slate-400"><?php echo $tempo; ?></span>
                                    <a href="<?php echo get_permalink($recipe_id); ?>" class="bg-slate-100 dark:bg-slate-700 hover:bg-primary hover:text-white transition-all p-2 rounded-xl text-primary text-xs font-black">VER</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="bg-dashed border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-[32px] p-4 flex flex-col items-center justify-center text-center opacity-50 grayscale">
                                <span class="material-symbols-outlined text-slate-300 text-3xl mb-2"><?php echo $m_info['icon']; ?></span>
                                <span class="text-[9px] font-black text-slate-400 uppercase"><?php echo $m_info['label']; ?> - Livre</span>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>

        <!-- Seção de Lista de Compras (Oculta por padrão) -->
        <div id="shopping-list-section" class="hidden animate-in fade-in zoom-in-95 duration-500">
            <div class="bg-white dark:bg-slate-800 rounded-[40px] p-8 border border-slate-100 dark:border-slate-700 shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Lista de Compras Automática</h2>
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Sugerida</span>
                </div>
                
                <div id="ingredients-compiled-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Gerado via JS para não travar o carregamento inicial -->
                    <div class="col-span-full py-10 text-center">
                         <span class="animate-spin material-symbols-outlined text-primary text-4xl">autorenew</span>
                         <p class="text-slate-400 text-xs mt-2 uppercase font-bold tracking-widest">Calculando ingredientes da semana...</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bottom Nav (Apenas para esta página) -->
    <nav class="fixed bottom-6 left-6 right-6 z-50 md:max-w-md md:mx-auto">
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 p-2 rounded-[32px] shadow-2xl flex items-center justify-between">
            <button onclick="toggleCardapioView('menu')" id="nav-menu" class="flex-1 flex flex-col items-center gap-1 py-2 text-primary">
                <span class="material-symbols-outlined">calendar_today</span>
                <span class="text-[9px] font-black uppercase">Cardápio</span>
            </button>
            <button onclick="toggleCardapioView('shopping')" id="nav-shopping" class="flex-1 flex flex-col items-center gap-1 py-2 text-slate-400">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="text-[9px] font-black uppercase">Compras</span>
            </button>
            <button onclick="window.scrollTo({top:0, behavior:'smooth'})" class="flex-1 flex flex-col items-center gap-1 py-2 text-slate-400">
                <span class="material-symbols-outlined">arrow_upward</span>
                <span class="text-[9px] font-black uppercase">Topo</span>
            </button>
        </div>
    </nav>

</div>

<script>
/**
 * Lógica do Planejador Semanal
 */
const cardapioRecipes = <?php echo json_encode($all_recipe_ids); ?>;

function toggleCardapioView(view) {
    const menuSection = document.getElementById('meal-plan-content');
    const shoppingSection = document.getElementById('shopping-list-section');
    const navMenu = document.getElementById('nav-menu');
    const navShopping = document.getElementById('nav-shopping');

    if (view === 'shopping') {
        menuSection.classList.add('hidden');
        shoppingSection.classList.remove('hidden');
        navMenu.classList.replace('text-primary', 'text-slate-400');
        navShopping.classList.replace('text-slate-400', 'text-primary');
        loadShoppingList();
    } else {
        menuSection.classList.remove('hidden');
        shoppingSection.classList.add('hidden');
        navMenu.classList.replace('text-slate-400', 'text-primary');
        navShopping.classList.replace('text-primary', 'text-slate-400');
    }
}

async function loadShoppingList() {
    const container = document.getElementById('ingredients-compiled-list');
    if (container.dataset.loaded === 'true') return;

    try {
        const response = await fetch(window.themeConfig.ajaxUrl + '?action=get_cardapio_ingredients&ids=' + cardapioRecipes.join(','));
        const res = await response.json();

        if (res.success) {
            let html = '';
            res.data.forEach(item => {
                html += `
                <div class="flex items-center p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 group hover:border-primary/30 transition-all cursor-pointer">
                    <input type="checkbox" class="size-5 rounded border-slate-200 text-primary focus:ring-primary mr-4">
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 group-hover:text-primary">${item}</span>
                </div>`;
            });
            container.innerHTML = html;
            container.dataset.loaded = 'true';
        }
    } catch (e) {
        container.innerHTML = '<p class="text-center col-span-full text-slate-400">Erro ao carregar lista.</p>';
    }
}
</script>

<style>
@media print {
    nav, header .flex-center, .bottom-nav, button { display: none !important; }
    #shopping-list-section { display: block !important; }
    .day-card { page-break-inside: avoid; }
}
</style>

<?php get_footer(); ?>
