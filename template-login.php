<?php
/**
 * Template Name: Login Personalizado
 */

get_header();

// Se já estiver logado, vai pro dashboard
if (is_user_logged_in()) {
    wp_redirect(home_url('/meu-painel')); // Ajuste o link se necessário
    exit;
}
?>

<main class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-900 py-6 px-4">
    <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-[40px] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-in fade-in zoom-in-95 duration-500">
        
        <!-- Header Login -->
        <div class="bg-primary p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <span class="material-symbols-outlined text-[100px] absolute -left-10 -top-10 rotate-12 text-white">restaurant</span>
                <span class="material-symbols-outlined text-[80px] absolute -right-8 -bottom-8 -rotate-12 text-white">flatware</span>
            </div>
            <div class="relative z-10 size-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-3 border border-white/30">
                <span class="material-symbols-outlined text-white text-3xl">login</span>
            </div>
            <h1 class="text-2xl font-black text-white relative z-10 leading-none">Bem-vindo(a)</h1>
            <p class="text-white/70 text-[10px] mt-2 font-medium relative z-10 tracking-widest uppercase">Acesse sua cozinha agora mesmo</p>
        </div>

        <!-- Form Login -->
        <div class="p-8">
            <form id="custom-login-form" class="space-y-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">E-mail ou Usuário</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">person</span>
                        <input type="text" name="log" placeholder="Seu acesso..." class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-3.5 pl-11 pr-6 font-bold text-sm transition-all shadow-inner" required />
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Sua Senha</label>
                        <a href="<?php echo wp_lostpassword_url(); ?>" class="text-[9px] font-black text-primary hover:underline uppercase">Esqueceu?</a>
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">lock</span>
                        <input type="password" name="pwd" placeholder="••••••••" class="w-full bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-2xl py-3.5 pl-11 pr-6 font-bold text-sm transition-all shadow-inner" required />
                    </div>
                </div>

                <div class="flex items-center gap-2 px-1">
                    <input type="checkbox" name="rememberme" id="rememberme" class="rounded text-primary focus:ring-primary size-4 border-slate-200">
                    <label for="rememberme" class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Manter conectado</label>
                </div>

                <button type="submit" class="w-full py-4 bg-primary text-white rounded-[22px] font-black text-xs uppercase tracking-widest hover:translate-y-[-2px] hover:shadow-2xl hover:shadow-primary/30 transition-all active:scale-95 mt-2">
                    CONFIRMAR ENTRADA
                </button>

                <div class="text-center pt-6 border-t border-slate-100 dark:border-slate-700/50 mt-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Novo na plataforma?</p>
                    <a href="<?php echo home_url('/cadastrar'); ?>" class="text-xs font-black text-primary hover:underline mt-2 block uppercase tracking-wide">CRIAR MINHA CONTA DE CHEF</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('custom-login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = loginForm.querySelector('button[type="submit"]');
            const originalTxt = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'AUTENTICANDO...';

            const formData = new FormData(loginForm);
            formData.append('action', 'sts_ajax_login');

            fetch(window.themeConfig.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = res.data.redirect;
                } else {
                    alert(res.data);
                    btn.disabled = false;
                    btn.innerText = originalTxt;
                }
            })
            .catch(err => {
                alert('Erro na conexão com o servidor.');
                btn.disabled = false;
                btn.innerText = originalTxt;
            });
        });
    }
});
</script>

<?php get_footer(); ?>
