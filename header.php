<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="max-image-preview:large">
    <link rel="canonical" href="<?php echo get_permalink(); ?>" />
    
    <?php wp_head(); ?>

    <!-- PWA: Configurações de Aplicativo (Tier 1) -->
    <link rel="manifest" href="<?php echo THEME_URI; ?>/manifest.json">
    <meta name="theme-color" content="#ec5b13">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="D. Receitas">
    <link rel="apple-touch-icon" href="<?php echo THEME_URI; ?>/assets/images/logotipo-descomplicando_receitas300x300.png">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo THEME_URI; ?>/sw.js')
                    .then(reg => console.log('[PWA] Service Worker registrado com sucesso!'))
                    .catch(err => console.log('[PWA] Erro ao registrar SW:', err));
            });
        }
    </script>
    
    <!-- Preload das Fontes Locais (Otimização CWV) -->
    <link rel="preload" href="<?php echo THEME_URI; ?>/assets/fonts/public-sans-400.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo THEME_URI; ?>/assets/fonts/public-sans-700.ttf" as="font" type="font/ttf" crossorigin>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Font Awesome (Social Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body.admin-bar header { top: 32px !important; }
        @media screen and (max-width: 782px) { body.admin-bar header { top: 46px !important; } }
    </style>

    <script>
        window.themeConfig = {
            homeUrl: '<?php echo esc_url(home_url('/')); ?>',
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>'
        };
    </script>
</head>

<body <?php body_class('bg-white dark:bg-slate-900 transition-colors duration-200'); ?> data-category="<?php if(is_singular('post')) { $cat = get_the_category(); if($cat) echo $cat[0]->slug; } ?>">
    <?php wp_body_open(); ?>

    <!-- Pular para o Conteúdo (Acessibilidade) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[10000] focus:bg-primary focus:text-white focus:px-6 focus:py-3 focus:rounded-2xl focus:shadow-2xl focus:font-bold">
        Pular para o conteúdo principal
    </a>

    <div id="global-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[20000] hidden opacity-0 transition-opacity duration-300"></div>

    <header class="sticky top-[var(--header-top,0px)] z-[100] w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-100 dark:border-slate-800 transition-[top] duration-700">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4 px-4 h-16 md:h-20">
            
            <!-- Logo Section -->
            <div class="flex items-center gap-2">
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo-wrapper h-10 md:h-12 w-auto flex items-center">
                        <?php 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id , 'full');
                        if ($logo) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Ir para a página inicial">
                                <img src="<?php echo esc_url($logo[0]); ?>" class="h-10 md:h-12 w-auto object-contain" alt="<?php bloginfo('name'); ?>" fetchpriority="high" decoding="sync">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2 group" aria-label="Ir para a página inicial">
                        <div class="bg-primary text-white p-1.5 rounded-xl">
                            <span class="material-symbols-outlined block text-2xl" aria-hidden="true">restaurant_menu</span>
                        </div>
                        <span class="text-xl md:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter"><?php bloginfo('name'); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Main Navigation -->
            <nav class="hidden lg:flex items-center gap-2" aria-label="Menu principal">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'container' => false,
                    'menu_class' => 'flex items-center gap-2',
                    'fallback_cb' => false,
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'add_li_class'  => 'px-4 py-2 text-[11px] font-black text-slate-500 dark:text-slate-400 hover:text-primary transition-all uppercase tracking-widest rounded-xl hover:bg-primary/5'
                ));
                ?>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-2 md:gap-3">
                
                <!-- Live Search -->
                <div class="relative group hidden md:block">
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-2xl px-4 py-2 border border-transparent focus-within:border-primary/20 w-48 xl:w-64 transition-all">
                        <span class="material-symbols-outlined text-slate-400 text-xl mr-2" aria-hidden="true">search</span>
                        <input type="text" id="sts-live-search" placeholder="Buscar..." aria-label="Buscar receitas"
                               class="bg-transparent border-none p-0 text-xs font-black text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-0 w-full uppercase tracking-widest">
                    </div>
                    <div id="sts-live-results" class="absolute top-full right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-[32px] shadow-2xl border border-slate-100 dark:border-slate-800 hidden overflow-hidden z-[110]"></div>
                </div>

                <!-- Theme Toggle -->
                <button id="theme-toggle" type="button" aria-label="Alternar modo de cor"
                        class="size-11 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-xl dark:hidden" aria-hidden="true">dark_mode</span>
                    <span class="material-symbols-outlined text-xl hidden dark:block" aria-hidden="true">light_mode</span>
                </button>

                <!-- Favorites -->
                <div class="relative group">
                    <button id="favorites-trigger" type="button" aria-label="Minhas favoritas"
                            class="size-11 flex items-center justify-center rounded-2xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">favorite</span>
                        <span id="fav-count" class="absolute -top-1 -right-1 size-4 bg-primary text-white text-[9px] font-bold rounded-full border-2 border-white dark:border-slate-900 flex items-center justify-center hidden">0</span>
                    </button>
                    <div class="absolute top-full right-0 mt-4 w-72 bg-white dark:bg-slate-900 rounded-[32px] shadow-2xl border border-slate-100 dark:border-slate-800 invisible opacity-0 translate-y-4 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 z-[110] p-6">
                        <h4 class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-4">Minhas Salvas</h4>
                        <div id="fav-items-list" class="max-h-80 overflow-y-auto"></div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="relative group">
                    <button id="user-profile-trigger" type="button" aria-label="Perfil do usuário"
                            class="size-11 flex items-center justify-center rounded-2xl bg-slate-900 dark:bg-slate-800 text-white overflow-hidden transition-all">
                        <?php if (is_user_logged_in()) : 
                            echo get_avatar(get_current_user_id(), 44, '', '', ['class' => 'w-full h-full object-cover']);
                        else : ?>
                            <span class="material-symbols-outlined text-xl" aria-hidden="true">person</span>
                        <?php endif; ?>
                    </button>
                    <div class="absolute top-full right-0 mt-4 w-60 bg-white dark:bg-slate-900 rounded-[32px] shadow-2xl border border-slate-100 dark:border-slate-800 invisible opacity-0 translate-y-4 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 z-[110] p-2">
                        <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo home_url('/perfil'); ?>" class="flex items-center gap-3 p-3 text-[10px] font-black text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl transition-all uppercase tracking-widest leading-none">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">account_circle</span> Perfil
                            </a>
                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="flex items-center gap-3 p-3 text-[10px] font-black text-red-510 hover:bg-red-50 rounded-2xl transition-all uppercase tracking-widest leading-none">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">logout</span> Sair
                            </a>
                        <?php else : ?>
                            <div class="p-4 text-center">
                                <button type="button" onclick="document.getElementById('auth-modal').classList.remove('hidden')" 
                                        class="w-full py-3 bg-primary text-white text-[10px] font-black rounded-2xl uppercase tracking-widest">ENTRAR</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Toggle Mobile -->
                <button id="mobileMenuBtn" type="button" aria-label="Abrir menu" aria-expanded="false"
                        class="lg:hidden size-11 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400">
                    <span class="material-symbols-outlined text-2xl" aria-hidden="true">menu</span>
                </button>

            </div>
        </div>
    </header>

    <!-- Ad: Billboard Top (Native Manager) -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <?php if(function_exists('sts_show_ad_slot')) sts_show_ad_slot('ad_top_billboard', false); ?>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden absolute top-full left-0 w-full bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 shadow-xl z-[40]">
        <nav class="p-6">
            <?php wp_nav_menu(array('theme_location' => 'main-menu', 'container' => false, 'menu_class' => 'flex flex-col gap-4 text-sm font-black uppercase tracking-widest')); ?>
        </nav>
    </div>

    <!-- Auth Modal -->
    <div id="auth-modal" class="hidden fixed inset-0 z-[20001] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-[40px] shadow-2xl p-10 relative">
            <button onclick="document.getElementById('auth-modal').classList.add('hidden')" aria-label="Fechar" class="absolute top-6 right-6 text-slate-400">
                <span class="material-symbols-outlined">close</span>
            </button>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6 uppercase tracking-tighter">Entrar na Cozinha</h2>
            <form id="popup-login-form" class="space-y-4">
                <input type="text" name="log" placeholder="Usuário" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl py-4 px-6 font-bold" required />
                <input type="password" name="pwd" placeholder="Senha" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl py-4 px-6 font-bold" required />
                <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-primary/20">ENTRAR</button>
            </form>
        </div>
    </div>

    <script id="dark-mode-fouc">
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>