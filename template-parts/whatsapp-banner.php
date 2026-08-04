<?php
/**
 * Componente: Banner de Convite para Canal do WhatsApp
 * Versão: 2.0 (Corrigida e Funcional)
 */
?>
<div id="whatsapp-channel-banner" role="complementary" aria-label="Convite para o Canal do WhatsApp"
     class="fixed bottom-6 right-6 z-[10001] max-w-[320px] bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-emerald-200/50 dark:border-emerald-800/50 rounded-[32px] shadow-[0_25px_60px_rgba(0,0,0,0.2)] translate-y-[150%] opacity-0 invisible transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] pointer-events-none overflow-hidden group/card hover:scale-[1.02] active:scale-95">
    
    <!-- Link Principal -->
    <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H" target="_blank" rel="noopener" class="block p-6 cursor-pointer">
        <!-- Ícone flutuante -->
        <div class="absolute -top-6 left-8 bg-[#25D366] text-white size-12 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 animate-bounce group-hover/card:scale-110 transition-transform">
            <i class="fab fa-whatsapp text-2xl" aria-hidden="true"></i>
        </div>

        <div class="mt-4">
            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2 flex items-center gap-2">
                Canal do Chef! 👨‍🍳
                <span class="size-2 bg-red-500 rounded-full animate-pulse"></span>
            </h4>
            <p class="text-[13px] text-slate-600 dark:text-slate-400 leading-snug mb-5">
                Receba <strong class="text-emerald-600 dark:text-emerald-400">receitas novas</strong> todos os dias no seu celular. Junte-se a nós!
            </p>

            <div class="flex items-center justify-center gap-3 w-full py-3.5 bg-[#25D366] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 group-hover/card:bg-[#1ebd59] transition-all">
                ENTRAR NO CANAL
                <span class="material-symbols-outlined text-sm">open_in_new</span>
            </div>
        </div>
    </a>

    <!-- Botão Fechar -->
    <button id="close-whatsapp-banner" aria-label="Fechar convite"
            class="absolute top-2 right-2 size-10 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all duration-300 z-20">
        <span class="material-symbols-outlined text-xl pointer-events-none">close</span>
    </button>
</div>

<style>
    #whatsapp-channel-banner.show {
        transform: translateY(0) !important;
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }

    /* Ajuste para não sobrepor LGPD */
    body.lgpd-active #whatsapp-channel-banner {
        bottom: 100px;
    }

    @media (max-width: 640px) {
        #whatsapp-channel-banner {
            left: 1rem;
            right: 1rem;
            max-width: none;
            bottom: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('whatsapp-channel-banner');
    const closeBtn = document.getElementById('close-whatsapp-banner');
    
    // Verifica se o usuário já fechou o banner anteriormente
    const isClosed = localStorage.getItem('whatsapp_banner_closed');

    if (!isClosed) {
        // Mostra o banner após 3 segundos (melhor experiência)
        setTimeout(() => {
            banner.classList.add('show');
        }, 3000);
    }

    closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        banner.classList.remove('show');
        // Opcional: Não mostrar novamente por 7 dias
        localStorage.setItem('whatsapp_banner_closed', 'true');
    });
});
</script>
