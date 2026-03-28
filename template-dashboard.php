<?php
/**
 * Template Name: Dashboard do Cozinheiro
 */

get_header();

// Proteção: Apenas logados - Redireciona para Home com gatilho de popup
if (!is_user_logged_in()) {
    wp_redirect(home_url('/?auth=required'));
    exit;
}
$current_user = wp_get_current_user();
$user_post_count = count_user_posts($current_user->ID);
?>
<main class="min-h-screen bg-slate-50 dark:bg-slate-900 pt-10 pb-20">
    <div class="max-w-6xl mx-auto px-4">
        
        <!-- Header Dashboard Professional Rebuilt -->
        <div class="bg-white dark:bg-slate-800 rounded-[40px] p-8 md:p-12 shadow-sm border border-slate-100 dark:border-slate-700 mb-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-80 h-80 bg-primary/5 rounded-full -mr-40 -mt-40 blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10 relative z-10">
                
                <!-- Lado Esquerdo: Identidade -->
                <div class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left flex-1 w-full">
                    <div class="relative shrink-0">
                        <div class="size-28 md:size-36 rounded-[40px] overflow-hidden border-4 border-white dark:border-slate-700 shadow-2xl shadow-primary/10 relative group">
                            <img src="<?php echo sts_get_user_avatar_url($current_user->ID, 150); ?>" alt="Meu Perfil" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="material-symbols-outlined text-white text-3xl">add_a_photo</span>
                            </div>
                        </div>
                        <button onclick="document.getElementById('modal-profile').classList.remove('hidden')" class="absolute -bottom-2 -right-2 p-3 bg-primary text-white rounded-2xl shadow-xl hover:scale-110 active:scale-95 transition-all border-4 border-white dark:border-slate-800">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </button>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <span class="text-[12px] font-black text-primary uppercase tracking-[0.3em] mb-2 block animate-pulse">Cozinheiro(a) Verificado(a)</span>
                        <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white leading-tight mb-2 tracking-tight truncate">
                            <?php echo $current_user->display_name; ?>
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium text-lg italic">
                            Você já compartilhou <span class="text-primary font-black"><?php echo $user_post_count; ?> receitas</span> com nossa rede.
                        </p>
                    </div>
                </div>

                <!-- Lado Direito: Ações Estratégicas -->
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-5 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-[22px] font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        Sair do Site
                    </a>
                    <button onclick="document.getElementById('modal-submit').classList.remove('hidden')" class="w-full sm:w-auto flex items-center justify-center gap-4 px-10 py-5 bg-primary text-white rounded-[22px] font-black text-[11px] uppercase tracking-widest shadow-2xl shadow-primary/30 hover:-translate-y-1 active:scale-95 transition-all animate-bounce-subtle">
                        <span class="material-symbols-outlined text-xl">add_circle</span>
                        Enviar Receita
                    </button>
                </div>

            </div>
        </div>
     </div>

        <!-- Dashboard Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Sidebar: Stats & Info -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 uppercase text-xs tracking-widest mb-6">Estatísticas</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <span class="text-2xl font-black text-primary block">0</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Receitas</span>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <span class="text-2xl font-black text-primary block" id="dashboard-fav-count">0</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Salvas</span>
                        </div>
                    </div>
                </div>

                <div class="bg-primary/5 rounded-3xl p-8 border border-primary/10">
                    <h3 class="font-black text-primary uppercase text-xs tracking-widest mb-4">Dica de Chef</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium italic">
                        "Receitas com fotos bem iluminadas e passos detalhados têm 80% mais chances de serem aprovadas e visualizadas!"
                    </p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-2 space-y-10">
                
                <!-- Tab Receiver Placeholder -->
                <div id="dashboard-main-content">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-slate-100 mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">favorite</span>
                        Minhas Receitas Favoritas
                    </h2>
                    
                    <div id="fav-dashboard-list" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        <!-- Carregado via JS -->
                    </div>

                    <!-- Paginação de Favoritos -->
                    <div id="fav-pagination" class="mt-12 flex justify-center items-center gap-4"></div>
                </div>

                <!-- Minhas Receitas Enviadas -->
                <div id="dashboard-user-posts" class="mt-16">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-slate-100 mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">post_add</span>
                        Minhas Publicações
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <?php 
                        $user_posts = new WP_Query(array(
                            'author' => $current_user->ID,
                            'post_type' => 'post',
                            'post_status' => array('publish', 'pending', 'draft'),
                            'posts_per_page' => 10
                        ));

                        if ($user_posts->have_posts()) : while ($user_posts->have_posts()) : $user_posts->the_post();
                            $status = get_post_status();
                            $status_label = ($status == 'pending') ? 'Aguardando Moderação' : (($status == 'publish') ? 'Publicada' : 'Rascunho');
                            $status_color = ($status == 'pending') ? 'bg-amber-100 text-amber-700' : (($status == 'publish') ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500');
                        ?>
                            <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-100 dark:border-slate-700 flex items-center justify-between group hover:shadow-md transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-300">restaurant</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-slate-100 uppercase text-xs tracking-tight"><?php the_title(); ?></h4>
                                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md <?php echo $status_color; ?> mt-1 inline-block">
                                            <?php echo $status_label; ?>
                                        </span>
                                    </div>
                                </div>
                                <?php if ($status == 'publish') : ?>
                                    <a href="<?php the_permalink(); ?>" target="_blank" class="p-3 bg-primary/10 text-primary rounded-xl hover:bg-primary hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; wp_reset_postdata(); else : ?>
                            <div class="py-16 flex flex-col items-center justify-center text-center bg-slate-50/50 dark:bg-slate-900/50 rounded-[30px] border-2 border-dashed border-slate-100 dark:border-slate-700/50">
                                <span class="material-symbols-outlined text-slate-200 text-5xl mb-3">cloud_upload</span>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none">Você ainda não enviou nenhuma receita.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (current_user_can('manage_options')) : ?>
                    <!-- Painel de Moderação para Admins (Dashboard Interno) -->
                    <div id="dashboard-admin-moderation" class="p-10 bg-slate-900 border border-slate-700 rounded-[40px] mt-20 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 bg-primary text-white text-[9px] font-black rounded-bl-3xl uppercase tracking-widest">Moderador</div>
                        <h3 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-4xl">admin_panel_settings</span>
                            Receitas Pendentes (Global)
                        </h3>
                        
                        <div class="space-y-4">
                            <?php 
                            $pending_posts = new WP_Query(array('post_status' => 'pending', 'posts_per_page' => 20));
                            if ($pending_posts->have_posts()) : while ($pending_posts->have_posts()) : $pending_posts->the_post();
                                $author_name = get_the_author();
                            ?>
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-6 bg-slate-800/50 rounded-3xl border border-slate-700 hover:border-primary/50 transition-all gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-2xl bg-slate-700 overflow-hidden shrink-0">
                                            <?php echo get_avatar(get_the_author_meta('ID'), 48); ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-white uppercase tracking-tight"><?php the_title(); ?></h4>
                                            <p class="text-[10px] text-slate-400 mt-1">Autor: <span class="text-primary font-bold"><?php echo $author_name; ?></span></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full md:w-auto">
                                        <button onclick="handleAdminAction(<?php the_ID(); ?>, 'approve')" class="flex-1 md:flex-none px-6 py-3 bg-green-500 text-white rounded-2xl font-black text-[10px] hover:scale-105 transition-all shadow-lg shadow-green-500/20">APROVAR AGORA</button>
                                        <button onclick="handleAdminAction(<?php the_ID(); ?>, 'delete')" class="flex-1 md:flex-none px-6 py-3 bg-red-500/20 text-red-500 rounded-2xl font-black text-[10px] hover:bg-red-500 hover:text-white transition-all">EXCLUIR</button>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata(); else : ?>
                                <div class="text-center py-10 opacity-50">
                                    <span class="material-symbols-outlined text-slate-500 text-5xl mb-3">task_alt</span>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tudo limpo! Não há receitas pendentes.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<!-- Modal: Enviar Receita -->
<div id="modal-submit" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
    <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden relative animate-in zoom-in-95 duration-300">
        <button onclick="document.getElementById('modal-submit').classList.add('hidden')" class="absolute top-6 right-6 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="px-8 py-10">
            <h2 class="text-3xl font-black mb-2">Compartilhar Receita</h2>
            <p class="text-slate-500 dark:text-slate-400 mb-8 font-medium">Sua receita passará por uma revisão dos nossos Chefs antes de ser publicada.</p>
            
            <form id="recipe-submit-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Título da Receita</label>
                        <input type="text" name="recipe_title" placeholder="Ex: Bolo de Cenoura Fit" class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-4 px-6 font-bold" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Categoria</label>
                        <select name="recipe_category" class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-4 px-6 font-bold">
                            <?php 
                            $cats = get_categories();
                            foreach($cats as $cat) echo "<option value='{$cat->term_id}'>{$cat->name}</option>";
                            ?>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Ingredientes</label>
                    <textarea name="recipe_ingredients" rows="3" placeholder="Liste um por linha..." class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-4 px-6 font-bold"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Modo de Preparo</label>
                    <textarea name="recipe_steps" rows="3" placeholder="Explique passo a passo..." class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-4 px-6 font-bold"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Foto da Receita (Capa)</label>
                    <div class="relative group cursor-pointer h-24 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl flex items-center justify-center hover:border-primary transition-colors bg-slate-50/50 dark:bg-slate-900/50 overflow-hidden">
                        <div class="flex items-center gap-3 pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">image</span>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-primary transition-colors">Escolher Imagem (JPG, PNG ou WEBP)</span>
                        </div>
                        <input type="file" name="recipe_image" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 opacity-0 cursor-pointer" onchange="this.parentElement.querySelector('span:last-child').innerText = this.files[0].name" />
                    </div>
                </div>

                <div class="flex items-center gap-6 pt-4">
                    <button type="submit" class="flex-1 py-4 bg-primary text-white rounded-2xl font-black text-sm hover:translate-y-[-2px] transition-all shadow-xl shadow-primary/20">
                        ENVIAR PARA MODERAÇÃO
                    </button>
                    <button type="button" onclick="document.getElementById('modal-submit').classList.add('hidden')" class="px-8 py-4 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 rounded-2xl font-black text-sm">CANCELAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Carregar Favoritos no Dashboard com Paginação
    const FAV_KEY = 'sts_fav_recipes';
    let currentFavPage = 1;
    const itemsPerFavPage = 6;

    function getFavs() { try { return JSON.parse(localStorage.getItem(FAV_KEY)) || []; } catch(e) { return []; } }
    
    function refreshDashboardFavs() {
        const favs = getFavs();
        const container = document.getElementById('fav-dashboard-list');
        const pagination = document.getElementById('fav-pagination');
        const countTxt = document.getElementById('dashboard-fav-count');
        
        if (countTxt) countTxt.innerText = favs.length;
        if (!container) return;
        
        if (favs.length === 0) {
            container.innerHTML = `
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-800 rounded-[40px] border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">restaurant</span>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none">Ainda não há receitas navegando por aqui...</p>
                </div>`;
            if (pagination) pagination.innerHTML = '';
            return;
        }

        // Paginação Logic
        const totalItems = favs.length;
        const totalPages = Math.ceil(totalItems / itemsPerFavPage);
        if (currentFavPage > totalPages) currentFavPage = totalPages;
        
        const start = (currentFavPage - 1) * itemsPerFavPage;
        const pagedIds = favs.slice(start, start + itemsPerFavPage);

        // Grid compacta
        container.classList.remove('grid-cols-1', 'md:grid-cols-2', 'xl:grid-cols-3', 'xl:grid-cols-4');
        container.classList.add('grid-cols-2', 'sm:grid-cols-3', 'xl:grid-cols-4', 'gap-4', 'md:gap-6');

        container.innerHTML = '<div class="col-span-full py-10 text-center"><span class="animate-spin material-symbols-outlined text-primary text-4xl">autorenew</span></div>';

        const data = new FormData();
        data.append('action', 'get_fav_details');
        pagedIds.forEach(id => data.append('ids[]', id));
        
        fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                container.innerHTML = res.data.map(item => `
                    <div class="bg-white dark:bg-slate-800 rounded-2xl md:rounded-[32px] overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group flex flex-col animate-in fade-in zoom-in-95 duration-500">
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img src="${item.thumb}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <button onclick="dashboardRemoveFav(${item.id})" class="absolute top-2 right-2 md:top-4 md:right-4 size-8 md:size-10 bg-white/20 backdrop-blur-md rounded-lg md:rounded-xl text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-red-500 shadow-lg flex items-center justify-center" aria-label="Remover dos favoritos">
                                <span class="material-symbols-outlined text-base md:text-xl">delete</span>
                            </button>
                        </div>
                        <div class="p-3 md:p-5 flex-1 flex flex-col">
                            <h4 class="font-black text-slate-900 dark:text-slate-100 leading-tight mb-3 flex-1 line-clamp-2 uppercase tracking-tight text-[10px] md:text-xs">${item.title}</h4>
                            <div class="flex items-center justify-between mt-auto">
                                <a href="${item.url}" class="px-3 py-1.5 md:px-5 md:py-2 bg-primary text-white rounded-lg md:rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 transition-all">VER</a>
                                <button onclick="navigator.clipboard.writeText('${item.url}'); alert('Link copiado!');" class="p-1 text-slate-400 hover:text-primary transition-colors" aria-label="Copiar link da receita">
                                    <span class="material-symbols-outlined text-base md:text-lg">share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');

                renderPagination(totalPages);
            }
        });
    }

    function renderPagination(totalPages) {
        const pagination = document.getElementById('fav-pagination');
        if (!pagination || totalPages <= 1) {
            if (pagination) pagination.innerHTML = '';
            return;
        }

        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            const active = i === currentFavPage ? 'bg-primary text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 hover:bg-slate-200';
            html += `<button onclick="changeFavPage(${i})" class="size-10 rounded-xl font-black text-xs transition-all ${active}" aria-label="Ir para página ${i}">${i}</button>`;
        }
        pagination.innerHTML = html;
    }

    window.changeFavPage = function(page) {
        currentFavPage = page;
        refreshDashboardFavs();
        document.getElementById('fav-dashboard-list').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    window.dashboardRemoveFav = function(id) {
        let favs = getFavs();
        favs = favs.filter(fid => fid != id);
        localStorage.setItem(FAV_KEY, JSON.stringify(favs));
        
        // Se a página ficar vazia ao remover, volta uma página
        const totalPages = Math.ceil(favs.length / itemsPerFavPage);
        if (currentFavPage > totalPages) currentFavPage = Math.max(1, totalPages);
        
        refreshDashboardFavs();
        
        const headerCount = document.getElementById('fav-count');
        if (headerCount) {
             headerCount.innerText = favs.length;
             if (favs.length === 0) headerCount.classList.add('hidden');
        }
    };

    refreshDashboardFavs();

    // 2. Envio de Receita
    const form = document.getElementById('recipe-submit-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'ENVIANDO...';

            const formData = new FormData(form);
            formData.append('action', 'sts_submit_recipe');

            fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert('Receita enviada com sucesso! Ela entrará para moderação.');
                    location.reload();
                } else {
                    alert('Erro ao enviar: ' + (res.data || 'Erro desconhecido'));
                    btn.disabled = false;
                    btn.innerText = 'ENVIAR PARA MODERAÇÃO';
                }
            });
        });
    }

    // 3. Ações de Moderação (Administrador)
    window.handleAdminAction = function(postId, type) {
        if (!confirm('Deseja realmente ' + (type === 'approve' ? 'aprovar' : 'excluir') + ' esta receita?')) return;
        
        const data = new FormData();
        data.append('action', 'sts_admin_action');
        data.append('post_id', postId);
        data.append('type', type);

        fetch(window.themeConfig.ajaxUrl, { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert(res.data);
                location.reload();
            } else {
                alert('Erro: ' + res.data);
            }
        });
    }
});
</script>

<?php get_footer(); ?>
