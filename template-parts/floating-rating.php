<?php
/**
 * Componente: Barra Flutuante de Avaliação (Floating Slide-in)
 * Estratégia: Aparece após 60% de scroll para coletar votos de usuários engajados.
 */
$post_id = get_the_ID();
$cookie_name = 'sts_rated_' . $post_id;

// Não exibe se o usuário já votou nesta sessão/cookie
if (isset($_COOKIE[$cookie_name])) return;
?>

<div id="floating-rating-bar" class="fixed bottom-6 right-6 md:w-[320px] bg-white dark:bg-slate-800 rounded-[28px] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 dark:border-slate-700 p-5 z-[150] transform translate-y-[200%] transition-all duration-700 ease-out flex flex-col items-center text-center opacity-0 pointer-events-none">
    
    <!-- ID Independente para o Flutuante -->
    <input type="hidden" id="float_rating_post_id" value="<?php echo $post_id; ?>">
    
    <!-- Botão de Fechar -->
    <button id="close-floating-rating" class="absolute -top-2 -right-2 size-8 bg-white dark:bg-slate-700 rounded-full shadow-lg border border-slate-100 dark:border-slate-600 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors pointer-events-auto" aria-label="Fechar">
        <span class="material-symbols-outlined text-base">close</span>
    </button>

    <div class="mb-3">
        <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Gostou da Receita?</span>
        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Esquente nosso fogão com sua <span class="text-primary italic">nota</span>!</h4>
    </div>

    <div class="flex items-center justify-center gap-1.5 mb-2" id="floating-stars">
        <?php for($i=1; $i<=5; $i++) : ?>
            <button type="button" class="floating-star-btn p-1 transition-all hover:scale-125 pointer-events-auto" data-value="<?php echo $i; ?>">
                <span class="material-symbols-outlined text-3xl text-slate-200 dark:text-slate-600 transition-colors">star</span>
            </button>
        <?php endfor; ?>
    </div>

    <p class="text-[9px] text-slate-400 font-medium tracking-tight">Avaliar nos ajuda a criar mais receitas gratuitas 😍</p>

    <!-- Overlay de Sucesso Interno -->
    <div id="floating-success" class="hidden absolute inset-0 bg-white/95 dark:bg-slate-800/95 rounded-[28px] flex flex-col items-center justify-center p-4 z-10 animate-in fade-in zoom-in-95">
        <div class="size-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-2">
            <span class="material-symbols-outlined text-emerald-500 text-2xl">check_circle</span>
        </div>
        <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">OBRIGADO!</span>
        <span class="text-[10px] text-slate-500 font-bold mt-1">Sua nota vale ouro.</span>
    </div>
</div>

<style>
    /* Transições e Estilos Específicos */
    #floating-rating-bar.active {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }
    .floating-star-btn.active .material-symbols-outlined,
    .floating-star-btn:hover .material-symbols-outlined,
    .floating-star-btn.hover .material-symbols-outlined {
        color: #fbbf24 !important; /* amber-400 */
        font-variation-settings: 'FILL' 1;
    }
</style>
