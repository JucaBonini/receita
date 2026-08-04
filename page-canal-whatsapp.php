<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entre no Nosso Canal Exclusivo de Receitas no WhatsApp!</title>
    <meta name="description" content="Receitas exclusivas, prêmios surpresa e dicas culinárias diárias no WhatsApp!">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset e Variáveis */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #25D366;
            --primary-dark: #128C7E;
            --secondary: #FF6B6B;
            --accent: #FFD166;
            --dark: #2C3E50;
            --light: #F8F9FA;
            --transition: all 0.3s ease;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 16px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Layout Principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            padding: 25px 0;
            text-align: center;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo span {
            color: var(--secondary);
        }

        .logo i {
            font-size: 2.5rem;
        }

        /* Hero Section */
        .hero {
            padding: 80px 0;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 3.2rem;
            margin-bottom: 20px;
            color: var(--dark);
            line-height: 1.2;
        }

        .hero-title span {
            color: var(--primary);
            position: relative;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(37, 211, 102, 0.2);
            z-index: -1;
            border-radius: 4px;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Contador (AGORA 5 SEGUNDOS) */
        .countdown-container {
            background: white;
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 400px;
            margin: 0 auto 40px;
            border: 2px dashed var(--primary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                border-color: var(--primary);
            }

            50% {
                border-color: var(--secondary);
            }

            100% {
                border-color: var(--primary);
            }
        }

        .countdown-label {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 15px;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .countdown-item {
            text-align: center;
            background: var(--primary);
            color: white;
            padding: 15px;
            border-radius: 12px;
            min-width: 70px;
        }

        .countdown-number {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
        }

        .countdown-label-small {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Botão Principal */
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: var(--primary);
            color: white;
            padding: 22px 45px;
            border-radius: var(--radius);
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
            margin: 30px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
        }

        .whatsapp-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-btn i {
            font-size: 1.8rem;
        }

        .btn-secondary {
            background: var(--secondary);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        }

        .btn-secondary:hover {
            background: #ff5252;
            box-shadow: 0 15px 30px rgba(255, 107, 107, 0.4);
        }

        /* Benefícios */
        .benefits {
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: var(--dark);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .benefit-card {
            background: white;
            padding: 35px 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            border-top: 5px solid var(--primary);
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .benefit-icon {
            width: 80px;
            height: 80px;
            background: rgba(37, 211, 102, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.2rem;
            color: var(--primary);
        }

        .benefit-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .benefit-description {
            color: #666;
            font-size: 1.05rem;
        }

        /* Depoimentos */
        .testimonials {
            padding: 60px 0;
            background: rgba(255, 255, 255, 0.7);
            border-radius: var(--radius);
            margin: 40px 0;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 4rem;
            color: rgba(37, 211, 102, 0.1);
            font-family: Georgia, serif;
            line-height: 1;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: #555;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .author-info h4 {
            margin-bottom: 5px;
        }

        .author-info p {
            color: #888;
            font-size: 0.9rem;
        }

        /* FAQ */
        .faq {
            padding: 60px 0;
        }

        .faq-item {
            background: white;
            margin-bottom: 15px;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            padding: 25px 30px;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .faq-question:hover {
            background: #f8f9fa;
        }

        .faq-question i {
            transition: var(--transition);
            color: var(--primary);
        }

        .faq-answer {
            padding: 0 30px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer.active {
            padding: 0 30px 25px;
            max-height: 500px;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
            text-align: center;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-logo {
            font-size: 2rem;
            margin-bottom: 20px;
            color: white;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .copyright {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Modal de Redirecionamento */
        .redirect-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }

        .redirect-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            padding: 50px;
            border-radius: var(--radius);
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: modalAppear 0.9s ease;
        }

        @keyframes modalAppear {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-icon {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .modal-title {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .modal-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .modal-countdown {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 20px 0;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .countdown-item {
                min-width: 60px;
                padding: 12px;
            }

            .countdown-number {
                font-size: 1.8rem;
            }

            .whatsapp-btn {
                padding: 18px 30px;
                font-size: 1.2rem;
            }

            .modal-content {
                padding: 30px;
            }

            .modal-title {
                font-size: 1.7rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .countdown {
                gap: 10px;
            }

            .countdown-item {
                min-width: 50px;
                padding: 10px;
            }

            .countdown-number {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Junte-se ao <span>Canal Exclusivo</span> de Receitas no WhatsApp!
                </h1>
                <p class="hero-subtitle">
                    Receitas secretas, prêmios surpresa e dicas diárias diretamente no seu celular.
                    Tudo gratuito e exclusivo para membros!
                </p>

                <!-- Contador de 5 SEGUNDOS -->
                <div class="countdown-container">
                    <p class="countdown-label">Redirecionamento automático em:</p>
                    <div class="countdown" id="countdown">
                        <div class="countdown-item">
                            <div class="countdown-number" id="seconds">10</div>
                            <div class="countdown-label-small">Segundos</div>
                        </div>
                    </div>
                    <p style="color: #666; font-size: 0.9rem; margin-top: 10px;">
                        <i class="fas fa-exclamation-circle"></i> Você será redirecionado automaticamente!
                    </p>
                </div>

                <!-- Botão Principal -->
                <button class="whatsapp-btn" id="joinBtn">
                    <i class="fab fa-whatsapp"></i>
                    ENTRAR NO CANAL AGORA
                </button>

                <p style="color: #666; margin-top: 15px; font-size: 0.95rem;">
                    <i class="fas fa-lock"></i> 100% gratuito • Sem spam • Pode sair quando quiser
                </p>
            </div>
        </div>
    </section>

    <!-- Benefícios -->
    <section class="benefits">
        <div class="container">
            <h2 class="section-title">O que você vai receber?</h2>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="benefit-title">Receitas Exclusivas</h3>
                    <p class="benefit-description">
                        Acesso antecipado a receitas que não são publicadas em nenhum outro lugar.
                        Pratos especiais criados pelos nossos chefs.
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="benefit-title">Prêmios Surpresa</h3>
                    <p class="benefit-description">
                        Participe de sorteios mensais de utensílios de cozinha, livros de receitas
                        e até cursos de culinária gratuitos!
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="benefit-title">Dicas Diárias</h3>
                    <p class="benefit-description">
                        Truques de cozinha, substituições inteligentes e respostas rápidas para
                        suas dúvidas culinárias todos os dias.
                    </p>
                </div>
            </div>

            <!-- Botão Secundário -->
            <div style="text-align: center;">
                <button class="whatsapp-btn btn-secondary" id="joinBtn2">
                    <i class="fab fa-whatsapp"></i>
                    QUERO RECEBER TUDO ISSO!
                </button>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">O que nossos membros dizem</h2>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "Desde que entrei no canal, minha cozinha mudou completamente! Recebi 3 receitas exclusivas que viraram as favoritas da família."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">M</div>
                        <div class="author-info">
                            <h4>Mariana Silva</h4>
                            <p>Membro há 4 meses</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "Ganhei um jogo de panelas no último sorteio! Nem acreditei quando recebi a mensagem. Vale muito a pena participar!"
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">C</div>
                        <div class="author-info">
                            <h4>Carlos Eduardo</h4>
                            <p>Membro há 6 meses</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "As dicas diárias me salvaram em várias situações. Agora sei substituir ingredientes e fazer pratos incríveis com o que tenho em casa."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div class="author-info">
                            <h4>Ana Paula</h4>
                            <p>Membro há 3 meses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq">
        <div class="container">
            <h2 class="section-title">Perguntas Frequentes</h2>

            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        É realmente gratuito?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim! Todo o conteúdo do canal é 100% gratuito. Não cobramos nenhum valor agora ou no futuro.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Quantas mensagens vou receber por dia?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Enviamos de 1 a 3 mensagens por dia, sempre em horários que não incomodam. Nada de spam!</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Posso sair do canal quando quiser?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim! Basta clicar em "Sair do grupo" no WhatsApp. O processo é instantâneo e sem complicações.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        Como funcionam os sorteios?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Todo mês sorteamos prêmios entre os membros ativos do canal. Basta estar no grupo para participar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="benefits" style="padding-top: 0;">
        <div class="container">
            <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 60px; border-radius: var(--radius); text-align: center; color: white;">
                <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: white;">
                    Não perca mais tempo!
                </h2>
                <p style="font-size: 1.3rem; margin-bottom: 30px; opacity: 0.9;">
                    Junte-se a mais de 5.000 apaixonados por culinária que já transformaram suas cozinhas.
                </p>

                <button class="whatsapp-btn" id="joinBtn3" style="background: white; color: var(--primary); max-width: 350px;">
                    <i class="fab fa-whatsapp" style="color: var(--primary);"></i>
                    ÚLTIMA CHANCE DE ENTRAR
                </button>

                <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.8;">
                    <i class="fas fa-users"></i> Vagas limitadas • Últimos 50 lugares disponíveis
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="fab fa-whatsapp"></i> Descomplicando Receitas
                </div>

                <p class="footer-text">
                    Leve a paixão pela culinária para o próximo nível. Receba conteúdo exclusivo
                    diretamente no seu WhatsApp e transforme sua experiência na cozinha.
                </p>

                <div class="footer-links">
                    <a href="<?php echo esc_url(home_url('/politica-de-privacidade')); ?>">Política de Privacidade</a>
                    <a href="<?php echo esc_url(home_url('/termos-de-uso')); ?>">Termos de Uso</a>
                    <a href="mailto:contato@descomplicandoreceitas.com.br">Contato</a>
                </div>

                <p class="copyright">
                    © 2023/<?php
                            $ano_atual = date('Y');
                            echo $ano_atual; // Exemplo: 2024
                            ?> Descomplicando Receitas. Todos os direitos reservados.<br>
                    Este site não é afiliado ao WhatsApp Inc.
                </p>
            </div>
        </div>
    </footer>

    <!-- Modal de Redirecionamento -->
    <div class="redirect-modal" id="redirectModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fab fa-whatsapp"></i>
            </div>

            <h2 class="modal-title">Redirecionando...</h2>

            <p class="modal-text">
                Você será redirecionado automaticamente para o WhatsApp para entrar no nosso canal exclusivo!
            </p>

            <div class="modal-countdown" id="modalCountdown">5</div>

            <p>Segundos restantes</p>

            <div class="modal-actions">
                <button class="whatsapp-btn" id="goNowBtn" style="max-width: 200px;">
                    <i class="fab fa-whatsapp"></i>
                    IR AGORA
                </button>

                <button class="whatsapp-btn btn-secondary" id="cancelBtn" style="max-width: 200px; background: #95a5a6;">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </button>
            </div>
        </div>
    </div>

    <script>
        // Variáveis globais
        let countdownInterval;
        let redirectInterval;
        let timeLeft = 10; // 10 segundos para redirecionamento automático
        let modalTimeLeft = 10; // 10 segundos para redirecionamento no modal
        const whatsappLink = "https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H"; // Substitua pelo seu link

        // Inicializar contador principal de 5 segundos
        function initCountdown() {
            // Atualizar visualmente
            updateCountdownDisplay();

            countdownInterval = setInterval(() => {
                timeLeft--;
                updateCountdownDisplay();

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    redirectToWhatsApp();
                }
            }, 1000);
        }

        // Atualizar display do contador
        function updateCountdownDisplay() {
            document.getElementById('seconds').textContent = timeLeft.toString().padStart(2, '0');

            // Mudar cor quando estiver no final
            if (timeLeft <= 3) {
                document.querySelector('.countdown-item').style.background = 'var(--secondary)';
                document.querySelector('.countdown-container').style.animation = 'pulse 10s infinite';
            }
        }

        // Iniciar redirecionamento (quando clicar no botão)
        function startRedirect() {
            const modal = document.getElementById('redirectModal');
            modal.classList.add('active');

            // Atualizar contador do modal
            redirectInterval = setInterval(() => {
                modalTimeLeft--;
                document.getElementById('modalCountdown').textContent = modalTimeLeft;

                if (modalTimeLeft <= 0) {
                    clearInterval(redirectInterval);
                    redirectToWhatsApp();
                }
            }, 1000);
        }

        // Redirecionar para WhatsApp
        function redirectToWhatsApp() {
            window.location.href = whatsappLink;
        }

        // Alternar FAQ
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');

            answer.classList.toggle('active');

            if (answer.classList.contains('active')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Iniciar contador automático de 5 segundos
            setTimeout(() => {
                initCountdown();
            }, 2000); // Começar após 2 segundos de carregamento

            // Adicionar eventos aos botões de entrada
            const joinButtons = ['joinBtn', 'joinBtn2', 'joinBtn3'];
            joinButtons.forEach(btnId => {
                document.getElementById(btnId).addEventListener('click', () => {
                    clearInterval(countdownInterval); // Parar contador automático
                    startRedirect(); // Iniciar redirecionamento manual
                });
            });

            // Botão "Ir Agora" no modal
            document.getElementById('goNowBtn').addEventListener('click', () => {
                clearInterval(redirectInterval);
                redirectToWhatsApp();
            });

            // Botão "Cancelar" no modal
            document.getElementById('cancelBtn').addEventListener('click', () => {
                clearInterval(redirectInterval);
                document.getElementById('redirectModal').classList.remove('active');
                modalTimeLeft = 10; // Resetar tempo
                document.getElementById('modalCountdown').textContent = modalTimeLeft;
            });

            // Fechar modal clicando fora
            document.getElementById('redirectModal').addEventListener('click', (e) => {
                if (e.target.id === 'redirectModal') {
                    clearInterval(redirectInterval);
                    document.getElementById('redirectModal').classList.remove('active');
                    modalTimeLeft = 10;
                    document.getElementById('modalCountdown').textContent = modalTimeLeft;
                }
            });
        });

        // Efeito de digitação no título (opcional)
        document.addEventListener('DOMContentLoaded', () => {
            const title = document.querySelector('.hero-title span');
            const originalText = title.textContent;
            title.textContent = '';

            let i = 0;
            const typeWriter = () => {
                if (i < originalText.length) {
                    title.textContent += originalText.charAt(i);
                    i++;
                    setTimeout(typeWriter, 100);
                }
            };

            // Iniciar efeito após 1 segundo
            setTimeout(typeWriter, 1000);
        });
    </script>
</body>

</html>