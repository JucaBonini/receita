<?php
/**
 * Componente: Barra de Navegação Inferior Fixa (Visual App no Celular)
 * Estratégia: Fixa na base para facilitar o toque de polegar e abrir busca/favoritos rapidamente.
 */
$post_id = get_the_ID();
?>

<!-- Dock Inferior Mobile (Oculto no Desktop) -->
<div class="lg:hidden fixed bottom-0 left-0 z-[190] w-full bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-t border-slate-100 dark:border-slate-800 px-6 py-3 pb-safe shadow-[0_-10px_35px_rgba(0,0,0,0.06)] flex items-center justify-between pointer-events-auto">
    <!-- Home -->
    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 active:scale-95 transition-all w-16" aria-label="Ir para página inicial">
        <span class="material-symbols-outlined text-2xl font-light">home</span>
        <span class="text-[9px] font-bold uppercase tracking-widest">Início</span>
    </a>

    <!-- Buscar -->
    <button type="button" id="dock-search-btn" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 active:scale-95 transition-all w-16" aria-label="Buscar receitas">
        <span class="material-symbols-outlined text-2xl font-light">search</span>
        <span class="text-[9px] font-bold uppercase tracking-widest">Buscar</span>
    </button>

    <!-- Cozinhar (Apenas no Single Post) ou Receitas Salvas -->
    <?php if (is_singular('post')) : ?>
        <button type="button" id="dock-start-cooking" class="flex flex-col items-center gap-1 text-primary active:scale-95 transition-all w-16" aria-label="Iniciar Modo Cozinha">
            <span class="material-symbols-outlined text-2xl font-light" style="font-variation-settings: 'FILL' 1;">cooking</span>
            <span class="text-[9px] font-black uppercase tracking-widest">Cozinhar</span>
        </button>
    <?php else : ?>
        <button type="button" id="dock-favorites" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 active:scale-95 transition-all w-16" aria-label="Ver receitas salvas">
            <span class="material-symbols-outlined text-2xl font-light">favorite</span>
            <span class="text-[9px] font-bold uppercase tracking-widest">Salvas</span>
        </button>
    <?php endif; ?>

    <!-- Menu -->
    <button type="button" id="dock-menu-btn" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 active:scale-95 transition-all w-16" aria-label="Abrir menu">
        <span class="material-symbols-outlined text-2xl font-light">menu</span>
        <span class="text-[9px] font-bold uppercase tracking-widest">Menu</span>
    </button>
</div>

<!-- Modal Flutuante de Busca Móvel (Oculto por Padrão) -->
<div id="mobile-search-overlay" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[20000] flex items-center justify-center p-6 animate-in fade-in duration-300 pointer-events-auto">
    <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-[35px] p-6 relative shadow-2xl border border-slate-100 dark:border-slate-800">
        <button type="button" id="close-mobile-search" class="absolute top-4 right-4 size-10 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-primary transition-all active:scale-90" aria-label="Fechar busca">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
        <div class="mb-4">
            <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-1">O que vamos cozinhar?</span>
            <h4 class="text-base font-bold text-slate-900 dark:text-white leading-tight">Buscar no Descomplicando</h4>
        </div>
        <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="flex gap-2">
            <input type="text" name="s" placeholder="Digite um ingrediente ou prato..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl py-3 px-5 font-bold text-sm outline-none focus:ring-2 focus:ring-primary/20 text-slate-900 dark:text-white" required />
            <button type="submit" class="bg-primary text-white size-12 rounded-2xl font-bold hover:bg-primary/95 transition-all flex items-center justify-center shrink-0 active:scale-90 shadow-lg shadow-primary/25">
                <span class="material-symbols-outlined text-xl">search</span>
            </button>
        </form>
    </div>
</div>
