<div id="lgpd-banner" role="region" aria-label="Aviso de Privacidade e Cookies"
     class="fixed bottom-0 left-0 right-0 z-[10000] p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shadow-[0_-5px_30px_rgba(0,0,0,0.1)] translate-y-full transition-transform duration-700 ease-in-out">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        
        <!-- Info Section -->
        <div class="flex items-center md:items-start gap-4 flex-1">
            <div class="bg-primary/10 text-primary p-3 rounded-2xl flex-shrink-0 animate-bounce">
                <span class="material-symbols-outlined block text-3xl">cookie</span>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1 flex items-center gap-2">
                    Privacidade e Dados
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300">LGPD</span>
                </h4>
                <p class="text-[11px] md:text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-w-2xl">
                    Utilizamos cookies para personalizar sua experiência, salvar suas receitas nos <strong>favoritos</strong> e gerenciar seu <strong>perfil de gastronomista</strong>. Ao continuar navegando, você autoriza nossa coleta de dados para estes fins. 
                    <a href="<?php echo get_privacy_policy_url(); ?>" class="text-primary font-bold hover:underline">Saber mais detalhes.</a>
                </p>
            </div>
        </div>

        <!-- Action Section -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button id="lgpd-decline" class="flex-1 md:flex-none px-6 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 text-[10px] font-black text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all uppercase">
                Rejeitar
            </button>
            <button id="lgpd-accept" class="flex-1 md:flex-none px-10 py-3 rounded-2xl bg-primary text-white text-[10px] font-black shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-105 active:scale-95 transition-all uppercase tracking-wider">
                Aceitar Tudo
            </button>
        </div>

    </div>
</div>

<style>
    /* Suaviza a entrada */
    #lgpd-banner.show {
        transform: translateY(0);
    }
</style>
