<!-- Banner WhatsApp Acessível -->
<div class="whatsapp-banner-accessible" 
     id="whatsapp-banner"
     role="dialog"
     aria-labelledby="banner-title"
     aria-describedby="banner-description"
     aria-modal="false"
     tabindex="-1">
    
    <button class="close-btn" 
            id="closeBanner"
            aria-label="Fechar banner do WhatsApp">
        ×
    </button>
    
    <div class="whatsapp-icon" aria-hidden="true">
        <i class="fab fa-whatsapp" role="img" aria-label="Ícone do WhatsApp"></i>
    </div>
    
    <div class="banner-content">
        <h2 id="banner-title" class="banner-title">
            🚀 ENTRE NO NOSSO CANAL DO WHATSAPP!
        </h2>
        
        <p id="banner-description" class="banner-subtitle">
            Receba <strong>ofertas exclusivas 48h antes</strong>, conteúdos VIP e seja notificado 
            das novidades. <em>Já são mais de 5.000 membros!</em>
        </p>
        
        <a href="https://wa.me/SEUNUMEROAQUI?text=Quero%20entrar%20no%20canal!" 
           class="cta-button"
           id="joinButton"
           role="button"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Entrar no canal do WhatsApp, abre em nova janela">
            <i class="fab fa-whatsapp" aria-hidden="true"></i>
            <span>QUERO ENTRAR AGORA</span>
        </a>
    </div>
</div>

<!-- Inclua os scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="whatsapp-banner.css">
<script src="whatsapp-banner.js" defer></script>