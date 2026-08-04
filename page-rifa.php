<?php
/**
 * Template Name: Rifa - Ação Entre Amigos
 * Description: Landing page premium para a campanha de arrecadação do Descomplicando Receitas.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ação Entre Amigos | Descomplicando Receitas</title>
    
    <!-- Otimizações e Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>

    <style>
        /* Reset e Variáveis de Design Moderno */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #f26b21;
            --primary-dark: #d4520e;
            --primary-light: #fef0e6;
            --secondary: #e58f00;
            --secondary-light: #fff7eb;
            --dark: #26160d;
            --dark-light: #3d2617;
            --text-gray: #5c5550;
            --bg-cream: #fffaf5;
            --card-bg: #ffffff;
            --border-color: #f0e6dd;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 8px;
            --shadow-sm: 0 4px 10px rgba(38, 22, 13, 0.03);
            --shadow-md: 0 10px 30px rgba(38, 22, 13, 0.06);
            --shadow-lg: 0 20px 40px rgba(38, 22, 13, 0.12);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-cream);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout Base */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Header */
        .header {
            background-color: var(--dark);
            padding: 20px 0;
            text-align: center;
            border-bottom: 4px solid var(--primary);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .header-logo i {
            color: var(--primary);
            font-size: 2rem;
            animation: beat 2s infinite alternate;
        }

        .header-logo span {
            color: var(--primary);
        }

        @keyframes beat {
            to { transform: scale(1.1); }
        }

        /* Hero Section */
        .hero {
            padding: 80px 0 60px;
            text-align: center;
            background: radial-gradient(circle at 80% 20%, rgba(242, 107, 33, 0.05) 0%, transparent 60%);
            position: relative;
        }

        .badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(242, 107, 33, 0.15);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--dark);
            max-width: 850px;
            margin: 0 auto 20px;
        }

        .hero-title span {
            color: var(--primary);
            position: relative;
            display: inline-block;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 0;
            width: 100%;
            height: 6px;
            background: rgba(242, 107, 33, 0.18);
            border-radius: 2px;
            z-index: -1;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-gray);
            max-width: 650px;
            margin: 0 auto 35px;
            font-weight: 400;
        }

        /* Storytelling Card */
        .story-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin: 0 auto 60px;
            max-width: 850px;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .story-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .story-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .story-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
        }

        .story-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
        }

        .story-body {
            color: var(--text-gray);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .story-body p {
            margin-bottom: 16px;
        }

        .story-body p:last-child {
            margin-bottom: 0;
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Seção de Prêmios */
        .prizes-section {
            padding: 40px 0 80px;
            position: relative;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .section-header p {
            color: var(--text-gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* 1º Lugar Card */
        .grand-prize-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 2px solid rgba(242, 107, 33, 0.15);
            box-shadow: var(--shadow-lg);
            margin-bottom: 40px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
        }

        /* Carousel de Imagens */
        .prize-gallery {
            position: relative;
            background-color: #f7f3ef;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .slides-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
        }

        .slide.active {
            opacity: 1;
            z-index: 10;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 20;
            pointer-events: none;
        }

        .nav-btn {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            color: var(--dark);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: var(--transition);
            pointer-events: auto;
        }

        .nav-btn:hover {
            background-color: var(--primary);
            color: white;
            transform: scale(1.05);
        }

        .gallery-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 20;
        }

        .indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(38, 22, 13, 0.2);
            cursor: pointer;
            transition: var(--transition);
        }

        .indicator.active {
            background-color: var(--primary);
            width: 25px;
            border-radius: 10px;
        }

        .prize-info {
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .place-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #d48500;
            background-color: #fff7e6;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            width: fit-content;
            margin-bottom: 20px;
            border: 1px solid rgba(229, 143, 0, 0.15);
        }

        .place-badge.first {
            color: #d4520e;
            background-color: #fff0e6;
            border: 1px solid rgba(242, 107, 33, 0.15);
        }

        .prize-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .prize-desc {
            color: var(--text-gray);
            font-size: 1.05rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .prize-features {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 30px;
        }

        .prize-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            color: var(--dark-light);
        }

        .prize-features li i {
            color: #2ecc71;
            font-size: 1.2rem;
        }

        /* 2º e 3º Lugar Grid */
        .secondary-prizes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 60px;
        }

        .secondary-prize-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 40px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: var(--transition);
        }

        .secondary-prize-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .ml-mockup {
            width: 100%;
            max-width: 260px;
            height: 160px;
            background: linear-gradient(135deg, #fff159 0%, #ffd100 100%);
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .ml-mockup::after {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.15), transparent);
            transform: skewX(-25deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .ml-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            color: #2d3277;
            font-size: 0.95rem;
        }

        .ml-logo i {
            font-size: 1.2rem;
        }

        .ml-val {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2d3277;
            text-align: right;
        }

        .ml-footer {
            font-size: 0.75rem;
            color: rgba(45, 50, 119, 0.7);
            font-weight: 600;
            text-align: left;
        }

        .secondary-prize-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .secondary-prize-desc {
            color: var(--text-gray);
            font-size: 1rem;
        }

        /* Central CTA Area */
        .cta-container {
            text-align: center;
            padding: 40px 0;
            position: relative;
        }

        .main-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 24px 50px;
            font-size: 1.4rem;
            font-weight: 700;
            border-radius: var(--radius-lg);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 15px 35px rgba(242, 107, 33, 0.4);
            transition: var(--transition);
            width: 100%;
            max-width: 550px;
            animation: pulseBtn 2s infinite;
        }

        @keyframes pulseBtn {
            0% {
                box-shadow: 0 0 0 0 rgba(242, 107, 33, 0.5);
            }
            70% {
                box-shadow: 0 0 0 18px rgba(242, 107, 33, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(242, 107, 33, 0);
            }
        }

        .main-cta-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(242, 107, 33, 0.5);
            background: linear-gradient(135deg, var(--primary-dark), #b83f07);
        }

        .main-cta-btn i {
            font-size: 1.8rem;
        }

        .cta-caption {
            margin-top: 18px;
            font-size: 1.05rem;
            color: var(--text-gray);
            font-weight: 500;
        }

        .cta-caption i {
            color: var(--primary);
            margin-right: 5px;
        }

        /* Selos de Confiança */
        .trust-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 60px 0 80px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 40px 0;
        }

        .trust-item {
            text-align: center;
        }

        .trust-icon {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .trust-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .trust-desc {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* FAQ Section */
        .faq-section {
            padding: 40px 0 80px;
            background-color: #faf4ee;
            border-radius: var(--radius-lg);
            margin-bottom: 80px;
            border: 1px solid var(--border-color);
        }

        .faq-list {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 0 20px;
        }

        .faq-item {
            background-color: var(--card-bg);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .faq-question {
            padding: 24px 30px;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dark);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .faq-question:hover {
            background-color: var(--bg-cream);
        }

        .faq-question i {
            transition: var(--transition);
            color: var(--primary);
            font-size: 1.1rem;
        }

        .faq-answer {
            padding: 0 30px;
            max-height: 0;
            overflow: hidden;
            transition: var(--transition);
            color: var(--text-gray);
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .faq-answer p {
            padding-bottom: 24px;
        }

        .faq-item.active .faq-question {
            color: var(--primary-dark);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            max-height: 400px;
        }

        /* Footer */
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 30px;
            text-align: center;
        }

        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
            text-decoration: none;
            display: inline-block;
        }

        .footer-logo span {
            color: var(--primary);
        }

        .footer-text {
            color: rgba(255,255,255,0.7);
            font-size: 1.05rem;
            margin-bottom: 30px;
        }

        .footer-socials {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .social-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.06);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.1rem;
        }

        .social-icon:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }

        .copyright-text {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 30px;
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
        }

        /* REDIRECT SECURE MODAL */
        .redirect-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(38, 22, 13, 0.85);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .redirect-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .redirect-modal {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            transform: scale(0.9);
            transition: var(--transition);
        }

        .redirect-overlay.active .redirect-modal {
            transform: scale(1);
        }

        .shield-icon {
            width: 80px;
            height: 80px;
            background-color: var(--primary-light);
            color: var(--primary);
            font-size: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            position: relative;
        }

        .shield-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid var(--primary);
            animation: pulse-ring 1.5s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.3); opacity: 0; }
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .modal-text {
            color: var(--text-gray);
            font-size: 1.05rem;
            margin-bottom: 24px;
        }

        .loading-bar {
            width: 100%;
            height: 6px;
            background-color: var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress {
            width: 0%;
            height: 100%;
            background-color: var(--primary);
            border-radius: 10px;
            transition: width 3s linear;
        }

        .countdown-num {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            .grand-prize-card {
                grid-template-columns: 1fr;
            }
            .prize-gallery {
                min-height: 350px;
            }
            .prize-info {
                padding: 40px 30px;
            }
            .trust-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 50px 0 40px;
            }
            .hero-title {
                font-size: 2.2rem;
            }
            .secondary-prizes-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .story-card {
                padding: 24px;
            }
            .main-cta-btn {
                padding: 20px 30px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.8rem;
            }
            .trust-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .header-logo {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header Simples -->
    <header class="header">
        <div class="container">
            <a href="https://descomplicandoreceitas.com.br" class="header-logo">
                <i class="fa-solid fa-cookie-bite"></i> Descomplicando <span>Receitas</span>
            </a>
        </div>
    </header>

    <!-- Hero Area -->
    <section class="hero">
        <div class="container">
            <div class="badge-tag">
                <i class="fa-solid fa-heart"></i> Campanha Solidária
            </div>
            <h1 class="hero-title">Participe da nossa <span>Ação entre Amigos</span> e ajude o site a crescer!</h1>
            <p class="hero-subtitle">Concorra a prêmios incríveis e de quebra nos ajude a continuar criando conteúdos de receitas descomplicadas e gratuitas para você.</p>
            
            <!-- Storytelling -->
            <div class="story-card">
                <div class="story-header">
                    <div class="story-icon">
                        <i class="fa-solid fa-face-smile-wink"></i>
                    </div>
                    <h3>Quem nunca complicou uma receita de brigadeiro, não é mesmo? 😅</h3>
                </div>
                <div class="story-body">
                    <p>O <strong>Descomplicando Receitas</strong> nasceu para ajudar pessoas que querem cozinhar melhor, com menos stress e muito mais sabor. Nosso foco é fazer a cozinha ser um espaço de prazer, não de complicação.</p>
                    <p>Mas, para continuarmos criando novos conteúdos acessíveis, testando receitas deliciosas e mantendo nossos servidores rápidos e no ar, precisamos de uma forcinha das amigas e amigos que nos acompanham.</p>
                    <p>Por isso, criamos essa rifa solidária. Participando, você concorre a prêmios incríveis para a sua casa e de quebra apoia o nosso projeto. ❤️</p>
                    <p><i class="fa-solid fa-kitchen-set"></i> Vamos cozinhar juntos e, claro, torcer muito para ganhar!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Prêmios -->
    <section class="prizes-section" id="premios">
        <div class="container">
            <div class="section-header">
                <h2>Conheça os Prêmios</h2>
                <p>Separamos prêmios especiais com muito carinho para equipar sua cozinha e facilitar suas compras.</p>
            </div>

            <!-- 1º Lugar Card (Grand Prize) -->
            <div class="grand-prize-card">
                <!-- Carrossel de Imagens -->
                <div class="prize-gallery">
                    <div class="slides-container">
                        <!-- Imagem 1 -->
                        <div class="slide active">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/rifa/57bt.webp" alt="Kit Churrasco Maleta Alumínio - Aberto">
                        </div>
                        <!-- Imagem 2 -->
                        <div class="slide">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/rifa/5j8i.webp" alt="Kit Churrasco Maleta Alumínio - Itens">
                        </div>
                        <!-- Imagem 3 -->
                        <div class="slide">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/rifa/n3fg.webp" alt="Kit Churrasco Maleta Alumínio - Fechado">
                        </div>
                    </div>
                    
                    <div class="gallery-nav">
                        <button class="nav-btn" id="prevSlide" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="nav-btn" id="nextSlide" aria-label="Próximo"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>

                    <div class="gallery-indicators" id="indicators">
                        <span class="indicator active" data-index="0"></span>
                        <span class="indicator" data-index="1"></span>
                        <span class="indicator" data-index="2"></span>
                    </div>
                </div>

                <!-- Detalhes do Prêmio -->
                <div class="prize-info">
                    <div class="place-badge first">
                        <i class="fa-solid fa-trophy"></i> 1º LUGAR
                    </div>
                    <h3 class="prize-title">Kit Churrasco Maleta Alumínio 10 Peças</h3>
                    <p class="prize-desc">Um kit de churrasco completo e profissional, acomodado em uma linda e robusta maleta de alumínio. Perfeito para preparar os melhores churrascos em família com total praticidade e estilo.</p>
                    
                    <ul class="prize-features">
                        <li><i class="fa-solid fa-circle-check"></i> Maleta de alumínio resistente de alta durabilidade</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pegador, garfo, espátula e pincel de silicone</li>
                        <li><i class="fa-solid fa-circle-check"></i> Faca profissional de corte e espetos inclusos</li>
                        <li><i class="fa-solid fa-circle-check"></i> Utensílios em aço inox fáceis de limpar</li>
                    </ul>
                </div>
            </div>

            <!-- 2º e 3º Lugar Grid -->
            <div class="secondary-prizes-grid">
                <!-- 2º Lugar -->
                <div class="secondary-prize-card">
                    <div class="place-badge">
                        <i class="fa-solid fa-award"></i> 2º LUGAR
                    </div>
                    
                    <!-- ML Mockup -->
                    <div class="ml-mockup">
                        <div class="ml-logo">
                            <i class="fa-solid fa-handshake"></i> mercado livre
                        </div>
                        <div class="ml-val">
                            R$ 100
                        </div>
                        <div class="ml-footer">
                            Vale-compras Digital
                        </div>
                    </div>

                    <h3 class="secondary-prize-title">Vale-compras de R$ 100</h3>
                    <p class="secondary-prize-desc">R$ 100 de saldo para gastar como quiser no site ou app do Mercado Livre. Milhares de produtos à sua escolha com frete rápido!</p>
                </div>

                <!-- 3º Lugar -->
                <div class="secondary-prize-card">
                    <div class="place-badge">
                        <i class="fa-solid fa-award"></i> 3º LUGAR
                    </div>
                    
                    <!-- ML Mockup -->
                    <div class="ml-mockup" style="background: linear-gradient(135deg, #fff7a1 0%, #ffd100 100%);">
                        <div class="ml-logo">
                            <i class="fa-solid fa-handshake"></i> mercado livre
                        </div>
                        <div class="ml-val">
                            R$ 50
                        </div>
                        <div class="ml-footer">
                            Vale-compras Digital
                        </div>
                    </div>

                    <h3 class="secondary-prize-title">Vale-compras de R$ 50</h3>
                    <p class="secondary-prize-desc">R$ 50 de saldo para usar em qualquer compra no Mercado Livre. Ideal para garantir aquele utensílio de cozinha que você tanto queria!</p>
                </div>
            </div>

            <!-- Botão de Ação -->
            <div class="cta-container">
                <button class="main-cta-btn" id="participateBtn">
                    <i class="fa-solid fa-ticket"></i> QUERO ADQUIRIR MEU NÚMERO AGORA!
                </button>
                <p class="cta-caption"><i class="fa-solid fa-shield-halved"></i> Compra segura garantida pela plataforma <strong>Rifeme</strong></p>
            </div>
        </div>
    </section>

    <!-- Selos de Confiança / Como Funciona -->
    <div class="container">
        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-icon"><i class="fa-brands fa-pix"></i></div>
                <h4 class="trust-title">Pagamento via PIX</h4>
                <p class="trust-desc">Rápido, seguro e com baixa automática imediata do seu número.</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fa-solid fa-shield-heart"></i></div>
                <h4 class="trust-title">Plataforma Segura</h4>
                <p class="trust-desc">Sua compra é processada com criptografia e total proteção de dados na Rifeme.</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h4 class="trust-title">Loteria Federal</h4>
                <p class="trust-desc">O sorteio é baseado na extração oficial da Loteria Federal. Transparência garantida!</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="fa-solid fa-gifts"></i></div>
                <h4 class="trust-title">Apoio Direto</h4>
                <p class="trust-desc">100% do lucro é revertido para melhorias técnicas e novos conteúdos no site.</p>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header">
                <h2>Perguntas Frequentes</h2>
                <p>Tire suas dúvidas rápidas sobre a nossa ação entre amigos solidária.</p>
            </div>

            <div class="faq-list">
                <!-- Pergunta 1 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Como faço para participar da rifa?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>É muito simples! Basta clicar em qualquer botão desta página para ser redirecionado com segurança para a nossa página oficial na plataforma <strong>Rifeme</strong>. Lá, você escolhe a quantidade de números que deseja comprar, preenche seus dados básicos e realiza o pagamento via PIX. Seus números são gerados e confirmados automaticamente.</p>
                    </div>
                </div>

                <!-- Pergunta 2 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Como funciona o sorteio pela Loteria Federal?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O sorteio é associado aos resultados da Loteria Federal. A Rifeme utiliza os últimos dígitos do primeiro prêmio extraído na data correspondente para definir o número vencedor. Isso assegura que o sorteio seja 100% transparente, idôneo e impossível de ser manipulado.</p>
                    </div>
                </div>

                <!-- Pergunta 3 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Quando será realizado o sorteio?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>A data do sorteio será marcada assim que todas as cotas da ação forem reservadas e pagas. Fique tranquilo, a data oficial será divulgada com antecedência no nosso Instagram oficial e na própria página da Rifeme.</p>
                    </div>
                </div>

                <!-- Pergunta 4 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Como receberei meu prêmio caso ganhe?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Nós entraremos em contato direto com os ganhadores pelos dados cadastrados (WhatsApp ou E-mail) no momento da compra. O 1º colocado receberá a maleta do Kit Churrasco diretamente em sua casa via Correios ou Transportadora sem nenhum custo de envio. O 2º e 3º colocados receberão o código digital dos vales-compras do Mercado Livre de forma instantânea.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <a href="https://descomplicandoreceitas.com.br" class="footer-logo">
                    Descomplicando <span>Receitas</span>
                </a>
                <p class="footer-text">Juntos, vamos continuar espalhando receitas práticas, inspiração e muito amor pela cozinha! Obrigado pelo seu valioso apoio.</p>
                
                <div class="footer-socials">
                    <a href="https://www.facebook.com/descomplicandoreceitasofic" class="social-icon" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/descomplicandoreceitasofic" class="social-icon" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://br.pinterest.com/descomplicandoreceitas/" class="social-icon" target="_blank"><i class="fa-brands fa-pinterest-p"></i></a>
                    <a href="https://www.youtube.com/@descomplicandoreceitas" class="social-icon" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                </div>

                <p class="copyright-text">&copy; <?php echo date('Y'); ?> Descomplicando Receitas. Desenvolvido com carinho para simplificar sua cozinha.</p>
            </div>
        </div>
    </footer>

    <!-- MODAL DE REDIRECIONAMENTO SEGURO -->
    <div class="redirect-overlay" id="redirectOverlay">
        <div class="redirect-modal">
            <div class="shield-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 class="modal-title">Redirecionando você...</h3>
            <p class="modal-text">Você está sendo direcionado com total segurança para a plataforma <strong>Rifeme</strong> para concluir seu apoio.</p>
            
            <div class="loading-bar">
                <div class="progress" id="progressBar"></div>
            </div>
            <p class="countdown-num">Abrindo em <span id="counter">3</span> segundos...</p>
        </div>
    </div>

    <!-- Script de Interatividade -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- CARROSSEL DE IMAGENS ---
            const slides = document.querySelectorAll('.slide');
            const indicators = document.querySelectorAll('.indicator');
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');
            let currentSlideIndex = 0;
            let slideInterval;

            function showSlide(index) {
                // Limites
                if (index >= slides.length) {
                    currentSlideIndex = 0;
                } else if (index < 0) {
                    currentSlideIndex = slides.length - 1;
                } else {
                    currentSlideIndex = index;
                }

                // Ajustar classes ativas
                slides.forEach((slide, i) => {
                    if (i === currentSlideIndex) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });

                indicators.forEach((indicator, i) => {
                    if (i === currentSlideIndex) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
            }

            function nextSlide() {
                showSlide(currentSlideIndex + 1);
            }

            function prevSlide() {
                showSlide(currentSlideIndex - 1);
            }

            // Iniciar auto slide
            function startAutoSlide() {
                stopAutoSlide();
                slideInterval = setInterval(nextSlide, 5000);
            }

            function stopAutoSlide() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                }
            }

            // Listeners do Carrossel
            prevBtn.addEventListener('click', () => {
                prevSlide();
                startAutoSlide();
            });

            nextBtn.addEventListener('click', () => {
                nextSlide();
                startAutoSlide();
            });

            indicators.forEach(indicator => {
                indicator.addEventListener('click', (e) => {
                    const index = parseInt(e.target.getAttribute('data-index'));
                    showSlide(index);
                    startAutoSlide();
                });
            });

            // Iniciar carrossel
            startAutoSlide();


            // --- FAQ ACCORDION ---
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const item = question.parentElement;
                    const isActive = item.classList.contains('active');
                    
                    // Fechar todos
                    document.querySelectorAll('.faq-item').forEach(i => {
                        i.classList.remove('active');
                    });
                    
                    // Abrir se não estava ativo
                    if (!isActive) {
                        item.classList.add('active');
                    }
                });
            });


            // --- MODAL DE REDIRECIONAMENTO ---
            const participateBtn = document.getElementById('participateBtn');
            const redirectOverlay = document.getElementById('redirectOverlay');
            const progressBar = document.getElementById('progressBar');
            const counterSpan = document.getElementById('counter');
            const targetUrl = "https://www.rifeme.com.br/rifa/acao-entre-amigos-do-descomplicando-receitas";

            participateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Ativar modal
                redirectOverlay.classList.add('active');
                
                // Animação de progresso e contagem regressiva
                setTimeout(() => {
                    progressBar.style.width = '100%';
                }, 100);

                let timeLeft = 3;
                counterSpan.textContent = timeLeft;

                const countdownInterval = setInterval(() => {
                    timeLeft--;
                    counterSpan.textContent = timeLeft;
                    
                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        window.location.href = targetUrl;
                    }
                }, 1000);

                // Fechar modal ao clicar fora (opcional se der erro ou quiser voltar)
                redirectOverlay.addEventListener('click', function(evt) {
                    if (evt.target === redirectOverlay) {
                        clearInterval(countdownInterval);
                        redirectOverlay.classList.remove('active');
                        progressBar.style.width = '0%';
                    }
                });
            });
        });
    </script>

    <?php wp_footer(); ?>
</body>
</html>
