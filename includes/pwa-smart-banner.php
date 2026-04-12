<?php
/**
 * SMART PWA INSTALL BANNER - DESCOMPLICANDO RECEITAS
 * Aumenta a taxa de conversão para instalação do App
 */

if (!defined('ABSPATH')) exit;

function sts_render_pwa_smart_banner() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = '';
    
    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_src($custom_logo_id, 'thumbnail');
        $logo_url = $logo[0];
    } else {
        $logo_url = THEME_URI . '/assets/images/logotipo-descomplicando_receitas300x300.png';
    }
    ?>
    <!-- Smart PWA Banner -->
    <div id="pwa-smart-banner" class="fixed top-0 left-0 w-full z-[30000] -translate-y-full transition-transform duration-700 ease-in-out hidden mb-0">
        <div class="bg-white dark:bg-slate-900 border-b-2 border-primary/20 shadow-2xl px-4 py-3 md:py-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between gap-3">
                
                <!-- Info -->
                <div class="flex items-center gap-3 overflow-hidden">
                    <button id="close-pwa-banner" class="size-8 flex items-center justify-center text-slate-400 hover:text-red-510 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                    <div class="size-12 md:size-14 rounded-2xl overflow-hidden shadow-lg border border-slate-100 dark:border-slate-800 flex-shrink-0 bg-white">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="App Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-xs md:text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight truncate leading-none mb-1">Cozinhe com nosso App</h4>
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest truncate leading-none">Rápido, Grátis e Offline</p>
                    </div>
                </div>

                <!-- Action -->
                <div class="flex items-center gap-2">
                    <button id="install-pwa-btn" class="bg-primary text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-transform whitespace-nowrap">
                        Instalar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <style>
        body.pwa-banner-active {
            padding-top: 76px !important;
            transition: padding-top 0.7s ease-in-out;
        }
        @media (min-width: 768px) {
            body.pwa-banner-active { padding-top: 88px !important; }
        }
        #pwa-smart-banner.show {
            transform: translateY(0);
            display: block;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let deferredPrompt;
        const banner = document.getElementById('pwa-smart-banner');
        const installBtn = document.getElementById('install-pwa-btn');
        const closeBtn = document.getElementById('close-pwa-banner');
        const STORAGE_KEY = 'sts_pwa_banner_dismissed';

        // 1. Capturar o evento nativo de instalação
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            console.log('✅ PWA: Evento de instalação capturado');
            
            // Inicia o timer de 15 segundos se não foi rejeitado antes
            if (!localStorage.getItem(STORAGE_KEY)) {
                setTimeout(showPWABanner, 15000); 
            }
        });

        function showPWABanner() {
            if (banner && deferredPrompt) {
                banner.classList.remove('hidden');
                setTimeout(() => {
                    banner.classList.add('show');
                    document.body.classList.add('pwa-banner-active');
                }, 100);
            }
        }

        // 2. Lógica de Instalação ao clicar
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                
                banner.classList.remove('show');
                document.body.classList.remove('pwa-banner-active');
                
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    console.log('User accepted the PWA install');
                    localStorage.setItem(STORAGE_KEY, 'installed');
                }
                deferredPrompt = null;
            });
        }

        // 3. Lógica de Fechar (Rejeitar por 7 dias)
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                banner.classList.remove('show');
                document.body.classList.remove('pwa-banner-active');
                
                // Salva o timestamp da rejeição
                const expireDate = new Date().getTime() + (7 * 24 * 60 * 60 * 1000);
                localStorage.setItem(STORAGE_KEY, expireDate);
                
                setTimeout(() => banner.classList.add('hidden'), 700);
            });
        }

        // Limpa flag de expiração se já passou 7 dias
        const dismissed = localStorage.getItem(STORAGE_KEY);
        if (dismissed && dismissed !== 'installed' && new Date().getTime() > parseInt(dismissed)) {
            localStorage.removeItem(STORAGE_KEY);
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'sts_render_pwa_smart_banner');
