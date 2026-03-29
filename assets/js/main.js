/**
 * sts-recipe-2 - Main Logic (Clean & PHP-Free)
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Menu Mobile
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // 1.1 Gerenciador de Tema (Dark Mode)
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
    }

    // 1.2 Sistema de Abas do Modal de Autenticação
    window.toggleAuthTab = function(type) {
        const loginBtn = document.getElementById('tab-login-btn');
        const regBtn = document.getElementById('tab-register-btn');
        const loginCont = document.getElementById('auth-login-content');
        const regCont = document.getElementById('auth-register-content');

        if (!loginBtn || !regBtn) return;

        if(type === 'login') {
            loginBtn.classList.add('border-primary', 'text-primary');
            loginBtn.classList.remove('border-transparent', 'text-slate-400');
            regBtn.classList.add('border-transparent', 'text-slate-400');
            regBtn.classList.remove('border-primary', 'text-primary');
            loginCont.classList.remove('hidden');
            regCont.classList.add('hidden');
        } else {
            regBtn.classList.add('border-primary', 'text-primary');
            regBtn.classList.remove('border-transparent', 'text-slate-400');
            loginBtn.classList.add('border-transparent', 'text-slate-400');
            loginBtn.classList.remove('border-primary', 'text-primary');
            regCont.classList.remove('hidden');
            loginCont.classList.add('hidden');
        }
    }

    // 2. Sistema de Favoritos (LGPD Compliant - LocalStorage)
    const FAV_KEY = 'sts_fav_recipes';
    
    const getFavs = () => {
        try {
            const data = localStorage.getItem(FAV_KEY);
            return data ? JSON.parse(data) : [];
        } catch(e) {
            return [];
        }
    };
    
    const saveFavs = (favs) => {
        try {
            localStorage.setItem(FAV_KEY, JSON.stringify(favs));
        } catch(e) {
            console.error('LocalStorage falhou:', e);
        }
    };
    
    function updateFavUI() {
        const favs = getFavs();
        
        // Atualiza contadores no cabeçalho
        const countBadge = document.getElementById('fav-count');
        const totalText = document.getElementById('fav-total');
        if (countBadge) {
            countBadge.innerText = favs.length;
            countBadge.classList.toggle('hidden', favs.length === 0);
        }
        if (totalText) totalText.innerText = favs.length + ' Salvas';

        // Atualiza botões espalhados pela página
        document.querySelectorAll('.btn-favorite').forEach(btn => {
            const id = btn.dataset.postId;
            const icon = btn.querySelector('.material-symbols-outlined');
            if (favs.includes(id)) {
                btn.classList.add('is-favorite');
                if (icon) icon.style.fontVariationSettings = "'FILL' 1, 'wght' 700";
            } else {
                btn.classList.remove('is-favorite');
                if (icon) icon.style.fontVariationSettings = "'FILL' 0, 'wght' 400";
            }
        });

        // Carrega a lista se houver itens
        if (favs.length > 0) {
            refreshFavList();
        } else {
            const list = document.getElementById('fav-items-list');
            if (list) list.innerHTML = '<div class="text-center py-6"><span class="material-symbols-outlined text-slate-200 text-4xl mb-2">favorite_border</span><p class="text-xs text-slate-400">Nenhuma salva ainda.</p></div>';
        }
    }

    let isFetchingFavs = false;
    function refreshFavList() {
        const favs = getFavs();
        const listContainer = document.getElementById('fav-items-list');
        if (!listContainer || isFetchingFavs || favs.length === 0) return;
        
        isFetchingFavs = true;
        
        const data = new FormData();
        data.append('action', 'get_fav_details');
        favs.forEach(id => data.append('ids[]', id));
        
        fetch(window.themeConfig.ajaxUrl, {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                listContainer.innerHTML = res.data.map(item => `
                    <a href="${item.url}" class="flex items-center gap-4 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-2xl transition-all group border-b border-transparent">
                        <div class="size-16 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 shadow-sm">
                            <img src="${item.thumb}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex-1">
                            <h5 class="text-xs font-black text-slate-900 dark:text-slate-100 line-clamp-2 leading-tight uppercase tracking-tight">${item.title}</h5>
                            <span class="text-[9px] text-primary font-black flex items-center gap-1 mt-1">VER AGORA <span class="material-symbols-outlined text-[10px]">arrow_forward</span></span>
                        </div>
                    </a>
                `).join('');
            }
        })
        .finally(() => { isFetchingFavs = false; });
    }

    // Event Delegation para cliques em favoritos
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-favorite');
        if (!btn) return;
        
        e.preventDefault();
        const id = btn.dataset.postId;
        if (!id) return;

        let favs = getFavs();
        const isFav = favs.includes(id);

        if (isFav) {
            favs = favs.filter(f => f !== id);
        } else {
            favs.push(id);
            // Efeito de feedback
            btn.classList.add('scale-125');
            setTimeout(() => btn.classList.remove('scale-125'), 200);
        }
        
        saveFavs(favs);
        updateFavUI();
    });

    // Inicializa UI
    updateFavUI();
    
    // 3. Lazy Loading & Animations
    const observerOptions = {
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-4');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.recipe-card-animate').forEach(card => {
        card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-4');
        observer.observe(card);
    });

    // 4. Sistema de Busca (Dinâmico)
    window.updateSearch = function() {
        const inp = document.querySelector('.search-page-input');
        const c = document.getElementById('search-category');
        const o = document.getElementById('search-order');
        const params = new URLSearchParams();
        if (inp && inp.value) params.append('s', inp.value);
        if (c && c.value) params.append('category', c.value);
        if (o && o.value) params.append('orderby', o.value);
        const u = window.themeConfig ? window.themeConfig.homeUrl : '/';
        window.location.href = u + '?' + params.toString();
    };

    window.clearSearch = function() {
        window.location.href = window.themeConfig ? window.themeConfig.homeUrl : '/';
    };

    // 5. Autenticação e Perfil (Popups & Dashboard)
    const initAuth = () => {
        // Gatilho Automático pela URL
        const params = new URLSearchParams(window.location.search);
        const authModal = document.getElementById('auth-modal');
        if (params.get('auth') === 'required' && authModal) {
            authModal.classList.remove('hidden');
        }

        // Form de Login
        const loginForm = document.getElementById('popup-login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = loginForm.querySelector('button[type="submit"]');
                const originalText = btn.innerText;
                btn.disabled = true; btn.innerText = 'AUTENTICANDO...';
                const formData = new FormData(loginForm);
                formData.append('action', 'sts_ajax_login');
                fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: formData })
                .then(res => res.json()).then(res => {
                    if (res.success) { window.location.href = res.data.redirect; }
                    else { alert(res.data); btn.disabled = false; btn.innerText = originalText; }
                });
            });
        }

        // Form de Cadastro
        const regForm = document.getElementById('popup-register-form');
        if (regForm) {
            regForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = regForm.querySelector('button[type="submit"]');
                const originalText = btn.innerText;
                btn.disabled = true; btn.innerText = 'CADASTRANDO...';
                const formData = new FormData(regForm);
                formData.append('action', 'sts_ajax_register');
                fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: formData })
                .then(res => res.json()).then(res => {
                    if (res.success) { window.location.href = res.data.redirect; }
                    else { alert(res.data); btn.disabled = false; btn.innerText = originalText; }
                });
            });
        }

        // Dashboard Update
        const profileForm = document.getElementById('profile-update-form');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = profileForm.querySelector('button[type="submit"]');
                btn.disabled = true; btn.innerText = 'SALVANDO...';
                const formData = new FormData(profileForm);
                formData.append('action', 'sts_update_profile');
                fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: formData })
                .then(res => res.json()).then(res => {
                    if (res.success) { 
                        alert('Perfil atualizado com sucesso!');
                        window.location.reload(); 
                    } else { 
                        alert(res.data); btn.disabled = false; btn.innerText = 'SALVAR ALTERAÇÕES'; 
                    }
                });
            });
        }
    };

    initAuth();

    // 6. Sistema de Avaliação de Receitas (Stars)
    const ratingWidget = document.getElementById('rating-widget');
    if (ratingWidget) {
        const starBtns = ratingWidget.querySelectorAll('.star-btn');
        const successModal = document.getElementById('rating-success');
        const avgDisplay = document.getElementById('rating-current-avg');
        const countDisplay = document.getElementById('rating-current-count');
        const ratingInfoId = document.getElementById('rating_post_id');
        
        if (starBtns.length > 0 && ratingInfoId) {
            const postId = ratingInfoId.value;

            starBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (this.classList.contains('submitting')) return;
                    
                    const rating = this.dataset.value;
                    starBtns.forEach(b => b.classList.add('submitting', 'opacity-50', 'pointer-events-none'));
                    
                    const formData = new FormData();
                    formData.append('action', 'sts_recipe_rating');
                    formData.append('post_id', postId);
                    formData.append('rating', rating);

                    fetch(window.themeConfig.ajaxUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            // Atualiza os textos na interface
                            if (avgDisplay) avgDisplay.innerText = res.data.average;
                            if (countDisplay) countDisplay.innerText = res.data.count;
                            
                            // Mostra a mensagem de sucesso
                            if (successModal) {
                                successModal.classList.remove('hidden');
                                successModal.classList.add('flex', 'animate-in', 'fade-in', 'duration-500');
                            }
                        } else {
                            starBtns.forEach(b => b.classList.remove('submitting', 'opacity-50', 'pointer-events-none'));
                            alert(res.data || 'Erro ao registrar voto.'); 
                        }
                    })
                    .catch(err => {
                        starBtns.forEach(b => b.classList.remove('submitting', 'opacity-50', 'pointer-events-none'));
                        console.error('Erro na requisição:', err);
                    });
                });

                // Efeito Hover Melhorado com Persistência
                btn.addEventListener('mouseenter', function() {
                    if (this.classList.contains('submitting')) return;
                    const val = parseInt(this.dataset.value);
                    starBtns.forEach((s, idx) => {
                        const starIcon = s.querySelector('.material-symbols-outlined');
                        if (idx < val) {
                            starIcon.classList.add('text-amber-400', 'fill-star');
                            starIcon.style.fontVariationSettings = "'FILL' 1";
                        }
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    if (this.classList.contains('submitting')) return;
                    starBtns.forEach(s => {
                        const starIcon = s.querySelector('.material-symbols-outlined');
                        starIcon.classList.remove('text-amber-400', 'fill-star');
                        starIcon.style.fontVariationSettings = "'FILL' 0";
                    });
                });
            });
        }
    }

    // 7. Sistema de Aviso LGPD (Cookies)
    const LGPD_KEY = 'sts_lgpd_consent_2026';
    const lgpdBanner = document.getElementById('lgpd-banner');
    
    if (lgpdBanner) {
        const consent = localStorage.getItem(LGPD_KEY);
        if (!consent) {
            setTimeout(() => lgpdBanner.classList.add('show'), 1500);
        } else {
            lgpdBanner.remove(); 
        }

        const lgpdAccept = document.getElementById('lgpd-accept');
        const lgpdDecline = document.getElementById('lgpd-decline');

        if (lgpdAccept) {
            lgpdAccept.addEventListener('click', function() {
                localStorage.setItem(LGPD_KEY, 'accepted');
                lgpdBanner.classList.remove('show');
                setTimeout(() => lgpdBanner.remove(), 700);
            });
        }

        if (lgpdDecline) {
            lgpdDecline.addEventListener('click', function() {
                localStorage.setItem(LGPD_KEY, 'declined');
                lgpdBanner.classList.remove('show');
                setTimeout(() => lgpdBanner.remove(), 700);
            });
        }
    }

    // 8. Barra de Avaliação Flutuante (Floating Slide-in)
    const floatingBar = document.getElementById('floating-rating-bar');
    const RATING_STORAGE_KEY = 'sts_floating_hidden';
    
    if (floatingBar) {
        const closeBtn = document.getElementById('close-floating-rating');
        const floatStars = floatingBar.querySelectorAll('.floating-star-btn');
        const floatSuccess = document.getElementById('floating-success');
        const ratingIdElement = document.getElementById('rating_post_id') || document.getElementById('float_rating_post_id');
        
        if (ratingIdElement) {
            const postId = ratingIdElement.value;
            let hasShown = false;

            // Verifica se o usuário já fechou ou votou nesta sessão (LocalStorage)
            const isHidden = localStorage.getItem(RATING_STORAGE_KEY + '_' + postId);

            if (!isHidden) {
                window.addEventListener('scroll', function() {
                    // Maneira ultra-estável de detectar scroll (em pixels em vez de %)
                    if (!hasShown && window.scrollY > 600) {
                        hasShown = true;
                        console.log('✅ Barra de Avaliação Flutuante Ativada'); 
                        floatingBar.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-[200%]');
                        floatingBar.classList.add('active', 'opacity-100', 'translate-y-0');
                    }
                });
            }

            // Fechar manualmente
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    floatingBar.classList.remove('active', 'opacity-100');
                    localStorage.setItem(RATING_STORAGE_KEY + '_' + postId, 'true');
                });
            }

            // Lógica de Voto Flutuante
            floatStars.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (this.classList.contains('submitting')) return;
                    
                    const rating = this.dataset.value;
                    floatStars.forEach(b => b.classList.add('submitting', 'pointer-events-none', 'opacity-50'));
                    
                    const formData = new FormData();
                    formData.append('action', 'sts_recipe_rating');
                    formData.append('post_id', postId);
                    formData.append('rating', rating);

                    fetch(window.themeConfig.ajaxUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            if (floatSuccess) {
                                floatSuccess.classList.remove('hidden');
                                floatSuccess.classList.add('flex');
                            }
                            
                            setTimeout(() => {
                                floatingBar.classList.remove('active', 'opacity-100');
                                localStorage.setItem(RATING_STORAGE_KEY + '_' + postId, 'true');
                            }, 3000);

                            const mainAvg = document.getElementById('rating-current-avg');
                            const mainCount = document.getElementById('rating-current-count');
                            if (mainAvg) mainAvg.innerText = res.data.average;
                            if (mainCount) mainCount.innerText = res.data.count;
                        } else {
                            alert(res.data);
                            floatStars.forEach(b => b.classList.remove('submitting', 'pointer-events-none', 'opacity-50'));
                        }
                    });
                });

                btn.addEventListener('mouseenter', function() {
                    if (this.classList.contains('submitting')) return;
                    const val = parseInt(this.dataset.value);
                    floatStars.forEach((s, idx) => {
                        const starIcon = s.querySelector('.material-symbols-outlined');
                        if (idx < val) {
                            starIcon.classList.add('text-amber-400');
                            starIcon.style.fontVariationSettings = "'FILL' 1";
                        }
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    if (this.classList.contains('submitting')) return;
                    floatStars.forEach(s => {
                        const starIcon = s.querySelector('.material-symbols-outlined');
                        starIcon.classList.remove('text-amber-400');
                        starIcon.style.fontVariationSettings = "'FILL' 0";
                    });
                });
            });
        }
    }
});
