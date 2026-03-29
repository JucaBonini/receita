<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    $subtitle    = get_post_meta(get_the_ID(), '_ebook_subtitle', true);
    $pdf_url     = get_post_meta(get_the_ID(), '_ebook_pdf', true);
    $ebook_type  = get_post_meta(get_the_ID(), '_ebook_type', true) ?: 'free';
    $ebook_price = get_post_meta(get_the_ID(), '_ebook_price', true);
?>

<div id="ebook-app-root" class="bg-slate-50 dark:bg-slate-950 min-h-screen font-primary">
    
<div id="ebook-app-root" class="bg-slate-50 dark:bg-slate-950 min-h-screen font-primary">

    <!-- Header de Revista (Sem Fixar/Sobrepor) -->
    <header class="w-full bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 pt-12 pb-16 md:pt-20 md:pb-24 px-6 relative overflow-hidden">
        <!-- Detalhe Decorativo de Fundo -->
        <div class="absolute top-0 right-0 size-80 bg-primary/5 blur-[100px] rounded-full pointer-events-none"></div>
        
        <div class="max-w-[1400px] mx-auto text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-3 text-primary font-black uppercase tracking-[0.4em] text-[10px] mb-6">
                <span class="size-2 bg-primary rounded-full animate-pulse"></span>
                <?php echo ($ebook_type === 'paid') ? 'PREMIUM ACCESS' : 'FREE CONTENT'; ?>
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-8xl font-black text-slate-900 dark:text-white leading-[0.95] tracking-tighter uppercase mb-6 max-w-5xl">
                <?php the_title(); ?>
            </h1>
            <?php if ($subtitle) : ?>
                <p class="text-[12px] md:text-lg font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] italic max-w-3xl leading-relaxed">
                    <?php echo $subtitle; ?>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8 lg:px-12 py-8 md:py-16">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-start">
            
            <!-- Sidebar Integrada (Arquitetura Moderna) -->
            <aside class="w-full lg:w-80 shrink-0 order-2 lg:order-1">
                <div class="bg-white dark:bg-slate-900 rounded-[40px] p-8 border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none text-center">
                    <div class="size-28 bg-slate-50 dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden mx-auto mb-8 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                        <?php if (has_post_thumbnail()) : the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); endif; ?>
                    </div>

                    <div class="space-y-4">
                        <div class="inline-flex px-5 py-2 rounded-full <?php echo ($ebook_type === 'paid') ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600'; ?> text-[9px] font-black uppercase tracking-widest mb-6">
                            <?php echo ($ebook_type === 'paid') ? '💎 Assinatura Premium' : '✅ Leitura Grátis'; ?>
                        </div>
                        
                        <?php if ($ebook_type === 'free') : ?>
                            <button id="btn-focus-mode" class="w-full py-5 bg-slate-900 dark:bg-primary text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:scale-[1.02] shadow-xl transition-all active:scale-95">MODO IMERSIVO</button>
                        <?php else : ?>
                            <div class="bg-slate-50 dark:bg-slate-800 py-6 rounded-2xl border border-slate-100 dark:border-slate-700 mb-4">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Custo do Investimento</p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white">R$ <?php echo esc_html($ebook_price) ?: '9,90'; ?></p>
                            </div>
                        <?php endif; ?>

                        <a href="<?php echo get_post_type_archive_link('ebook'); ?>" class="block w-full py-3 text-[10px] font-black text-slate-400 hover:text-primary transition-all uppercase tracking-[0.3em]">Voltar Biblioteca</a>
                    </div>
                </div>
            </aside>

            <!-- Área Principal (Reader UX Master) -->
            <section class="flex-grow w-full min-w-0 order-1 lg:order-2">
                <div id="content-viewer" class="w-full relative animate-in fade-in duration-1000">
                    
                    <?php if ($ebook_type === 'paid') : ?>
                        <!-- CHECKOUT LUXO (Apple/Stripe Style) -->
                        <div id="checkout-card" class="bg-white dark:bg-slate-900 rounded-[48px] md:rounded-[64px] border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden min-h-[500px] flex flex-col justify-center items-center p-8 md:p-20 text-center relative group">
                            
                            <!-- 1. State: Sales -->
                            <div id="st-sales" class="max-w-xl mx-auto z-10">
                                <span class="material-symbols-outlined text-6xl text-primary mb-8 font-bold">workspace_premium</span>
                                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 uppercase tracking-tighter">Conteúdo <span class="text-primary italic">Exclusivo</span></h2>
                                <p class="text-sm md:text-lg text-slate-500 mb-12 leading-relaxed uppercase tracking-widest font-medium">Garanta agora seu exemplar digital com pagamento único e liberação instantânea.</p>
                                <button onclick="nextStep('pay')" class="group relative px-14 py-6 bg-primary text-white rounded-full font-black text-[11px] uppercase tracking-[0.4em] shadow-2xl shadow-primary/40 overflow-hidden hover:scale-105 active:scale-95 transition-all">
                                    <span class="relative z-10">LIBERAR AGORA</span>
                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                                </button>
                            </div>

                            <!-- 2. State: Payment -->
                            <div id="st-pay" class="hidden w-full max-w-2xl mx-auto z-10 text-left animate-in slide-in-from-bottom-5 duration-500">
                                <div class="flex items-center justify-between mb-12">
                                    <button onclick="nextStep('sales')" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest flex items-center gap-2 transition-all">
                                        <span class="material-symbols-outlined text-sm">arrow_back</span> Sair
                                    </button>
                                    <span class="text-[9px] font-black text-emerald-500 border border-emerald-500/20 px-4 py-2 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-inner">
                                        <span class="material-symbols-outlined text-sm">shield_with_heart</span> Pagamento 100% Seguro
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-6">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-2">Identificação</p>
                                        <input type="text" id="p-cpf" placeholder="CPF (Apenas números)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-5 px-8 text-sm font-bold focus:ring-2 focus:ring-primary transition-all">
                                        <input type="text" id="p-phone" placeholder="Celular (DDD + Número)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-5 px-8 text-sm font-bold focus:ring-2 focus:ring-primary transition-all">
                                        
                                        <div class="flex gap-2 p-2 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-inner">
                                            <button onclick="setMethod('pix')" id="m-pix" class="flex-1 py-4 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl">PIX</button>
                                            <button onclick="setMethod('card')" id="m-card" class="flex-1 py-4 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest">CARTÃO</button>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-between">
                                        <div id="card-box" class="hidden space-y-4 animate-in fade-in">
                                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-2">Dados Cartão</p>
                                             <input type="text" placeholder="Número do Cartão" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-4 px-6 text-sm font-bold">
                                             <div class="grid grid-cols-2 gap-3">
                                                 <input type="text" placeholder="Expira" class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-4 text-center text-sm font-bold">
                                                 <input type="text" placeholder="CVV" class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-4 text-center text-sm font-bold">
                                             </div>
                                        </div>
                                        <div id="pix-box" class="py-12 text-center text-emerald-500 font-black animate-pulse uppercase text-xs tracking-[0.2em] flex flex-col items-center gap-4">
                                            <span class="material-symbols-outlined text-4xl">bolt</span>
                                            Desbloqueio em 30 segundos
                                        </div>
                                        <div class="mt-8 border-t border-slate-100 dark:border-slate-800 pt-8">
                                            <div class="flex justify-between items-end mb-6 px-2">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total:</span>
                                                <span class="text-3xl font-black text-slate-900 dark:text-white">R$ <?php echo esc_html($ebook_price) ?: '9,90'; ?></span>
                                            </div>
                                            <button id="btn-final-pay" onclick="doPayment()" class="w-full py-6 bg-slate-900 dark:bg-primary text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                                                <span id="txt-pay">Pagar e Liberar</span> <span class="material-symbols-outlined">rocket_launch</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. State: Success/PIX -->
                            <div id="st-success" class="hidden w-full max-w-xl mx-auto z-10 text-center animate-in zoom-in-95 duration-500">
                                <span class="material-symbols-outlined text-7xl text-emerald-500 mb-8 font-black">check_circle</span>
                                <h4 class="text-3xl font-black text-slate-900 dark:text-white mb-2 uppercase tracking-tighter italic">Pedido Realizado!</h4>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-12">Estamos aguardando o pagamento via PIX</p>
                                
                                <div id="pix-area" class="bg-slate-50 dark:bg-slate-800 p-10 rounded-[40px] border border-slate-100 dark:border-slate-700 shadow-inner mb-10">
                                     <div id="qr-target" class="size-60 bg-white mx-auto mb-8 p-4 rounded-3xl flex items-center justify-center border-2 border-slate-100 overflow-hidden shadow-xl"></div>
                                     <input id="pix-val" readonly class="w-full bg-slate-100 dark:bg-slate-900 border-none text-[10px] font-mono text-center mb-6 py-4 rounded-xl text-slate-600 dark:text-slate-400">
                                     <button onclick="copyPixCode()" class="text-[10px] font-black text-primary uppercase tracking-[0.4em] hover:underline p-2">Copiar Código PIX</button>
                                </div>
                                <a href="javascript:location.reload()" class="text-[9px] font-black text-slate-300 hover:text-slate-900 uppercase tracking-widest transition-all">Pagar depois</a>
                            </div>

                        </div>

                        <script>
                            let method = 'pix';
                            const ebookId = <?php echo get_the_ID(); ?>;
                            const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

                            function nextStep(s) { 
                                ['st-sales', 'st-pay', 'st-success'].forEach(v => document.getElementById(v).classList.add('hidden'));
                                document.getElementById('st-' + s).classList.remove('hidden');
                            }
                            function setMethod(m) {
                                method = m;
                                document.getElementById('m-pix').className = m === 'pix' ? 'flex-1 py-4 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl' : 'flex-1 py-4 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest';
                                document.getElementById('m-card').className = m === 'card' ? 'flex-1 py-4 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl' : 'flex-1 py-4 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest';
                                document.getElementById('card-box').classList.toggle('hidden', m === 'pix');
                                document.getElementById('pix-box').classList.toggle('hidden', m === 'card');
                            }
                            async function doPayment() {
                                const b = document.getElementById('btn-final-pay'); b.disabled = true; document.getElementById('txt-pay').innerText = 'PROCESSANDO...';
                                const params = new URLSearchParams({ action: 'sts_process_payment', ebook_id: ebookId, method: method, payer_cpf: document.getElementById('p-cpf').value, payer_phone: document.getElementById('p-phone').value });
                                try {
                                    const r = await fetch(ajaxUrl, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: params.toString() });
                                    const j = await r.json();
                                    if(j.success) {
                                        nextStep('success');
                                        if(j.data.type === 'pix') {
                                            document.getElementById('qr-target').innerHTML = `<img src="${j.data.qrcode}" class="w-full">`;
                                            document.getElementById('pix-val').value = j.data.copy;
                                        }
                                    } else { alert(j.data.message); b.disabled = false; document.getElementById('txt-pay').innerText = 'Pagar e Liberar'; }
                                } catch(e) { alert('Falha na Conexão'); b.disabled = false; }
                            }
                            function copyPixCode() { const c = document.getElementById('pix-val'); c.select(); document.execCommand('copy'); alert('Código Copiado!'); }
                        </script>

                    <?php elseif ($pdf_url) : ?>
                        <!-- LEITOR PDF ULTRA-CORE (Ergonômico, Fluido e Responsivo) -->
                        <div id="pdf-frame" class="w-full bg-white dark:bg-slate-900 rounded-[48px] md:rounded-[64px] border border-slate-100 dark:border-slate-800 shadow-2xl flex flex-col relative transition-all duration-700 overflow-hidden">
                            
                            <!-- Toolbar do Reader (Sutil, Transparente e Funcional - Não Fixo) -->
                            <div class="px-8 py-5 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between z-[100]">
                                <div class="flex items-center gap-6">
                                    <div class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[11px] font-black tracking-widest shadow-xl flex items-center gap-2">
                                        <span class="text-primary" id="cur-p">1</span> <span class="opacity-20">/</span> <span id="tot-p">0</span>
                                    </div>
                                </div>
                                <button id="close-focus" class="hidden px-10 py-3 bg-red-500 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">close_fullscreen</span> Sair do Foco
                                </button>
                            </div>

                            <!-- Viewport de Leitura (Auto-Sized para qualquer tela) -->
                            <div id="pdf-viewport" class="w-full h-[70vh] md:h-[85vh] overflow-y-auto p-4 md:p-12 lg:p-14 bg-slate-50 dark:bg-slate-950/20 scroll-smooth custom-scrollbar"></div>
                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
                        <script>
                            const pUrl = '<?php echo esc_url($pdf_url); ?>';
                            const vPort = document.getElementById('pdf-viewport');
                            pdfjsLib.getDocument(pUrl).promise.then(async (pdf) => {
                                document.getElementById('tot-p').textContent = pdf.numPages;
                                const renderAll = async () => {
                                    vPort.innerHTML = '';
                                    // Cálculo Matemático UX: largura total menos margens variáveis (20px mobile, 80px desktop)
                                    const scale = (vPort.clientWidth - (window.innerWidth < 768 ? 40 : 120)) / 595;
                                    for(let i=1; i<=pdf.numPages; i++) {
                                        const page = await pdf.getPage(i);
                                        const view = page.getViewport({ scale });
                                        const canvas = document.createElement('canvas');
                                        canvas.className = 'w-full h-auto bg-white shadow-2xl rounded-2xl md:rounded-[40px] mb-12 transform hover:scale-[1.01] transition-transform duration-500';
                                        canvas.dataset.pageIdx = i; canvas.height = view.height; canvas.width = view.width;
                                        await page.render({canvasContext: canvas.getContext('2d'), viewport: view}).promise;
                                        vPort.appendChild(canvas);
                                    }
                                }
                                await renderAll();
                                window.addEventListener('resize', renderAll);
                                vPort.onscroll = () => {
                                    vPort.querySelectorAll('canvas').forEach(c => { if(c.getBoundingClientRect().top >= 0 && c.getBoundingClientRect().top < 400) document.getElementById('cur-p').innerText = c.dataset.pageIdx; });
                                    const pct = (vPort.scrollTop / (vPort.scrollHeight - vPort.clientHeight)) * 100;
                                    document.getElementById('reading-progress').style.width = pct + '%';
                                };
                            });
                            function toggleFocus() {
                                const f = document.getElementById('pdf-frame');
                                f.classList.toggle('fixed'); f.classList.toggle('inset-0'); f.classList.toggle('z-[2000]'); f.classList.toggle('rounded-none');
                                document.getElementById('close-focus').classList.toggle('hidden');
                                document.body.classList.toggle('overflow-hidden');
                            }
                            if(document.getElementById('btn-focus-mode')) document.getElementById('btn-focus-mode').onclick = toggleFocus;
                            document.getElementById('close-focus').onclick = toggleFocus;
                        </script>
                    <?php else : ?>
                        <div class="bg-white dark:bg-slate-900 p-8 md:p-20 rounded-[48px] border border-slate-100 dark:border-slate-800 shadow-xl prose prose-slate dark:prose-invert max-w-none">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</main>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
</style>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
