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
        let adCode;
        try {
            adCode = atob(encodedCode);
        } catch (e) {
            console.error('[LazyAds] Erro ao decodificar Base64:', e);
            return;
        }
        
        // Injeta o código no container
        element.innerHTML = adCode;
        
        // Se houver tags <script> no código injetado, o innerHTML não as executa.
        // Precisamos extrair e executar manualmente.
        const scripts = element.querySelectorAll('script');
        let hasPushCall = false;

        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => {
                newScript.setAttribute(attr.name, attr.value);
            });
            
            if (oldScript.innerHTML) {
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                if (oldScript.innerHTML.includes('adsbygoogle.push')) {
                    hasPushCall = true;
                }
            }
            
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        // Força a inicialização do AdSense APENAS se não houver um push() manual no código injetado
        if (!hasPushCall) {
            (window.adsbygoogle = window.adsbygoogle || []).push({});
            console.log('[LazyAds] push({}) manual executado para o slot:', element.parentElement.dataset.adSlot);
        } else {
            console.log('[LazyAds] Código injetado já contém push(), ignorando push() manual.');
        }
        
        console.log('[LazyAds] Anúncio processado no slot:', element.parentElement.dataset.adSlot);
    } catch (e) {
        console.error('[LazyAds] Erro ao carregar anúncio:', e);
    }
}
