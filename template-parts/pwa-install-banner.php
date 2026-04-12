<?php
/**
 * Componente: Banner de Convite para Instalação PWA
 * Design: Premium, Discreto e Inteligente (Android & iOS)
 */
?>

<div id="pwa-install-banner" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[10002] w-[90%] max-w-md hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[32px] p-6 shadow-2xl shadow-primary/20 backdrop-blur-xl bg-opacity-95 dark:bg-opacity-90">
        <div class="flex items-center gap-5">
            <!-- Ícone do App -->
            <div class="size-14 rounded-2xl overflow-hidden shadow-lg border-2 border-white dark:border-slate-700 flex-shrink-0">
                <img src="<?php echo THEME_URI; ?>/assets/images/logotipo-descomplicando_receitas300x300.png" alt="App Icon" class="w-full h-full object-cover">
            </div>
            
            <!-- Texto -->
            <div class="flex-1">
                <h4 class="text-sm font-black text-slate-900 dark:text-white leading-tight mb-1 uppercase tracking-tight">Cozinhe com mais facilidade!</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Instale nosso App e acesse suas receitas favoritas em um clique.</p>
            </div>

            <!-- Botão Fechar -->
            <button onclick="dismissPWABanner()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors p-2">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <div class="mt-5 flex gap-3">
            <button id="pwa-install-btn" class="flex-1 py-4 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-primary/30 transition-all hover:scale-[1.02] active:scale-95">
                INSTALAR AGORA
            </button>
            <button onclick="dismissPWABanner()" class="px-6 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-widest">
                DEPOIS
            </div>
        </div>
    </div>
</div>

<!-- Estilo & Lógica do Banner PWA -->
<script>
    let deferredPrompt;
    const pwaBanner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');

    // Verifica se já foi recusado recentemente
    const isDismissed = () => {
        const dismissedAt = localStorage.getItem('pwa_dismissed_at');
        if (!dismissedAt) return false;
        const now = new Date().getTime();
        const diff = now - parseInt(dismissedAt);
        return diff < (7 * 24 * 60 * 60 * 1000); // 7 dias de silêncio
    };

    const dismissPWABanner = () => {
        pwaBanner.classList.add('hidden');
        localStorage.setItem('pwa_dismissed_at', new Date().getTime().toString());
    };

    // Detecção no Android (Chrome)
    window.addEventListener('beforeinstallprompt', (e) => {
        // Impede o prompt padrão do Chrome
        e.preventDefault();
        deferredPrompt = e;
        
        // Se não foi recusado, mostra o banner
        if (!isDismissed() && !window.matchMedia('(display-mode: standalone)').matches) {
            pwaBanner.classList.remove('hidden');
        }
    });

    installBtn.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('[PWA] Usuário aceitou a instalação');
                pwaBanner.classList.add('hidden');
            }
            deferredPrompt = null;
        } else {
            // Caso seja iOS ou outro navegador sem prompt nativo
            alert('Para instalar:\n1. Clique no ícone de Compartilhar 📤\n2. Escolha "Adicionar à Tela de Início" ➕');
        }
    });

    // Detecção para iOS (Safari)
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };
    
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    window.addEventListener('load', () => {
        if (isIos() && !isInStandaloneMode() && !isDismissed()) {
            // No iOS, mudamos o texto do botão para ser informativo
            installBtn.innerText = 'VER COMO INSTALAR';
            pwaBanner.classList.remove('hidden');
        }
    });
</script>
