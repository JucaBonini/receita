<?php
/**
 * Template Name: Página de Perfil do Chef
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/entrar/?auth=required'));
    exit;
}

get_header();
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Puxar metadados customizados
$gender = get_user_meta($user_id, 'sts_gender', true);
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$bio = get_user_meta($user_id, 'description', true);
?>

<main class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-20 pb-20 px-4">
    <div class="max-w-4xl mx-auto">
        
        <!-- Alertas Dinâmicos -->
        <div id="profile-alerts" class="mb-8 hidden"></div>

        <form id="sts-full-profile-form" class="space-y-12 bg-white dark:bg-slate-900 p-8 md:p-16 rounded-[40px] shadow-2xl border border-slate-100 dark:border-slate-800">
            
            <!-- SEÇÃO: MEUS DADOS -->
            <section class="space-y-10">
                <div class="flex items-center gap-4 border-b border-slate-50 dark:border-slate-800 pb-6">
                    <h1 class="text-3xl font-black text-primary uppercase tracking-tighter">Meus Dados</h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
                    
                    <!-- Lado Esquerdo: Avatar -->
                    <div class="lg:col-span-4 space-y-6 text-center lg:text-left">
                        <div class="relative group mx-auto lg:mx-0 w-48 h-48 bg-slate-100 dark:bg-slate-800 rounded-[30px] overflow-hidden border-4 border-white dark:border-slate-700 shadow-xl ring-1 ring-slate-100 dark:ring-slate-800 transition-transform hover:scale-[1.02]">
                            <img id="profile-avatar-preview" src="<?php echo sts_get_user_avatar_url($user_id, 300); ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-4xl">add_a_photo</span>
                            </div>
                            <input type="file" id="user_avatar_input" name="user_avatar" accept="image/jpeg,image/jpg" class="absolute inset-0 opacity-0 cursor-pointer" onchange="if(this.files[0]) { document.getElementById('profile-avatar-preview').src = URL.createObjectURL(this.files[0]); }">
                        </div>
                        
                        <div class="space-y-2">
                             <button type="button" onclick="document.getElementById('user_avatar_input').click()" class="bg-slate-900 text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-lg">ESCOLHER UMA IMAGEM</button>
                             <div class="text-[9px] text-slate-400 font-bold leading-relaxed mt-4">
                                Formato: <span class="text-primary">jpg e jpeg</span><br>
                                Dimensão: <span class="text-slate-600 dark:text-slate-300">200px por 200px mínimo</span><br>
                                Peso: <span class="text-slate-600 dark:text-slate-300">inferior a 10MB</span>
                             </div>
                        </div>
                    </div>

                    <!-- Lado Direito: Campos -->
                    <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Gênero -->
                        <div class="col-span-1 space-y-4">
                            <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Gênero</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="sts_gender" value="masculino" <?php checked($gender, 'masculino'); ?> class="size-5 border-2 border-slate-200 text-primary focus:ring-primary rounded-md">
                                    <span class="text-[11px] font-bold text-slate-500 group-hover:text-primary transition-colors uppercase tracking-widest">Masculino</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="sts_gender" value="feminino" <?php checked($gender, 'feminino'); ?> class="size-5 border-2 border-slate-200 text-primary focus:ring-primary rounded-md">
                                    <span class="text-[11px] font-bold text-slate-500 group-hover:text-primary transition-colors uppercase tracking-widest">Feminino</span>
                                </label>
                            </div>
                        </div>

                        <!-- Nome ou Apelido (Display Name - READONLY) -->
                        <div class="col-span-1 space-y-2">
                            <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Nome ou apelido</label>
                            <input type="text" value="<?php echo esc_attr($current_user->display_name); ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-400 font-bold text-sm cursor-not-allowed" readonly title="Nomes não podem ser alterados">
                        </div>

                        <!-- Nome -->
                        <div class="col-span-1 space-y-2">
                            <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Nome*</label>
                            <input type="text" name="first_name" value="<?php echo esc_attr($first_name); ?>" placeholder="Seu nome" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-900 dark:text-white font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-inner" required>
                        </div>

                        <!-- Sobrenome -->
                        <div class="col-span-1 space-y-2">
                            <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Sobrenome</label>
                            <input type="text" name="last_name" value="<?php echo esc_attr($last_name); ?>" placeholder="Seu sobrenome" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-900 dark:text-white font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-inner">
                        </div>

                        <!-- Mini Biografia -->
                        <div class="col-span-2 space-y-2">
                            <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Mini Biografia</label>
                            <textarea name="description" rows="3" placeholder="Conte-nos mais sobre você..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-900 dark:text-white font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-inner resize-none"><?php echo esc_textarea($bio); ?></textarea>
                        </div>
                    </div>
                </div>

            </section>

            <!-- SEÇÃO: SENHA -->
            <section class="space-y-8 pt-12 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-black text-primary uppercase tracking-tighter">Senha</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Nova senha</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="new_password" placeholder="Mínimo 8 caracteres" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-900 dark:text-white font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-inner pl-6 pr-14">
                            <button type="button" onclick="togglePasswordVisibility('new_password')" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                        <div class="space-y-3 pt-4">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest leading-none">Sua senha deve conter:</p>
                            <ul class="text-[10px] font-bold text-slate-400 space-y-1.5 list-disc list-inside uppercase tracking-widest">
                                <li id="req-length">• 8 caracteres mínimo</li>
                                <li id="req-digit">• 1 dígito</li>
                                <li id="req-lower">• 1 minúscula</li>
                                <li id="req-upper">• 1 maiúscula</li>
                                <li id="req-special">• 1 caractere especial (ex: ! @ # $)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Confirmação da nova senha</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Repita sua nova senha" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl py-4 px-6 text-slate-900 dark:text-white font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-inner pl-6 pr-14">
                            <button type="button" onclick="togglePasswordVisibility('confirm_password')" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Botão de Salvar Final -->
            <div class="flex justify-center pt-12">
                <button type="submit" class="bg-primary text-white px-20 py-6 rounded-[28px] font-black text-[12px] uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:-translate-y-1 active:scale-95 transition-all flex items-center gap-3">
                    SALVAR PERFIL <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
function togglePasswordVisibility(id) {
    const input = document.getElementById(id);
    const btn = input.nextElementSibling.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerText = 'visibility_off';
    } else {
        input.type = 'password';
        btn.innerText = 'visibility';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sts-full-profile-form');
    const alerts = document.getElementById('profile-alerts');

    // Validação de Senha em Tempo Real
    const newPass = document.getElementById('new_password');
    newPass.addEventListener('input', function() {
        const val = this.value;
        const reqs = {
            'req-length': val.length >= 8,
            'req-digit': /[0-9]/.test(val),
            'req-lower': /[a-z]/.test(val),
            'req-upper': /[A-Z]/.test(val),
            'req-special': /[^A-Za-z0-9]/.test(val)
        };

        for (const [id, met] of Object.entries(reqs)) {
            const el = document.getElementById(id);
            if (met) {
                el.classList.replace('text-slate-400', 'text-green-500');
            } else {
                el.classList.replace('text-green-500', 'text-slate-400');
            }
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const oldBtnText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = `<span class="animate-spin inline-block size-4 border-2 border-white/30 border-t-white rounded-full"></span> AGUARDE...`;

        const formData = new FormData(form);
        formData.append('action', 'sts_update_full_profile');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            alerts.classList.remove('hidden');
            if (res.success) {
                alerts.innerHTML = `<div class="bg-green-500 text-white p-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-green-500/20">${res.data.message}</div>`;
                setTimeout(() => {
                    window.location.href = '<?php echo home_url("/meu-painel"); ?>';
                }, 2000);
            } else {
                alerts.innerHTML = `<div class="bg-red-500 text-white p-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20">${res.data}</div>`;
                btn.disabled = false;
                btn.innerHTML = oldBtnText;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
});
</script>

<?php get_footer(); ?>
