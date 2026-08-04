<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header(); ?>

<main class="404-page bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center py-20 px-4 transform translate-y-[-100px]">
    
    <div class="max-w-xl w-full text-center">
        <!-- 404 Visual -->
        <div class="relative inline-block mb-12 group">
            <span class="text-[180px] font-black text-primary/10 dark:text-primary/5 leading-none select-none tracking-tighter">404</span>
            <div class="absolute inset-0 flex items-center justify-center transform group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[100px] text-primary drop-shadow-2xl">sentiment_very_dissatisfied</span>
            </div>
        </div>

        <h1 class="text-4xl font-black text-slate-900 dark:text-slate-100 mb-6">Página não encontrada</h1>
        <p class="text-xl text-slate-500 mb-10 leading-relaxed max-w-md mx-auto">
            Opa! Parece que essa receita desandou ou o link que você seguiu não existe mais.
        </p>

        <!-- Search Box 404 -->
        <div class="mb-12">
            <form action="<?php echo home_url(); ?>" method="get" class="relative group">
                <input type="text" name="s" placeholder="Encontrar uma receita deliciosa..." class="w-full bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 py-5 px-6 rounded-3xl focus:border-primary focus:ring-0 transition-all text-lg shadow-xl shadow-slate-200/50 dark:shadow-none font-bold">
                <button type="submit" class="absolute right-3 top-3 size-14 bg-primary text-white rounded-2xl flex items-center justify-center hover:scale-105 transition-transform shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </form>
        </div>

        <!-- Links Rápidos -->
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo home_url(); ?>" class="px-8 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all text-sm">
                Voltar para Home
            </a>
            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-slate-50 transition-all text-sm">
                Ver Todas as Receitas
            </a>
        </div>

        <p class="mt-20 text-xs text-slate-400 font-bold uppercase tracking-widest flex items-center justify-center gap-2">
            <span class="w-10 h-px bg-slate-200 dark:bg-slate-700"></span>
            Precisa de ajuda?
            <span class="w-10 h-px bg-slate-200 dark:bg-slate-700"></span>
        </p>
    </div>

</main>

<?php get_footer(); ?>