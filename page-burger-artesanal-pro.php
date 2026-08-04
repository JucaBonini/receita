<?php
/*
Template Name: Page Burger
*/
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Artesanal Pro - O Guia Definitivo</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        :root {
            --primary: #E74C3C;
            --primary-dark: #C0392B;
            --secondary: #F39C12;
            --dark: #2C3E50;
            --light: #ECF0F1;
            --success: #27AE60;
        }
        
        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header */
        .header {
            background: var(--dark);
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* Hero Section */
        .hero {
            padding: 2rem 1.5rem;
            text-align: center;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80') no-repeat center center/cover;
            color: white;
        }
        
        .hero h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--secondary);
            margin: 1rem 0;
        }
        
        .old-price {
            text-decoration: line-through;
            opacity: 0.7;
            font-size: 1.5rem;
        }
        
        /* CTA Button */
        .cta-button {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 1rem 0;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 90%;
            max-width: 350px;
        }
        
        .cta-button:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Features */
        .features {
            padding: 2rem 1.5rem;
        }
        
        .features h3 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .feature-icon {
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .feature-text h4 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        /* Testimonials */
        .testimonials {
            background: var(--light);
            padding: 2rem 1.5rem;
        }
        
        .testimonials h3 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }
        
        .testimonial {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 1rem;
        }
        
        .testimonial-author {
            font-weight: bold;
            color: var(--primary);
        }
        
        /* Bonus Section */
        .bonus {
            padding: 2rem 1.5rem;
            background: var(--dark);
            color: white;
            text-align: center;
        }
        
        .bonus h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .bonus-item {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .bonus-item h4 {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        
        /* Guarantee */
        .guarantee {
            padding: 2rem 1.5rem;
            text-align: center;
            background: var(--success);
            color: white;
        }
        
        .guarantee h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.9rem;
        }
        
        /* Responsive Adjustments */
        @media (min-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }
            
            .hero h2 {
                font-size: 2.2rem;
            }
            
            .price {
                font-size: 3rem;
            }
            
            .features, .testimonials, .bonus, .guarantee {
                padding: 3rem 2rem;
            }
            
            .feature-item {
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Burger Artesanal Pro</h1>
        <p>O Guia Definitivo para Hambúrgueres Incríveis</p>
    </header>
    
    <section class="hero">
        <h2>Domine a Arte do Hambúrguer Artesanal em Casa!</h2>
        <p>Descubra os segredos dos melhores chefs para hambúrgueres suculentos e cheios de sabor</p>
        
        <div class="price">
            <span class="old-price">R$ 47,99</span>
            <div>R$ 9,99</div>
        </div>
        
        <a href="#oferta" class="cta-button">QUERO MEU E-BOOK AGORA!</a>
        
        <p>Garanta hoje e receba 3 bônus exclusivos!</p>
    </section>
    
    <section class="features">
        <h3>O Que Você Vai Aprender</h3>
        
        <div class="feature-item">
            <div class="feature-icon">1</div>
            <div class="feature-text">
                <h4>50 Receitas Testadas e Aprovadas</h4>
                <p>Do clássico ao gourmet, com combinações de ingredientes que vão impressionar.</p>
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-icon">2</div>
            <div class="feature-text">
                <h4>Técnicas Profissionais de Preparo</h4>
                <p>Como moer a carne corretamente, escolher os cortes ideais e evitar hambúrgueres ressecados.</p>
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-icon">3</div>
            <div class="feature-text">
                <h4>Molhos e Acompanhamentos Exclusivos</h4>
                <p>Combinações que elevam seu hambúrguer a outro nível de sabor.</p>
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-icon">4</div>
            <div class="feature-text">
                <h4>Dicas de Grelha e Panela</h4>
                <p>Como conseguir aquele grelhado perfeito, mesmo sem churrasqueira profissional.</p>
            </div>
        </div>
    </section>
    
    <section class="testimonials">
        <h3>O Que Estão Dizendo</h3>
        
        <div class="testimonial">
            <p class="testimonial-text">"Comprei 3 e-books de hambúrguer antes desse, mas o Burger Artesanal Pro foi o único que me ensinou o segredo do ponto perfeito da carne. Valeu cada centavo!"</p>
            <p class="testimonial-author">- Rodrigo, Curitiba</p>
        </div>
        
        <div class="testimonial">
            <p class="testimonial-text">"Minha família não acreditou que eu fiz os hambúrgueres em casa. O molho especial do bônus virou nosso segredo de família!"</p>
            <p class="testimonial-author">- Juliana, Belo Horizonte</p>
        </div>
        
        <div class="testimonial">
            <p class="testimonial-text">"Sou dono de uma lancheria e apliquei as técnicas do e-book. Em 1 mês, nossas vendas de hambúrguer aumentaram 40%. Simplesmente sensacional!"</p>
            <p class="testimonial-author">- Marcos, Porto Alegre</p>
        </div>
        
        <div class="testimonial">
            <p class="testimonial-text">"Depois do Burger Artesanal Pro, meus hambúrgueres ficaram tão bons que meus amigos acham que eu comprei de uma hamburgueria gourmet!"</p>
            <p class="testimonial-author">- Carlos, São Paulo</p>
        </div>
        
        <div class="testimonial">
            <p class="testimonial-text">"As técnicas de preparo da carne mudaram tudo! Agora meus hambúrgueres são suculentos e cheios de sabor."</p>
            <p class="testimonial-author">- Ana, Rio de Janeiro</p>
        </div>
    </section>
    
    <section class="bonus" id="oferta">
        <h3>Bônus Exclusivos por Tempo Limitado</h3>
        
        <div class="bonus-item">
            <h4>Bônus 1: Pack de artes editaveis para hamburgueria</h4>
            <p>+ de 50 artes editaveis para você montar seu flyers e banners (valor R$ 24)</p>
        </div>
        
        <div class="bonus-item">
            <h4>Bônus 2: Lista de Compras Completa</h4>
            <p>PDF pronto para imprimir com todos os equipamentos necessários (valor R$ 7,99)</p>
        </div>
        
        <div class="bonus-item">
            <h4>Bônus 3: Calculadora Custo de Venda Hambúrguer Artesanal</h4>
            <p>"Como calcular os custos e precificar para não ter prejuijo" (valor R$ 16)</p>
        </div>
        
        <div class="price" style="color: white; margin-top: 1.5rem;">
            <span class="old-price">Valor Total: R$ 47,99</span>
            <div>HOJE POR APENAS: R$ 9,99</div>
        </div>
        
        <a href="https://pay.kiwify.com.br/ujEOKsQ" target="_blank" class="cta-button" style="background: var(--secondary); color: var(--dark);">GARANTIR MEU E-BOOK + BÔNUS</a>
    </section>
    
    <section class="guarantee">
        <h3>Garantia de 7 Dias</h3>
        <p>Se você não ficar satisfeito com o Burger Artesanal Pro, devolvemos seu dinheiro sem perguntas. Sem riscos para você!</p>
    </section>
    
    <footer class="footer">
        <p>Burger Artesanal Pro &copy; 2025 - Todos os direitos reservados</p>
        <p><a href="#" style="color: var(--secondary);">Termos de Uso</a> | <a href="#" style="color: var(--secondary);">Política de Privacidade</a></p>
    </footer>
</body>
</html>