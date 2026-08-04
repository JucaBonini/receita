<?php
/**
 * Template Name: Página Ponte Full Standalone (Metric Ref)
 * Description: Página 100% independente, sem dependências do tema, modelada na Metric Acesso.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">
    <title>Quase lá! Carregando sua receita...</title>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    
    fbq('init', '1851802192153599'); 
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1851802192153599&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #120d0b;
            --card-bg: #1d1614;
            --accent-pink: #d63384;
            --accent-gold: #c99c66;
            --white: #ffffff;
            --gray: #b3adaa;
            --whatsapp-green: #25d366;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        body {
            background-color: var(--bg-color);
            color: var(--white);
            font-family: 'Outfit', sans-serif;
            text-align: center;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-countdown-banner {
            width: 100%;
            background: linear-gradient(90deg, #b00b69, #700732);
            color: white;
            padding: 10px 0;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .main-content {
            flex: 1;
            padding: 40px 20px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        .avatar-container { margin-bottom: 25px; }

        .avatar-img {
            width: 100px; height: 100px; border-radius: 50%; border: 3px solid white;
            box-shadow: 0 0 20px rgba(0,0,0,0.5); object-fit: cover;
        }

        .headline { font-size: 24px; font-weight: 800; line-height: 1.2; margin-bottom: 8px; }

        .sub-headline { color: var(--accent-gold); font-size: 15px; font-weight: 600; margin-bottom: 25px; }

        /* Estilo do Botão Principal */
        .btn-redirect {
            display: none; /* Exibido via JS após carregar o pixel */
            width: 100%;
            background-color: var(--whatsapp-green);
            color: white !important;
            text-decoration: none;
            padding: 18px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
            margin-bottom: 30px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .status-area { margin-bottom: 30px; }

        .spinner {
            width: 35px; height: 35px; border: 3px solid rgba(255,255,255,0.1);
            border-top: 3px solid var(--accent-gold); border-radius: 50%;
            margin: 0 auto 15px; animation: spin 0.8s linear infinite;
        }

        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        .status-text { font-size: 13px; color: var(--gray); margin-bottom: 5px; }

        .redirect-timer { font-size: 12px; font-weight: 700; color: var(--white); }

        .scarcity-box {
            background: rgba(201, 156, 102, 0.1);
            border: 1px solid var(--accent-gold);
            border-radius: 12px;
            padding: 12px 25px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 18px; font-weight: 800; color: var(--accent-gold);
        }

        .benefits-title {
            text-align: left; font-size: 14px; font-weight: 800; color: var(--accent-gold);
            text-transform: uppercase; margin-bottom: 15px; letter-spacing: 1px;
        }

        .benefits-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 40px; }

        .benefit-card {
            background: var(--card-bg); border-radius: 12px; padding: 15px;
            display: flex; align-items: center; gap: 15px; text-align: left;
        }

        .benefit-icon {
            width: 45px; height: 45px; flex-shrink: 0; background: rgba(255,255,255,0.03);
            border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;
        }

        .benefit-info h4 { font-size: 15px; font-weight: 700; margin-bottom: 2px; }

        .benefit-info p { font-size: 12px; color: var(--gray); line-height: 1.3; }

        .footer-verified {
            padding: 30px 20px; font-size: 12px; color: var(--gray);
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: auto;
        }

        #social-proof {
            position: fixed; bottom: 20px; left: 20px; background: white; color: black;
            padding: 10px 15px; border-radius: 8px; display: flex; align-items: center;
            gap: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); transform: translateX(-200%);
            transition: 0.5s; z-index: 9999;
        }
        #social-proof.active { transform: translateX(0); }
    </style>
</head>
<body>

    <div class="top-countdown-banner">
        🔥 CANAL VIP LIBERADO: Novas receitas todo dia!
    </div>

    <div class="main-content">
        
        <div class="avatar-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logotipo-descomplicando_receitas300x300.png" alt="Descomplicando Receitas" class="avatar-img">
        </div>

        <h1 class="headline">Descubra as Melhores Receitas e Economize de Verdade</h1>
        <p class="sub-headline">👇 Canal VIP Gratuito!</p>

        <!-- Botão Principal (Aparece após o timer ou imediatamente se quiser) -->
        <a href="#" id="wa-button" class="btn-redirect">QUERO ENTRAR NO CANAL AGORA</a>

        <div class="status-area">
            <div class="spinner"></div>
            <p class="status-text">Aguarde... estamos direcionando você para o canal.</p>
            <p class="redirect-timer">Redirecionando em <span id="timer">5</span>s</p>
        </div>

        <div class="scarcity-box">
            <span>⌛ Vagas restantes: <span id="vCount">6</span></span>
        </div>

        <h3 class="benefits-title">No canal você vai encontrar:</h3>
        
        <div class="benefits-list">
            <div class="benefit-card">
                <div class="benefit-icon">🥘</div>
                <div class="benefit-info">
                    <h4>Receitas de Chef Todo Dia</h4>
                    <p>Acesso aos melhores segredos culinários.</p>
                </div>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">💸</div>
                <div class="benefit-info">
                    <h4>Economia no Supermercado</h4>
                    <p>Dicas para cozinhar bem gastando pouco.</p>
                </div>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🎁</div>
                <div class="benefit-info">
                    <h4>Cupons e E-books</h4>
                    <p>Materiais exclusivos que enviamos só no canal.</p>
                </div>
            </div>
        </div>

    </div>

    <div id="social-proof">
        <div style="width:30px;height:30px;background:#ddd;border-radius:50%"></div>
        <div style="font-size:12px; color: #000;">
            <b id="proof-name">Larissa</b> acabou de entrar no canal 🎉
        </div>
    </div>

    <footer class="footer-verified">
        🛡️ Canal seguro e verificado
    </footer>

    <script>
        const waLink = "https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H"; // <-- ADICIONE SEU LINK AQUI
        const timerEl = document.getElementById('timer');
        const vCountEl = document.getElementById('vCount');
        const waButton = document.getElementById('wa-button');
        const proofEl = document.getElementById('social-proof');
        const proofName = document.getElementById('proof-name');

        function fireLead() {
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Lead');
            }
        }

        let timeLeft = 5;
        const interval = setInterval(() => {
            timeLeft--;
            if (timerEl) timerEl.innerText = timeLeft;
            if(timeLeft <= 0) {
                clearInterval(interval);
                if(waButton) waButton.style.display = 'block';
            }
        }, 1000);

        setTimeout(() => { if(vCountEl) vCountEl.innerText = "3"; }, 3500);

        if(waButton) {
            waButton.addEventListener('click', (e) => {
                e.preventDefault();
                fireLead();
                setTimeout(() => { window.location.href = waLink; }, 400);
            });
        }

        const names = ["Aline", "Marcos", "Fabiana", "Renato", "Cláudia", "Jefferson"];
        function showProof() {
            if(proofName && proofEl) {
                proofName.innerText = names[Math.floor(Math.random() * names.length)];
                proofEl.classList.add('active');
                setTimeout(() => { proofEl.classList.remove('active'); }, 3500);
            }
        }
        setTimeout(showProof, 4000);
        setInterval(showProof, 12000);
    </script>

</body>
</html>
