<?php
/**
 * Componente: Banner de Convite para Canal do WhatsApp
 * Design: Floating Glassmorphism Premium
 */
?>
<div id="whatsapp-channel-banner" role="alert" aria-label="Convite para o Canal do WhatsApp"
     class="fixed bottom-6 right-6 z-[10001] max-w-[320px] bg-emerald-50 dark:bg-slate-900 border-2 border-emerald-100 dark:border-emerald-800 rounded-[32px] shadow-[0_25px_60px_rgba(0,0,0,0.3)] translate-y-[150%] opacity-0 invisible transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] pointer-events-none overflow-hidden group/card hover:scale-[1.02] active:scale-95">
    
    <!-- Link Principal (Cobre o card todo) -->
    <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H" target="_blank" rel="noopener" class="block p-6 cursor-pointer">
        <!-- Ícone flutuante do Zap -->
        <div class="absolute -top-6 left-8 bg-[#25D366] text-white size-12 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 animate-bounce group-hover/card:scale-110 transition-transform">
            <i class="fab fa-whatsapp text-2xl" aria-hidden="true"></i>
        </div>

        <div class="mt-4">
            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2 flex items-center gap-2">
                Canal do Chef! 👨‍🍳
                <span class="size-2 bg-red-500 rounded-full animate-pulse"></span>
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                Receba **receitas novas e exclusivas** todos os dias direto no seu celular. Junte-se a +50 mil seguidores!
            </p>

            <div class="flex items-center justify-center gap-3 w-full py-4 bg-[#25D366] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 group-hover/card:bg-[#1ebd59] transition-all">
                ENTRAR NO CANAL
                <span class="material-symbols-outlined text-sm">open_in_new</span>
            </div>
        </div>
    </a>

    <!-- Botão Fechar (Área de toque ampliada) -->
    <button id="close-whatsapp-banner" aria-label="Fechar convite do WhatsApp"
            class="absolute top-2 right-2 size-12 flex items-center justify-center text-slate-400 hover:text-red-510 transition-all duration-300 z-20">
        <span class="material-symbols-outlined text-2xl pointer-events-none" aria-hidden="true">close</span>
    </button>

    <!-- Efeito de brilho de fundo -->
    <div class="absolute -z-10 top-0 left-0 w-full h-full bg-gradient-to-br from-emerald-500/5 to-transparent rounded-[32px]"></div>
</div>

<style>
    /* Reforço de especificidade para garantir interatividade */
    #whatsapp-channel-banner.show {
        transform: translateY(0) !important;
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }

    /* Ajuste para não sobrepor se o LGPD estiver aberto (Opcional, mas profissional) */
    body.lgpd-active #whatsapp-channel-banner:not(.show-mobile) {
        bottom: 120px; /* Sobe o banner se a LGPD estiver visível */
    }

    @media (max-width: 640px) {
        #whatsapp-channel-banner {
            left: 1.5rem;
            right: 1.5rem;
            max-width: none;
            bottom: 1.5rem;
        }
        /* No mobile, se a LGPD estiver aberta, jogamos o WhatsApp um pouco mais pra cima */
        body.lgpd-active #whatsapp-channel-banner.show {
            bottom: 100px;
        }
    }
</style>

