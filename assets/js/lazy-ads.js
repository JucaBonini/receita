/**
 * Lazy Ads Loader v1.0
 * Carrega anúncios do Google AdSense apenas quando visíveis.
 */
document.addEventListener('DOMContentLoaded', () => {
    const lazyAds = document.querySelectorAll('.lazy-ad-loader');
    
    if ('IntersectionObserver' in window) {
        let adObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadAd(entry.target);
                    adObserver.unobserve(entry.target);
                }
            });
        });

        lazyAds.forEach(ad => {
            adObserver.observe(ad);
        });
    } else {
        // Fallback para navegadores muito antigos
        lazyAds.forEach(ad => loadAd(ad));
    }
});

function loadAd(element) {
    const encodedCode = element.dataset.code;
    if (!encodedCode) return;

    try {
        // Decodifica o código (Base64 para evitar quebras de aspas no HTML)
        const adCode = atob(encodedCode);
        
        // Injeta o código no container
        element.innerHTML = adCode;
        
        // Se houver tags <script> no código injetado, o innerHTML não as executa.
        // Precisamos extrair e executar manualmente ou garantir que o AdSense rode.
        const scripts = element.querySelectorAll('script');
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        // Força a inicialização do AdSense se necessário
        (window.adsbygoogle = window.adsbygoogle || []).push({});
        
        console.log('[LazyAds] Anúncio carregado no slot:', element.parentElement.dataset.adSlot);
    } catch (e) {
        console.error('[LazyAds] Erro ao carregar anúncio:', e);
    }
}
