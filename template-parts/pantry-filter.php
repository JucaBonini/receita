<?php
/**
 * Componente: Filtro de Dispensa (Advanced Pantry Search)
 */
?>

<section class="pantry-filter bg-white dark:bg-slate-800 rounded-[40px] p-8 sm:p-12 border border-slate-100 dark:border-slate-700/50 shadow-2xl shadow-primary/5 mt-12 mb-20 overflow-hidden relative">
    <!-- Decoração Background -->
    <div class="absolute -top-24 -right-24 size-64 bg-primary/5 rounded-full blur-3xl"></div>
    
    <div class="relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-4">Mecanismo de Busca</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white leading-tight">O que você tem na <span class="text-primary italic">geladeira?</span></h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2">Selecione os ingredientes e descubra o que cozinhar agora.</p>
            </div>
            
            <button id="pantry-search-btn" class="px-8 py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.05] active:scale-95 transition-all">
                BUSCAR RECEITAS
            </button>
        </div>

        <div class="flex flex-wrap gap-3" id="pantry-tags">
            <?php
            // Pegamos as 15 principais tags (ingredientes)
            $tags = get_tags(array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 15
            ));

            foreach ($tags as $tag) :
            ?>
                <button data-tag="<?php echo $tag->slug; ?>" class="pantry-tag-btn px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 hover:border-primary hover:text-primary transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm opacity-40">add</span>
                    <?php echo $tag->name; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    .pantry-tag-btn.is-active {
        background: #ec5b13;
        border-color: #ec5b13;
        color: white !important;
    }
    .pantry-tag-btn.is-active .material-symbols-outlined {
        content: 'check';
        opacity: 1;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tags = document.querySelectorAll('.pantry-tag-btn');
    const searchBtn = document.getElementById('pantry-search-btn');
    let selectedTags = [];

    tags.forEach(btn => {
        btn.addEventListener('click', () => {
            const slug = btn.dataset.tag;
            btn.classList.toggle('is-active');
            
            if (btn.classList.contains('is-active')) {
                selectedTags.push(slug);
                btn.querySelector('span').innerText = 'check';
            } else {
                selectedTags = selectedTags.filter(t => t !== slug);
                btn.querySelector('span').innerText = 'add';
            }
            
            // Atualiza o texto do botão de busca
            if (selectedTags.length > 0) {
                searchBtn.innerText = `BUSCAR COM ${selectedTags.length} ITENS`;
            } else {
                searchBtn.innerText = 'BUSCAR RECEITAS';
            }
        });
    });

    searchBtn.addEventListener('click', () => {
        if (selectedTags.length > 0) {
            // Redireciona para a busca combinada de tags
            window.location.href = `<?php echo home_url('/'); ?>?tag=${selectedTags.join('+')}`;
        } else {
            alert('Selecione pelo menos um ingrediente!');
        }
    });
});
</script>
