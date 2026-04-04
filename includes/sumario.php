<?php
/**
 * STS RECIPE - Sumário de Navegação Premium (Pílulas com Ícones)
 * Focado 100% em UX para receitas.
 */

function sts_render_recipe_pill_toc() {
    $post_id = get_the_ID();
    $ingredientes = get_post_meta($post_id, '_ingredientes', true);
    $instrucoes = get_post_meta($post_id, '_instrucoes', true);

    // REGRA INTELIGENTE: Só exibe se ambos estiverem preenchidos
    if (empty($ingredientes) || empty($instrucoes)) return;

    ?>
    <div class="sts-recipe-nav bg-slate-50 dark:bg-slate-900/50 rounded-3xl p-6 mb-10 border border-slate-100 dark:border-slate-800">
        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 block">Ir direto para:</span>
        <div class="flex flex-wrap gap-3">
            
            <!-- Link para Ingredientes -->
            <a href="#ingredients" class="flex items-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 rounded-2xl text-sm font-bold shadow-sm hover:border-primary border border-slate-100 dark:border-slate-700 transition-all hover:scale-[1.03] group">
                <span class="material-symbols-outlined text-primary text-xl group-hover:scale-110 transition-transform">shopping_basket</span>
                Ingredientes
            </a>

            <!-- Link para Modo de Preparo -->
            <a href="#instructions" class="flex items-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 rounded-2xl text-sm font-bold shadow-sm hover:border-primary border border-slate-100 dark:border-slate-700 transition-all hover:scale-[1.03] group">
                <span class="material-symbols-outlined text-primary text-xl group-hover:scale-110 transition-transform">restaurant</span>
                Modo de Preparo
            </a>

            <!-- Link para Avaliação -->
            <a href="#rating-widget" class="flex items-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 rounded-2xl text-sm font-bold shadow-sm hover:border-primary border border-slate-100 dark:border-slate-700 transition-all hover:scale-[1.03] group">
                <span class="material-symbols-outlined text-amber-500 text-xl group-hover:scale-110 transition-transform">star</span>
                Avaliar Receita
            </a>

        </div>
    </div>
    <?php
}
