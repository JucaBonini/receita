<?php
/**
 * Template Name: Landing Page Semana Santa (Estilo Gemini Dinâmico)
 * Description: Versão fiel ao layout do Gemini, com receitas dinâmicas das categorias Páscoa e Semana Santa e Achadinhos Shopee Estáticos.
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Semana Santa 2026: Cardápio Completo e Receitas Econômicas - Descomplicando Receitas</title>
    <meta name="description" content="Guia definitivo para a Semana Santa 2026. Receitas de bacalhau, peixes baratos e sobremesas de Páscoa testadas pela Chef Mary Rodrigues. Acesse agora!">
    <link rel="canonical" href="https://descomplicandoreceitas.com.br/semana-santa-2026/">
    
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root { --primary: #ec5b13; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        
        /* Ajuste para o Admin Bar do WordPress */
        html { margin-top: 0px !important; }
        * html body { margin-top: 0px !important; }
        @media screen and ( max-width: 782px ) {
            html { margin-top: 0px !important; }
            * html body { margin-top: 0px !important; }
        }
    </style>
    
    <?php wp_head(); ?>
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    <header class="bg-white border-b sticky top-0 z-50 py-4 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <div class="flex-shrink-0">
                <?php 
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<a href="' . home_url() . '" class="font-black text-2xl text-primary tracking-tighter">' . get_bloginfo('name') . '</a>';
                }
                ?>
            </div>
            <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H?utm_source=landingpage&utm_medium=header_button&utm_campaign=semanasanta2026" class="bg-green-500 text-white px-4 py-2 rounded-full text-xs font-bold hover:bg-green-600 transition tracking-widest uppercase">CANAL EXCLUSIVO WHATSAPP</a>
        </div>
    </header>

    <section class="relative bg-slate-900 text-white py-16 px-4 overflow-hidden min-h-[400px] flex items-center">
        <div class="absolute inset-0 opacity-30">
            <?php 
            // Tenta pegar a imagem do primeiro post da categoria para o fundo do Hero
            $hero_q = new WP_Query(array('category_name' => 'pascoa,semana-santa', 'posts_per_page' => 1));
            $hero_img = "https://descomplicandoreceitas.com.br/wp-content/uploads/2025/12/Bacalhau-no-forno-para-fazer-no-natal.webp";
            if ($hero_q->have_posts()) {
                $hero_q->the_post();
                if (has_post_thumbnail()) $hero_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
                wp_reset_postdata();
            }
            ?>
            <img src="<?php echo $hero_img; ?>" alt="Fundo Semana Santa" class="w-full h-full object-cover">
        </div>
        <div class="relative max-w-4xl mx-auto text-center z-10">
            <span class="bg-primary text-white px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">Especial 2026</span>
            <h1 class="text-4xl md:text-6xl font-black mt-4 leading-tight">Semana Santa: O Cardápio que une a Tradição ao seu Bolso</h1>
            <p class="text-lg md:text-xl text-slate-300 mt-6 max-w-2xl mx-auto">Receitas de Bacalhau, Peixes Econômicos e Sobremesas de Páscoa testadas pela Chef Mary Rodrigues.</p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="#cardapio" class="bg-primary hover:scale-105 transition py-4 px-8 rounded-2xl font-black text-sm uppercase">Ver Receitas de Hoje</a>
                <a href="#achadinhos" class="bg-white text-slate-900 hover:bg-slate-100 py-4 px-8 rounded-2xl font-black text-sm uppercase">Ofertas da Shopee</a>
            </div>
        </div>
    </section>

    <!-- Espaço Publicitário -->
    <div class="max-w-6xl mx-auto py-8 px-4 text-center">
        <div class="bg-slate-200 h-[100px] flex items-center justify-center text-slate-400 text-xs uppercase tracking-widest border border-dashed border-slate-300">
            <?php if (function_exists('sts_display_ad')) sts_display_ad('header_top'); else echo "Espaço Publicitário AdSense"; ?>
        </div>
    </div>

    <main id="cardapio" class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
            $args = array(
                'category_name' => 'pascoa,semana-santa',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $query = new WP_Query($args);
            
            // Rótulos do Gemini para manter o estilo
            $labels = array('Sexta-feira Santa', 'Dica de Economia', 'Domingo de Páscoa', 'Destaque', 'Econômica', 'Sobremesa');
            $i = 0;

            if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large') ?: "https://via.placeholder.com/600x400";
                $label = isset($labels[$i]) ? $labels[$i] : 'Especial';
                $i++;
            ?>
            <article class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 flex flex-col hover:border-primary transition group">
                <img src="<?php echo $thumb; ?>" alt="<?php the_title(); ?>" class="h-56 w-full object-cover">
                <div class="p-6 flex flex-col flex-1">
                    <span class="text-primary font-bold text-[10px] uppercase"><?php echo $label; ?></span>
                    <h2 class="text-xl font-black mt-2 leading-tight"><?php the_title(); ?></h2>
                    <p class="text-slate-500 text-sm mt-4 flex-1"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    <a href="<?php the_permalink(); ?>" class="mt-6 inline-block text-primary font-bold border-b-2 border-primary pb-1 self-start group-hover:pr-2 transition-all">Ver Receita Completa &rarr;</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); else: ?>
                <p class="col-span-full text-center text-slate-400 italic">Nenhuma receita encontrada nestas categorias ainda.</p>
            <?php endif; ?>

        </div>
    </main>

    <!-- Achadinhos Dinâmicos Shopee (ESTÁTICOS) -->
    <section id="achadinhos" class="bg-slate-900 text-white py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-5xl font-black italic">Achadinhos de Páscoa 🐰</h2>
                <p class="text-slate-400 mt-4 leading-relaxed tracking-tight">Utensílios e ingredientes que usamos e recomendamos (Entrega Rápida Shopee)</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                <?php 
                // Definindo os produtos estáticos para facilitar sua edição no código
                $produtos = array(
                    array('nome' => 'Conj. de Assadeiras Quadradas Nadir 3 Peças', 'link' => 'https://s.shopee.com.br/1qXlnGQ1Vg', 'imagem' => 'https://down-br.img.susercontent.com/file/sg-11134201-81zvd-mmji4n4lay33db@resize_w450_nl.webp'),
                    array('nome' => 'Mixer Multiuso', 'link' => 'https://s.shopee.com.br/3VfzmN1HRs', 'imagem' => 'https://down-br.img.susercontent.com/file/sg-11134201-7rd61-lvp04k94lq9b97@resize_w450_nl.webp'),
                    array('nome' => 'Jogo Completo de Utensílios de Cozinha de Silicone Itens Essenciais para sua Casa KITC/12 JIMMY', 'link' => 'https://s.shopee.com.br/1BI50Cg7Xx', 'imagem' => 'https://down-br.img.susercontent.com/file/sg-11134201-8262z-mko1f024hjb7bd@resize_w450_nl.webp'),                   
                );

                foreach($produtos as $prod): 
                ?>
                <div class="bg-[#1e293b] p-6 rounded-3xl border border-slate-700 text-center hover:border-primary transition duration-300">
                    <div class="bg-slate-700/50 h-40 rounded-2xl mb-6 flex items-center justify-center text-[10px] text-slate-500 uppercase font-black tracking-widest border border-slate-600">
                        <?php if ($prod['imagem']): ?>
                            <img src="<?php echo $prod['imagem']; ?>" alt="<?php echo $prod['nome']; ?>" class="w-full h-full object-cover rounded-xl">
                        <?php else: ?>
                            IMAGEM PRODUTO
                        <?php endif; ?>
                    </div>
                    <h3 class="text-sm font-bold mb-6 text-white h-5 overflow-hidden"><?php echo $prod['nome']; ?></h3>
                    <a href="<?php echo $prod['link']; ?>" target="_blank" class="block bg-primary hover:scale-105 text-white py-3 rounded-xl text-xs font-black uppercase transition-all tracking-widest shadow-lg shadow-primary/20">Compre na Shopee</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="bg-white py-12 border-t">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">Sobre a Mary Rodrigues</h3>
            <p class="text-slate-500 text-sm mt-4 leading-relaxed italic">"Minha missão no Descomplicando Receitas é transformar ingredientes comuns em refeições extraordinárias para o seu dia a dia, sempre com amor e praticidade."</p>
            <div class="mt-8 pt-8 border-t border-slate-100 flex flex-wrap justify-center gap-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <a href="/sobre-nos">Sobre Nós</a>
                <a href="/politica-de-privacidade">Privacidade</a>
                <a href="/fale-conosco">Fale Conosco</a>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
