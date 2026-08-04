/**
 * Smart Recommendation Engine v1.0
 * Analisa o comportamento do usuário e personaliza as sugestões.
 */
document.addEventListener('DOMContentLoaded', () => {
    const currentCategory = document.body.dataset.category; // Precisamos injetar isso no body
    
    if (currentCategory) {
        let interests = JSON.parse(localStorage.getItem('user_interests')) || {};
        
        // Aumenta o peso da categoria atual
        interests[currentCategory] = (interests[currentCategory] || 0) + 1;
        
        // Limita a análise às top 3 categorias para manter a performance
        const sortedInterests = Object.entries(interests)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3);
            
        localStorage.setItem('user_interests', JSON.stringify(Object.fromEntries(sortedInterests)));
    }

    // Lógica para reordenar ou destacar blocos de recomendação
    applySmartRecommendations();
});

function applySmartRecommendations() {
    const interests = JSON.parse(localStorage.getItem('user_interests')) || {};
    const topInterest = Object.keys(interests)[0];

    if (topInterest) {
        console.log('[Smart Engine] Categoria de interesse detectada:', topInterest);
        
        // Procura por blocos de recomendações que correspondam à categoria de interesse
        const recommendationItems = document.querySelectorAll('.smart-recommendation-item');
        recommendationItems.forEach(item => {
            if (item.dataset.category === topInterest) {
                item.classList.add('is-recommended');
                // Move para o topo do grid se for um container de flex/grid
                item.style.order = "-1";
            }
        });
    }
}
