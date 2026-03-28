<?php
/**
 * Template Name: Links - Links Sociais
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
      <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right');
            bloginfo('name'); ?></title>
    <!-- Otimização Core Web Vitals -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
    
    <style>
        /* Reset e variáveis CSS */
        :root {
            --primary: #e74c3c;
            --primary-dark: #c0392b;
            --secondary: #2ecc71;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --gray: #95a5a6;
            --transition: all 0.3s ease;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .linktree-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }
        
        /* Profile Section */
        .profile-section {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .profile-image {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: var(--shadow);
            margin: 0 auto 15px;
        }
        
        .profile-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .profile-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            max-width: 400px;
            margin: 0 auto;
        }
        
        /* Links Section */
        .links-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .link-item {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 16px 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-decoration: none;
            color: var(--dark);
        }
        
        .link-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .link-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .whatsapp-icon {
            background-color: #25D366;
            color: white;
        }
        
        .website-icon {
            background-color: var(--primary);
            color: white;
        }
        
        .ebook-icon {
            background-color: var(--secondary);
            color: white;
        }
        
        .link-content {
            flex: 1;
        }
        
        .link-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        
        .link-description {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .link-arrow {
            color: var(--gray);
            transition: var(--transition);
        }
        
        .link-item:hover .link-arrow {
            transform: translateX(5px);
            color: var(--primary);
        }
        
        /* Social Links */
        .social-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .social-title {
            color: white;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }
        
        .social-link:hover {
            background-color: white;
            color: var(--primary);
            transform: translateY(-3px);
        }
        
        /* footer-links */
        .footer-links {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-top: 20px;
        }
        
        /* Ebook Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background-color: white;
            border-radius: var(--radius);
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform: translateY(20px);
            transition: var(--transition);
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .modal-description {
            color: var(--gray);
        }
        
        .modal-image {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 auto 20px;
            display: block;
            box-shadow: var(--shadow);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            width: 100%;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: #27ae60;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .profile-title {
                font-size: 1.5rem;
            }
            
            .profile-description {
                font-size: 1rem;
            }
            
            .link-item {
                padding: 14px 18px;
            }
        }
    </style>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="linktree-container">
        <!-- Profile Section -->
        <section class="profile-section">
            <img src="<?php bloginfo('template_url'); ?>/assets/images/logotipo-descomplicando_receitas300x300.png" alt="Descomplicando receitas" class="profile-image">
            <h1 class="profile-title">Descomplicando receitas</h1>
            <p class="profile-description">Encontre inspiração culinária, receitas testadas e saboreie momentos deliciosos todos os dias.</p>
        </section>
        
        <!-- Links Section -->
        <section class="links-section">
            <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H" class="link-item" target="_blank">
                <div class="link-icon whatsapp-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Canal no WhatsApp</div>
                    <div class="link-description">Receba receitas exclusivas diariamente</div>
                </div>
                <div class="link-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            
            <a href="https://descomplicandoreceitas.com.br" class="link-item" target="_blank">
                <div class="link-icon website-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Nosso Site</div>
                    <div class="link-description">Acesse todas as receitas e artigos</div>
                </div>
                <div class="link-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            
            <!--<a href="#" class="link-item" id="ebook-link">
                <div class="link-icon ebook-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Ebook Grátis</div>
                    <div class="link-description">15 Receitas Fáceis para o Dia a Dia</div>
                </div>
                <div class="link-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>-->
        </section>
        
        <!-- Social Links -->
        <section class="social-section">
            <h3 class="social-title">Siga-nos nas redes sociais</h3>
            <div class="social-links">
                <a href="https://www.facebook.com/descomplicandoreceitasofic" class="social-link" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/descomplicandoreceitasofic" class="social-link" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>                
                <a href="https://www.youtube.com/@descomplicandoreceitas" class="social-link" aria-label="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://br.pinterest.com/descomplicandoreceitas/" class="social-link" aria-label="Pinterest">
                    <i class="fab fa-pinterest-p"></i>
                </a>
            </div>
        </section>
        
        <!-- footer-links -->
        <footer-links class="footer-links">
            <p><?php
                $copyright = get_theme_mod('footer_copyright', 
                    '&copy; 2023/' . date('Y') . ' Descomplicando Receitas. Todos os direitos reservados.'
                );
                echo wp_kses_post($copyright);
                ?></p>
        </footer-links>
    </div>   
 

    <!-- JavaScript -->
    <script>
        // Ebook Modal
        const ebookLink = document.getElementById('ebook-link');
        const ebookModal = document.getElementById('ebook-modal');
        const closeModal = document.getElementById('close-modal');
        const ebookForm = document.getElementById('ebook-form');
        
        // Abrir modal do ebook
        ebookLink.addEventListener('click', function(e) {
            e.preventDefault();
            ebookModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Fechar modal do ebook
        closeModal.addEventListener('click', function() {
            ebookModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
        
        // Fechar modal ao clicar fora
        ebookModal.addEventListener('click', function(e) {
            if (e.target === ebookModal) {
                ebookModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Envio do formulário do ebook
        ebookForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            
            // Simulação de envio (em um caso real, você enviaria para um backend)
            alert(`Obrigado, ${name}! Seu ebook foi enviado para ${email}. Verifique sua caixa de entrada.`);
            
            // Limpar formulário
            ebookForm.reset();
            
            // Fechar modal
            ebookModal.classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Redirecionar para página de agradecimento (opcional)
            // window.location.href = "obrigado.html";
        });
        
        // Animações suaves
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.link-item');
            
            links.forEach((link, index) => {
                link.style.opacity = '0';
                link.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    link.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    link.style.opacity = '1';
                    link.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
</body>
</html>