<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    <!-- Preload das Fontes Locais (Otimização CWV) -->
    <link rel="preload" href="<?php echo THEME_URI; ?>/assets/fonts/public-sans-400.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo THEME_URI; ?>/assets/fonts/public-sans-700.ttf" as="font" type="font/ttf" crossorigin>

    <!-- Material Symbols (Asynchronous Loading) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"></noscript>

    <!-- PWA Integration -->
    <link rel="manifest" href="<?php echo THEME_URI; ?>/manifest.json">
    <meta name="theme-color" content="#ec5b13">
    <link rel="apple-touch-icon" href="<?php echo THEME_URI; ?>/assets/images/logotipo-descomplicando_receitas300x300.png">
    
    <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?php echo THEME_URI; ?>/sw.js')
          .then(reg => console.log('[PWA] Service Worker Registered!'))
          .catch(err => console.log('[PWA] Service Worker Registration Failed!', err));
      });
    }
    </script>

        <!-- Font Awesome (Social Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Garantir que o WP Admin Bar não quebre o sticky header */
        body.admin-bar header { top: 32px !important; }
        @media screen and (max-width: 782px) {
            body.admin-bar header { top: 46px !important; }
        }
        /* Ajuste para Logo Dinâmica */
        .custom-logo-link img {
            height: 40px !important;
            width: auto !important;
            max-height: 40px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .custom-logo-link:hover img { transform: scale(1.05); }
        .dark .custom-logo-link img { filter: brightness(1.1) contrast(1.1); } /* Leve ajuste para Dark Mode */
    </style>

    <script>
        window.themeConfig = {
            homeUrl: '<?php echo esc_url(home_url('/')); ?>',
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>'
        };
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 transition-colors duration-200'); ?>>

<!-- Ad Top Header (2026 Strategy) -->
<?php sts_display_ad('header_top'); ?>

<header class="sticky top-0 z-50 w-full border-b border-primary/10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-10 py-3">
    <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
        
        <!-- Logo -->
        <div class="flex items-center gap-2 text-primary">
            <?php if (has_custom_logo()) : ?>
                <div class="custom-logo-wrapper h-10 md:h-12 w-auto flex items-center">
                    <?php the_custom_logo(); ?>
                </div>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2 group">
                    <div class="bg-primary text-white p-1.5 rounded-lg">
                        <span class="material-symbols-outlined block text-2xl font-bold">restaurant_menu</span>
                    </div>
                    <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight tracking-tight">
                        <?php 
                        $site_name = get_bloginfo('name');
                        echo str_replace('Receitas', '<span class="text-primary">Receitas</span>', $site_name);
                        ?>
                    </h2>
                </a>
            <?php endif; ?>
        </div>

        <!-- Desktop Navigation (Dinâmico) -->
        <nav class="hidden md:flex flex-1 justify-center items-center">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => false,
                'menu_class' => 'flex items-center gap-6 text-sm font-semibold',
                'fallback_cb' => false,
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'add_li_class'  => 'hover:text-primary transition-colors'
            ));
            ?>
        </nav>

        <!-- Search and Actions -->
        <div class="flex items-center gap-3">
            <div class="hidden lg:flex items-center bg-slate-200/50 dark:bg-slate-800/50 rounded-xl px-3 py-1.5 border border-transparent focus-within:border-primary/50 transition-all">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-xl">search</span>
                    <input type="search" name="s" class="bg-transparent border-none focus:ring-0 text-sm w-32 xl:w-48 placeholder:text-slate-400" placeholder="Buscar receitas..." value="<?php echo get_search_query(); ?>"/>
                </form>
            </div>

            <div class="relative group" id="fav-dropdown">
                <button class="p-2 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all relative" aria-label="Ver favoritos">
                    <span class="material-symbols-outlined">favorite</span>
                    <span id="fav-count" class="absolute -top-1 -right-1 bg-primary text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-black border-2 border-white dark:border-background-dark hidden">0</span>
                </button>
                <!-- Dropdown de Favoritos -->
                <div id="fav-container" class="absolute right-0 top-full mt-3 w-[320px] bg-white dark:bg-slate-800 rounded-[32px] shadow-2xl border border-slate-100 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all z-[100] p-6 origin-top-right">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50 dark:border-slate-700">
                        <h4 class="font-black text-slate-900 dark:text-slate-100 uppercase tracking-widest text-[10px]">Minhas Receitas</h4>
                        <span class="bg-primary/10 text-primary text-[9px] px-2 py-1 rounded-md font-bold" id="fav-total">0 Salvas</span>
                    </div>
                    
                    <div id="fav-items-list" class="space-y-4 max-h-[350px] overflow-y-auto custom-scrollbar">
                        <!-- Itens carregados via JS -->
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-slate-200 text-4xl mb-2">favorite_border</span>
                            <p class="text-xs text-slate-400">Suas favoritas aparecerão aqui.</p>
                        </div>
                    </div>

                    <a href="<?php echo esc_url(home_url('/')); ?>" class="block w-full text-center py-3 mt-4 text-[11px] font-black text-slate-400 hover:text-primary transition-colors border-t border-slate-50 dark:border-slate-700 pt-6">EXPLORAR MAIS RECEITAS</a>
                </div>
            </div>
            
            <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl bg-slate-200/50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 hover:text-primary transition-all" aria-label="Abrir menu">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <button id="theme-toggle" class="p-2 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-primary transition-all" aria-label="Alternar modo claro/escuro">
                <span class="material-symbols-outlined dark:!hidden">dark_mode</span>
                <span class="material-symbols-outlined !hidden dark:!block">light_mode</span>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative group">
                <div id="user-profile-trigger" class="h-10 w-10 rounded-full overflow-hidden cursor-pointer border-2 border-slate-100 dark:border-slate-800 group-hover:border-primary transition-all flex items-center justify-center bg-slate-50 dark:bg-slate-900 shadow-sm">
                    <?php if (is_user_logged_in()) : 
                        $current_user = wp_get_current_user();
                        echo get_avatar($current_user->ID, 40, '', 'Meu Perfil', ['class' => 'w-full h-full object-cover']);
                    else : ?>
                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">person</span>
                    <?php endif; ?>
                </div>

                <!-- O Menu suspenso -->
                <div class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all z-[110] overflow-hidden py-3">
                    <?php if (is_user_logged_in()) : ?>
                        <div class="px-6 py-3 border-b border-slate-50 dark:border-slate-700/50 mb-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Cozinheiro(a)</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white truncate"><?php echo $current_user->display_name; ?></p>
                        </div>
                        <a href="<?php echo home_url('/meu-painel'); ?>" class="flex items-center gap-3 px-6 py-3 text-[11px] font-black text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-primary/5 transition-all uppercase tracking-widest">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            Meu Painel
                        </a>
                        <a href="<?php echo home_url('/meu-perfil'); ?>" class="flex items-center gap-3 px-6 py-3 text-[11px] font-black text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-primary/5 transition-all uppercase tracking-widest">
                            <span class="material-symbols-outlined text-lg">person_edit</span>
                            Meu Perfil
                        </a>
                        <div class="mx-6 my-2 border-t border-slate-50 dark:border-slate-700/50"></div>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="flex items-center gap-3 px-6 py-3 text-[11px] font-black text-red-500 hover:bg-red-50 transition-all uppercase tracking-widest">
                            <span class="material-symbols-outlined text-lg">logout</span>
                            Sair do Site
                        </a>
                    <?php else : ?>
                        <a href="<?php echo home_url('/entrar'); ?>" class="flex items-center gap-4 px-6 py-4 text-[12px] font-black text-slate-900 dark:text-white hover:text-primary hover:bg-primary/5 transition-all uppercase tracking-widest border-b border-slate-50 dark:border-slate-700/50">
                            <span class="material-symbols-outlined text-xl text-primary">login</span>
                            ENTRAR
                        </a>
                        <a href="<?php echo home_url('/cadastrar'); ?>" class="flex items-center gap-4 px-6 py-4 text-[12px] font-black text-slate-900 dark:text-white hover:text-primary hover:bg-primary/5 transition-all uppercase tracking-widest">
                            <span class="material-symbols-outlined text-xl text-primary">how_to_reg</span>
                            CADASTRAR
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu (Overlay) -->
    <div id="mobileMenu" class="hidden absolute top-full left-0 w-full bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 shadow-xl z-[40] animate-in slide-in-from-top duration-300">
        <nav class="p-6">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => false,
                'menu_class' => 'flex flex-col gap-4 text-lg font-black uppercase tracking-widest',
                'fallback_cb' => false,
                'items_wrap' => '<ul class="%2$s">%3$s</ul>'
            ));
            ?>
            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-6">
                <!-- Formulário de Busca Real no Mobile -->
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary transition-transform group-focus-within:scale-110">search</span>
                    <input type="search" name="s" placeholder="Qual receita você busca hoje?" class="w-full bg-primary/5 dark:bg-primary/10 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 dark:text-white placeholder:text-primary/40 transition-all" value="<?php echo get_search_query(); ?>"/>
                </form>

                <div class="flex items-center justify-between px-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Siga-nos</span>
                    <div class="flex gap-4">
                        <button onclick="if(navigator.share) { navigator.share({ title: 'Descomplicando Receitas', url: window.location.href }); } else { navigator.clipboard.writeText(window.location.href); alert('Link copiado!'); }" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined text-xl">share</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Popup de Autenticação (Login/Cadastro) -->
<div id="auth-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xl">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-[40px] shadow-2xl overflow-hidden relative animate-in zoom-in-95 duration-300">
        <button onclick="document.getElementById('auth-modal').classList.add('hidden')" class="absolute top-6 right-6 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-all z-10 text-slate-400">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <!-- Abas do Modal -->
        <div class="flex border-b border-slate-100 dark:border-slate-700">
            <button onclick="toggleAuthTab('login')" id="tab-login-btn" class="flex-1 py-6 text-xs font-black uppercase tracking-widest border-b-2 border-primary text-primary transition-all">Entrar</button>
            <button onclick="toggleAuthTab('register')" id="tab-register-btn" class="flex-1 py-6 text-xs font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">Cadastrar</button>
        </div>

        <div class="p-10">
            <div id="auth-login-content" class="space-y-6">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white leading-none mb-2">Bem-vindo de volta!</h2>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide mb-6">Acesse sua cozinha agora mesmo.</p>
                <form id="popup-login-form" class="space-y-4">
                    <input type="text" name="log" placeholder="E-mail ou Usuário" class="w-full bg-slate-50 dark:bg-slate-900 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl py-4 px-6 font-bold text-sm shadow-inner" required />
                    <input type="password" name="pwd" placeholder="Sua Senha" class="w-full bg-slate-50 dark:bg-slate-900 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl py-4 px-6 font-bold text-sm shadow-inner" required />
                    <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all">ENTRAR NO SITE</button>
                </form>
            </div>

            <div id="auth-register-content" class="hidden space-y-6">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white leading-none mb-2">Crie sua conta Chef!</h2>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide mb-6">Compartilhe e salve suas receitas.</p>
                <form id="popup-register-form" class="space-y-4">
                    <input type="text" name="user_name" placeholder="Seu Nome Completo" class="w-full bg-slate-50 dark:bg-slate-900 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl py-4 px-6 font-bold text-sm shadow-inner" required />
                    <input type="email" name="user_email" placeholder="Seu melhor E-mail" class="w-full bg-slate-50 dark:bg-slate-900 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl py-4 px-6 font-bold text-sm shadow-inner" required />
                    <input type="password" name="user_pass" placeholder="Crie uma Senha" class="w-full bg-slate-50 dark:bg-slate-900 border-none focus:ring-2 focus:ring-primary/20 rounded-2xl py-4 px-6 font-bold text-sm shadow-inner" required minlength="6" />
                    <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all">CADASTRAR GRATUITAMENTE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script id="dark-mode-fouc">
    // Prevenir o "Flash" de luz no Dark Mode (FOUC)
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>