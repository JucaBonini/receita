<?php
/**
 * Template Name: Achadinhos da Cozinha
 */

get_header();
?>

<style>
/* ESTILOS ESPECÍFICOS PARA A PÁGINA DE ACHADINHOS - OTIMIZADO */
.kitchen-finds {
    padding: 60px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 2.5rem;
    color: var(--dark);
}

.section-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: var(--gray);
    max-width: 600px;
    margin: 0 auto 40px;
}

/* Filtros */
.finds-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 30px;
}

.filter-btn {
    padding: 10px 20px;
    border: 2px solid var(--primary);
    background: white;
    color: var(--primary);
    border-radius: 25px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary);
    color: white;
}

/* Grid de Achadinhos */
.finds-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.find-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
}

.find-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.find-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--primary);
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
}

.find-image {
    height: 200px;
    overflow: hidden;
}

.find-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.find-card:hover .find-image img {
    transform: scale(1.05);
}

.find-content {
    padding: 20px;
}

.find-category {
    display: inline-block;
    background: var(--light);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.find-title {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: var(--dark);
    line-height: 1.3;
}

.find-description {
    color: var(--gray);
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.4;
}

.find-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.rating-count {
    color: var(--gray);
    font-size: 0.8rem;
}

.find-benefits {
    margin-bottom: 20px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: var(--dark);
}

.benefit-item i {
    color: var(--secondary);
    font-size: 1rem;
}

.find-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.wishlist-btn {
    width: 44px;
    height: 44px;
    border: 2px solid var(--light);
    background: white;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.wishlist-btn:hover {
    border-color: var(--primary);
    background: var(--light);
}

.wishlist-btn.active {
    background: #ffe6e6;
    border-color: var(--primary);
    color: #e74c3c;
}

/* CTA Final */
.finds-cta {
    text-align: center;
    background: white;
    padding: 30px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.finds-cta p {
    margin-bottom: 20px;
    font-size: 1.1rem;
    color: var(--dark);
}

.newsletter-form {
    display: flex;
    max-width: 500px;
    margin: 0 auto;
    gap: 10px;
}

.newsletter-form input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid var(--light);
    border-radius: var(--radius);
    font-size: 1rem;
}

.newsletter-form input:focus {
    outline: none;
    border-color: var(--primary);
}

/* Responsividade */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .finds-filter {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .newsletter-form {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    .finds-grid {
        grid-template-columns: 1fr;
    }
    
    .find-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .wishlist-btn {
        align-self: center;
    }
}
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo home_url(); ?>">Home</a>
            <span class="separator">/</span>
            <span class="current">Achadinhos da Cozinha</span>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="kitchen-finds">
    <div class="container">
        <h1 class="section-title">Achadinhos da Cozinha</h1>
        <p class="section-subtitle">Descubra os melhores utensílios, eletrodomésticos e produtos que vão facilitar sua vida na cozinha</p>
        
        <!-- Filtros -->
        <div class="finds-filter">
            <button class="filter-btn active" data-filter="all">Todos</button>
            <button class="filter-btn" data-filter="utensilios">Utensílios</button>
            <button class="filter-btn" data-filter="eletrodomesticos">Eletrodomésticos</button>
            <button class="filter-btn" data-filter="organizacao">Organização</button>
            <button class="filter-btn" data-filter="eco">Eco-friendly</button>
            <button class="filter-btn" data-filter="destaque">Destaques</button>
        </div>

        <!-- Grid de Achadinhos -->
        <div class="finds-grid">
            <?php
            // Exemplo de dados - No WordPress real, isso viria de Custom Post Types ou ACF
            $achadinhos = array(
                array(
                    'badge' => 'Mais Vendido',
                    'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'eletrodomesticos',
                    'title' => 'Processador de Alimentos Multifuncional',
                    'description' => 'Processador com 12 funções diferentes para facilitar seu dia a dia na cozinha.',
                    'rating' => 5,
                    'reviews' => 248,
                    'benefits' => array('12 funções diferentes', 'Fácil de limpar', 'Compacto e prático')
                ),
                array(
                    'badge' => 'Novidade',
                    'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'utensilios',
                    'title' => 'Faqueiro Ecológico de Bambu',
                    'description' => 'Conjunto completo de talheres em bambu sustentável, ideal para o dia a dia.',
                    'rating' => 4,
                    'reviews' => 156,
                    'benefits' => array('Material sustentável', 'Leve e durável', 'Fácil armazenamento')
                ),
                array(
                    'badge' => 'Destaque',
                    'image' => 'https://images.unsplash.com/photo-1594736797933-d0ea3ff8db41?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'organizacao',
                    'title' => 'Organizador de Panelas e Tampa',
                    'description' => 'Sistema prático para organizar suas panelas e economizar espaço.',
                    'rating' => 5,
                    'reviews' => 312,
                    'benefits' => array('Economiza espaço', 'Acesso fácil', 'Organização prática')
                ),
                array(
                    'badge' => '',
                    'image' => 'https://images.unsplash.com/photo-1570222094114-d054a817e56b?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'eletrodomesticos',
                    'title' => 'Air Fryer 5L Digital',
                    'description' => 'Frite, asse e grelhe com pouquíssimo óleo. Prática e fácil de limpar.',
                    'rating' => 4,
                    'reviews' => 427,
                    'benefits' => array('Cozinha saudável', 'Fácil de usar', 'Limpeza simples')
                ),
                array(
                    'badge' => 'Eco',
                    'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'eco',
                    'title' => 'Kit Marmitas Reutilizáveis',
                    'description' => 'Conjunto de 3 marmitas em vidro com divisórias, perfeitas para meal prep.',
                    'rating' => 5,
                    'reviews' => 189,
                    'benefits' => array('Material reutilizável', 'Prático para congelar', 'Fácil de transportar')
                ),
                array(
                    'badge' => '',
                    'image' => 'https://images.unsplash.com/photo-1556909114-1596c82d0f58?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80',
                    'category' => 'utensilios',
                    'title' => 'Jogo de Panelas Antiaderente',
                    'description' => 'Conjunto completo de 5 peças com revestimento cerâmico antiaderente.',
                    'rating' => 4,
                    'reviews' => 534,
                    'benefits' => array('Antiaderente premium', 'Distribuição uniforme de calor', 'Fácil limpeza')
                )
            );

            foreach ($achadinhos as $achadinho) :
                $stars = str_repeat('★', $achadinho['rating']) . str_repeat('☆', 5 - $achadinho['rating']);
            ?>
            <div class="find-card" data-category="<?php echo $achadinho['category']; ?>">
                <?php if ($achadinho['badge']) : ?>
                    <div class="find-badge"><?php echo $achadinho['badge']; ?></div>
                <?php endif; ?>
                
                <div class="find-image">
                    <img src="<?php echo $achadinho['image']; ?>" alt="<?php echo esc_attr($achadinho['title']); ?>" loading="lazy">
                </div>
                
                <div class="find-content">
                    <span class="find-category">
                        <?php 
                        $categories = array(
                            'utensilios' => 'Utensílios',
                            'eletrodomesticos' => 'Eletrodomésticos',
                            'organizacao' => 'Organização',
                            'eco' => 'Eco-friendly'
                        );
                        echo $categories[$achadinho['category']] ?? 'Geral';
                        ?>
                    </span>
                    
                    <h3 class="find-title"><?php echo $achadinho['title']; ?></h3>
                    <p class="find-description"><?php echo $achadinho['description']; ?></p>
                    
                    <div class="find-rating">
                        <div class="stars"><?php echo $stars; ?></div>
                        <span class="rating-count">(<?php echo $achadinho['reviews']; ?> avaliações)</span>
                    </div>
                    
                    <div class="find-benefits">
                        <?php foreach ($achadinho['benefits'] as $benefit) : ?>
                            <div class="benefit-item">
                                <i>✓</i>
                                <span><?php echo $benefit; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="find-actions">
                        <button class="btn">Ver Detalhes</button>
                        <button class="wishlist-btn">♥</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA -->
        <div class="finds-cta">
            <h3>Receba Novos Achadinhos no Seu Email</h3>
            <p>Cadastre-se e seja o primeiro a saber sobre novidades e lançamentos</p>
            <div class="newsletter-form">
                <input type="email" placeholder="Seu melhor email" class="search-page-input">
                <button class="btn">Cadastrar</button>
            </div>
        </div>
    </div>
</section>

<script>
// Filtros
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const findCards = document.querySelectorAll('.find-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class de todos os botões
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Adiciona active class ao botão clicado
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Filtra os cards
            findCards.forEach(card => {
                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Wishlist
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
});
</script>

<?php get_footer(); ?>