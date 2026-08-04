<?php
/**
 * Componente: Banner de Convite para Instalação PWA
 * Design: Premium, Altamente Atraente, Discreto e Inteligente (Android & iOS)
 */
?>

<div id="pwa-install-banner" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[10002] w-[92%] max-w-sm hidden transition-all duration-500 ease-out transform translate-y-10 opacity-0">
    <div class="bg-white/95 dark:bg-slate-900/95 border border-slate-100 dark:border-slate-800 rounded-[28px] p-5 shadow-[0_20px_50px_rgba(236,91,19,0.15)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)] backdrop-blur-xl">
        
        <div class="flex items-start gap-4">
            <!-- Icone do App com Efeito de Glow -->
            <div class="relative size-14 rounded-2xl overflow-hidden shadow-md flex-shrink-0 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                <img src="<?php echo THEME_URI; ?>/assets/images/logotipo-descomplicando_receitas300x300.png" alt="App Icon" class="w-full h-full object-cover">
            </div>
            
            <!-- Conteúdo de Texto Premium -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="bg-primary/10 text-primary text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider">PWA APP</span>
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-[10px] text-emerald-500 font-bold flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-xs">offline_bolt</span> Grátis & Leve
                    </span>
                </div>
                <h4 class="text-sm font-black text-slate-800 dark:text-white leading-tight mb-1">Receitas na Palma da Mão!</h4>
                <p class="text-xs text-slate-550 dark:text-slate-400 leading-snug">Instale nosso aplicativo oficial para salvar favoritos e cozinhar sem internet.</p>
            </div>
        </div>

        <!-- Botões de Ação Dinâmicos e Espaçados -->
        <div class="mt-4 flex gap-2">
            <button onclick="dismissPWABanner()" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-500 dark:text-slate-350 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-200">
                Depois
            </button>
            <button id="pwa-install-btn" class="flex-[1.5] py-3.5 bg-primary hover:bg-primary/90 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-base font-bold">download</span> Instalar
            </button>
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
        return diff < (3 * 24 * 60 * 60 * 1000); // 3 dias de silêncio para não cansar o leitor
    };

    const showPWABanner = () => {
        pwaBanner.classList.remove('hidden');
        setTimeout(() => {
            pwaBanner.classList.remove('translate-y-10', 'opacity-0');
            pwaBanner.classList.add('translate-y-0', 'opacity-100');
        }, 100);
    };

    const dismissPWABanner = () => {
        pwaBanner.classList.remove('translate-y-0', 'opacity-100');
        pwaBanner.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => {
            pwaBanner.classList.add('hidden');
        }, 500);
        localStorage.setItem('pwa_dismissed_at', new Date().getTime().toString());
    };

    let shouldShowBanner = false;

    const triggerBannerOnScroll = () => {
        if (!shouldShowBanner) return;
        
        const handleScroll = () => {
            if (window.scrollY > 350) { // Mostra após rolar 350px (quando o usuário já passou pelo prompt de push inicial e está lendo a receita)
                showPWABanner();
                window.removeEventListener('scroll', handleScroll);
            }
        };
        
        window.addEventListener('scroll', handleScroll);
    };

    // Detecção no Android (Chrome)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Se não foi recusado, prepara o banner para rolar
        if (!isDismissed() && !window.matchMedia('(display-mode: standalone)').matches) {
            shouldShowBanner = true;
            triggerBannerOnScroll();
        }
    });

    installBtn.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('[PWA] Usuário aceitou a instalação');
                dismissPWABanner();
            }
            deferredPrompt = null;
        } else {
            // Caso seja iOS ou outro navegador sem prompt nativo
            alert('Para instalar o App no seu celular:\n\n1. Clique no ícone de "Compartilhar" 📤\n2. Role a lista e escolha "Adicionar à Tela de Início" ➕');
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
            // No iOS, mudamos o texto do botão para informar sobre as instruções do Safari
            installBtn.innerHTML = '<span class="material-symbols-outlined text-base font-bold">help</span> Como Instalar';
            shouldShowBanner = true;
            triggerBannerOnScroll();
        }
    });
</script>
