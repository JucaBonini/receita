<?php
/**
 * Footer específico para a página de cardápio
 */
?>
    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <span class="nav-label">Cardápio</span>
        </a>
        <a href="#" class="nav-item" id="shoppingListNav">
            <i class="fas fa-shopping-cart nav-icon"></i>
            <span class="nav-label">Compras</span>
        </a>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="#" class="nav-item" id="printNavBtn">
            <i class="fas fa-print nav-icon"></i>
            <span class="nav-label">Imprimir</span>
        </a>
    </nav>

    <!-- Shopping List Modal -->
    <div class="modal-overlay" id="shoppingListModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Lista de Compras da Semana</h3>
                <button class="modal-close" id="closeShoppingModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="shoppingListContent">
                    <!-- Conteúdo gerado via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Recipe Detail Modal -->
    <div class="modal-overlay" id="recipeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="recipeModalTitle">Nome da Receita</h3>
                <button class="modal-close" id="closeRecipeModal">&times;</button>
            </div>
            <div class="modal-body" id="recipeModalContent">
                <!-- Conteúdo gerado via JavaScript -->
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>
    
    <script>
(function($) {
    'use strict';
    
    // ============================================================
    // VARIÁVEIS GLOBAIS
    // ============================================================
    
    let currentWeek = cardapioData.currentWeek;
    let currentYear = cardapioData.currentYear;
    let mealData = {};
    
    // ============================================================
    // INICIALIZAÇÃO
    // ============================================================
    
    $(document).ready(function() {
        init();
    });
    
    function init() {
        // Carregar cardápio
        loadCardapio();
        
        // Event listeners
        $('#prevWeek').on('click', previousWeek);
        $('#nextWeek').on('click', nextWeek);
        $('#shoppingListBtn').on('click', openShoppingList);
        $('#closeShoppingModal').on('click', closeShoppingModal);
        $('#closeRecipeModal').on('click', closeRecipeModal);
        $('#printBtn').on('click', printCardapio);
        $('#shareBtn').on('click', shareCardapio);
        $('#viewFullListBtn').on('click', openShoppingList);
        
        // Fechar modal ao clicar fora
        $(window).on('click', function(event) {
            if (event.target.id === 'shoppingListModal') {
                closeShoppingModal();
            }
            if (event.target.id === 'recipeModal') {
                closeRecipeModal();
            }
        });
    }
    
    // ============================================================
    // CARREGAR CARDÁPIO
    // ============================================================
    
    function loadCardapio() {
        showLoading();
        
        $.ajax({
            url: cardapioData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_cardapio_semana',
                nonce: cardapioData.nonce
            },
            success: function(response) {
                if (response.success) {
                    mealData = response.data;
                    renderWeekInfo();
                    renderMealPlan();
                    renderShoppingPreview();
                    updateStats();
                } else {
                    showEmptyState();
                }
            },
            error: function() {
                showEmptyState();
            }
        });
    }
    
    // ============================================================
    // RENDERIZAR INFORMAÇÕES DA SEMANA
    // ============================================================
    
    function renderWeekInfo() {
        const semana = mealData.semana || currentWeek;
        const dataInicio = mealData.data_inicio ? formatDate(mealData.data_inicio) : '';
        const dataFim = mealData.data_fim ? formatDate(mealData.data_fim) : '';
        
        $('#weekTitle').text('Semana ' + semana);
        $('#weekDates').text(dataInicio + ' - ' + dataFim);
    }
    
    // ============================================================
    // RENDERIZAR CARDÁPIO
    // ============================================================
    
    function renderMealPlan() {
        const $mealPlan = $('#mealPlan');
        $mealPlan.empty();
        
        const refeicoes = mealData.refeicoes || getExampleMeals();
        const dias = ['segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo'];
        const nomesDias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
        
        dias.forEach((dia, index) => {
            const dayCard = $('<div class="day-card"></div>');
            const isToday = isCurrentDay(index);
            
            if (isToday) {
                dayCard.addClass('active');
            }
            
            const meals = refeicoes[dia] || [];
            let mealsHTML = '';
            
            meals.forEach(meal => {
                const tagsHTML = (meal.tags || []).map(tag => {
                    const tagClass = getTagClass(tag);
                    return '<span class="meal-tag ' + tagClass + '">' + tag + '</span>';
                }).join('');
                
                const imageUrl = meal.imagem_url || 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="80" height="80"%3E%3Crect fill="%23f0f0f0" width="80" height="80"/%3E%3C/svg%3E';
                
                mealsHTML += `
                    <div class="meal-section">
                        <div class="meal-header">
                            <div class="meal-title">
                                <i class="fas ${getMealIcon(meal.tipo)} meal-icon"></i>
                                ${meal.tipo}
                            </div>
                            <span class="meal-time">${meal.tempo_preparo || ''}</span>
                        </div>
                        <div class="meal-content">
                            <div class="meal-image">
                                <img src="${imageUrl}" alt="${meal.nome}" loading="lazy">
                            </div>
                            <div class="meal-details">
                                <div class="meal-name">${meal.nome}</div>
                                <div class="meal-description">${meal.descricao || ''}</div>
                                <div class="meal-tags">${tagsHTML}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            const dayDate = getDateForDay(index);
            const statusClass = isToday ? 'completed' : 'pending';
            
            dayCard.html(`
                <div class="day-header">
                    <div class="day-info">
                        <div class="day-number">${index + 1}</div>
                        <div>
                            <div class="day-name">${nomesDias[index]}</div>
                            <div class="day-date">${dayDate}</div>
                        </div>
                    </div>
                    <div class="day-status">
                        <span class="status-dot ${statusClass}"></span>
                    </div>
                </div>
                ${mealsHTML}
            `);
            
            $mealPlan.append(dayCard);
        });
    }
    
    // ============================================================
    // RENDERIZAR PREVIEW DA LISTA DE COMPRAS
    // ============================================================
    
    function renderShoppingPreview() {
        const lista = mealData.lista_compras || [];
        const preview = lista.slice(0, 6);
        
        const $preview = $('#shoppingPreview');
        $preview.empty();
        
        preview.forEach(item => {
            $preview.append('<span class="preview-item">' + item + '</span>');
        });
    }
    
    // ============================================================
    // ATUALIZAR ESTATÍSTICAS
    // ============================================================
    
    function updateStats() {
        const stats = mealData.stats || {};
        
        $('#totalRecipes').text(stats.total_receitas || '0');
        $('#avgPrepTime').text(stats.tempo_medio || '0');
        $('#moneySaved').text('R$ ' + (stats.economia || '0'));
        $('#varietyScore').text((stats.variedade || '0') + '/10');
    }
    
    // ============================================================
    // NAVEGAÇÃO DE SEMANAS
    // ============================================================
    
    function previousWeek() {
        // Impedir navegação para semanas passadas
        const hoje = new Date();
        const semanaAtual = getWeekNumber(hoje);
        
        if (currentWeek <= semanaAtual) {
            showNotification('Não é possível visualizar semanas anteriores', 'warning');
            return;
        }
        
        currentWeek--;
        loadCardapio();
    }
    
    function nextWeek() {
        currentWeek++;
        loadCardapio();
    }
    
    // ============================================================
    // LISTA DE COMPRAS
    // ============================================================
    
    function openShoppingList() {
        const lista = mealData.lista_compras || [];
        const $content = $('#shoppingListContent');
        
        let html = '<div class="shopping-list-items">';
        lista.forEach((item, index) => {
            html += `
                <div class="shopping-item">
                    <input type="checkbox" id="item-${index}" class="shopping-checkbox">
                    <label for="item-${index}">${item}</label>
                </div>
            `;
        });
        html += '</div>';
        html += `
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="btn" onclick="printShoppingList()">Imprimir</button>
                <button class="btn btn-secondary" onclick="shareShoppingList()">Compartilhar</button>
            </div>
        `;
        
        $content.html(html);
        $('#shoppingListModal').addClass('active');
    }
    
    function closeShoppingModal() {
        $('#shoppingListModal').removeClass('active');
    }
    
    function closeRecipeModal() {
        $('#recipeModal').removeClass('active');
    }
    
    // ============================================================
    // IMPRIMIR E COMPARTILHAR
    // ============================================================
    
    function printCardapio() {
        window.print();
    }
    
    function shareCardapio() {
        if (navigator.share) {
            navigator.share({
                title: 'Cardápio da Semana',
                text: 'Confira o cardápio da semana ' + mealData.semana,
                url: window.location.href
            }).catch(err => console.error('Erro ao compartilhar:', err));
        } else {
            showNotification('Compartilhamento não disponível neste navegador', 'info');
        }
    }
    
    window.printShoppingList = function() {
        const lista = mealData.lista_compras || [];
        const printWindow = window.open('', '', 'width=600,height=400');
        printWindow.document.write(`
            <html>
            <head>
                <title>Lista de Compras - Semana ${mealData.semana}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #e74c3c; }
                    ul { list-style: none; padding: 0; }
                    li { padding: 8px 0; border-bottom: 1px solid #eee; }
                </style>
            </head>
            <body>
                <h1>Lista de Compras - Semana ${mealData.semana}</h1>
                <ul>
                    ${lista.map(item => `<li>☐ ${item}</li>`).join('')}
                </ul>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    };
    
    window.shareShoppingList = function() {
        const lista = mealData.lista_compras || [];
        const text = `Lista de Compras - Semana ${mealData.semana}\n\n${lista.join('\n')}`;
        
        if (navigator.share) {
            navigator.share({
                title: 'Lista de Compras',
                text: text
            });
        } else {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Lista copiada para a área de transferência!', 'success');
            });
        }
    };
    
    // ============================================================
    // UTILITÁRIOS
    // ============================================================
    
    function formatDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long' });
    }
    
    function getDateForDay(index) {
        const hoje = new Date();
        const dia = hoje.getDate() + index;
        const date = new Date(hoje.getFullYear(), hoje.getMonth(), dia);
        return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
    }
    
    function isCurrentDay(index) {
        return index === new Date().getDay() - 1 || (index === 6 && new Date().getDay() === 0);
    }
    
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }
    
    function getMealIcon(mealType) {
        const icons = {
            'Café da Manhã': 'fa-coffee',
            'Almoço': 'fa-utensils',
            'Lanche': 'fa-cookie',
            'Jantar': 'fa-moon'
        };
        return icons[mealType] || 'fa-utensils';
    }
    
    function getTagClass(tag) {
        const classes = {
            'rápido': 'quick',
            'econômico': 'economical',
            'saudável': 'healthy'
        };
        return classes[tag] || '';
    }
    
    function showLoading() {
        $('#mealPlan').html(`
            <div class="day-card loading">
                <div class="loading-shimmer" style="height: 200px;"></div>
            </div>
        `);
    }
    
    function showEmptyState() {
        $('#mealPlan').html(`
            <div class="empty-state">
                <div class="empty-icon">📅</div>
                <h3 class="empty-title">Nenhum cardápio disponível</h3>
                <p class="empty-text">Desculpe, não há cardápio disponível para esta semana. Tente novamente mais tarde.</p>
            </div>
        `);
    }
    
    function showNotification(message, type) {
        const notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
        notification.css({
            'position': 'fixed',
            'top': '20px',
            'right': '20px',
            'padding': '16px 20px',
            'background': type === 'success' ? '#2ecc71' : type === 'warning' ? '#f39c12' : '#3498db',
            'color': 'white',
            'border-radius': '8px',
            'box-shadow': '0 4px 6px rgba(0,0,0,0.1)',
            'z-index': '3000',
            'animation': 'slideIn 0.3s ease'
        });
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.css('animation', 'slideOut 0.3s ease');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    function getExampleMeals() {
        return {
            'segunda': [
                {
                    tipo: 'Café da Manhã',
                    nome: 'Panqueca de Aveia',
                    descricao: 'Com frutas e mel',
                    tempo_preparo: '15 min',
                    tags: ['rápido', 'saudável']
                }
            ]
        };
    }
    
})(jQuery);

// Animações CSS para notificações
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .shopping-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }
    
    .shopping-checkbox {
        margin-right: 12px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .shopping-item label {
        flex: 1;
        cursor: pointer;
    }
    
    .shopping-item input:checked + label {
        text-decoration: line-through;
        color: #95a5a6;
    }
`;
document.head.appendChild(style);
    </script>
</body>
</html>