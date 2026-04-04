<?php
/**
 * STS RECIPE - Modo de Cozinha (Cooking Mode)
 * Permite que a tela fique ligada e as instruções fiquem em destaque.
 */

// Este arquivo não precisa de hooks de backend pesados, 
// apenas injeta o HTML do Modal que o JS irá controlar.

function sts_render_cooking_mode_modal() {
    if (!is_singular()) return;
    ?>
    <!-- Modal de Modo de Cozinha -->
    <div id="cooking-mode-overlay" class="fixed inset-0 z-[1000] bg-white dark:bg-slate-950 hidden flex-col overflow-hidden animate-in fade-in duration-300">
        <!-- Header fixo do Modo Cozinha -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-primary text-3xl">kitchen</span>
                <div>
                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-primary">Modo de Cozinha Ativado</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase" id="cooking-mode-status">Tela permanecerá ligada</p>
                </div>
            </div>
            <button id="close-cooking-mode" class="p-3 bg-red-50 text-red-500 rounded-full hover:bg-red-500 hover:text-white transition-all scale-125">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Conteúdo do Modo Cozinha (Passo a Passo Gigante) -->
        <div class="flex-1 overflow-y-auto p-10 md:p-20 custom-scrollbar scroll-smooth">
            <h2 id="cooking-recipe-title" class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-12 text-center"></h2>
            
            <div id="cooking-steps-container" class="max-w-4xl mx-auto space-y-16 pb-20">
                <!-- Passos injetados via JS -->
            </div>
            
            <div class="max-w-4xl mx-auto border-t border-slate-100 dark:border-slate-800 pt-10 text-center">
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Bom apetite! Fim da Receita.</p>
            </div>
        </div>
        
        <!-- Footer de Navegação (Checklist) -->
        <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex justify-center gap-4">
             <button id="cooking-prev" class="px-6 py-3 bg-white dark:bg-slate-800 rounded-2xl text-xs font-black uppercase tracking-widest shadow-sm hover:scale-105 transition-all hidden">Anterior</button>
             <button id="cooking-next" class="px-6 py-3 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-all">Próximo Passo</button>
        </div>
    </div>
    <?php
}

add_action('wp_footer', 'sts_render_cooking_mode_modal');
