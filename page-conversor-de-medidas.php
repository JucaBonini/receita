<?php
/**
 * The template for displaying conversor page
 *
 * @package receitas
 */

get_header(); ?>

<main class="page-template">
    <!-- Hero Section da Página -->
    <section class="page-hero">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
                
                <div class="page-meta">
                    <div class="meta-item calendar">
                        <i class="far fa-calendar"></i>
                        <span>Atualizado em <?php echo get_the_modified_date('d/m/Y'); ?></span>
                    </div>
                </div>

                <div class="page-excerpt">
                    <p>Converta facilmente entre diferentes unidades de medida para suas receitas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a>
                <span class="separator">/</span>
                <span class="current"><?php the_title(); ?></span>
            </nav>
        </div>
    </section>

    <div class="container">
        <div class="page-content-wrapper">
            <!-- Conteúdo Principal -->
            <div class="page-main-content">
                <!-- Conteúdo da Página -->
                <article class="page-content">
                    <div class="content-wrapper">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; ?>
                    </div>

                    <!-- Container do Conversor -->
                    <div class="converter-container">
                        <div class="converter-grid">
                            <!-- Conversor de Xícaras para Gramas -->
                            <div class="converter-box" id="cups-to-grams">
                                <h2 class="converter-title">Xícaras para Gramas</h2>
                                <form class="converter-form" id="cupsForm">
                                    <div class="form-group">
                                        <label for="ingredient">Ingrediente:</label>
                                        <select class="form-control" id="ingredient" required>
                                            <option value="">Selecione um ingrediente</option>
                                            <option value="flour">Farinha de trigo</option>
                                            <option value="sugar">Açúcar refinado</option>
                                            <option value="brown-sugar">Açúcar mascavo</option>
                                            <option value="butter">Manteiga</option>
                                            <option value="oil">Óleo</option>
                                            <option value="milk">Leite</option>
                                            <option value="water">Água</option>
                                            <option value="cocoa">Cacau em pó</option>
                                            <option value="rice">Arroz</option>
                                            <option value="oats">Aveia</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="cups">Quantidade (xícaras):</label>
                                        <input type="number" class="form-control" id="cups" step="0.01" min="0" required>
                                    </div>
                                    
                                    <button type="submit" class="btn">Converter</button>
                                </form>
                                
                                <div class="result" id="cupsResult"></div>
                                
                                <div class="converter-info">
                                    <h3>Informações úteis:</h3>
                                    <ul>
                                        <li>1 xícara padrão = 240ml</li>
                                        <li>Medidas podem variar conforme o método de preparo</li>
                                        <li>Para ingredientes sólidos, peneire antes de medir</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Conversor de Fahrenheit para Celsius -->
                            <div class="converter-box" id="fahrenheit-to-celsius">
                                <h2 class="converter-title">Fahrenheit para Celsius</h2>
                                <form class="converter-form" id="tempForm">
                                    <div class="form-group">
                                        <label for="fahrenheit">Temperatura (°F):</label>
                                        <input type="number" class="form-control" id="fahrenheit" required>
                                    </div>
                                    
                                    <button type="submit" class="btn">Converter</button>
                                </form>
                                
                                <div class="result" id="tempResult"></div>
                                
                                <div class="converter-info">
                                    <h3>Pontos de referência:</h3>
                                    <ul>
                                        <li>32°F = 0°C (ponto de congelamento da água)</li>
                                        <li>212°F = 100°C (ponto de ebulição da água)</li>
                                        <li>350°F ≈ 177°C (temperatura média de forno)</li>
                                        <li>400°F ≈ 200°C (forno quente)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <?php include(get_template_directory() . '/template-parts/whatsapp.php'); ?>
                </article>
            </div>

            <!-- Sidebar -->
            <aside class="page-sidebar">
                <!-- Widget de Conversores Rápidos -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">⚡ Conversões Rápidas</h3>
                    <div class="quick-conversions">
                        <div class="quick-conversion-item">
                            <h4>1 xícara de farinha</h4>
                            <p>= 125g</p>
                        </div>
                        <div class="quick-conversion-item">
                            <h4>1 xícara de açúcar</h4>
                            <p>= 200g</p>
                        </div>
                        <div class="quick-conversion-item">
                            <h4>350°F</h4>
                            <p>= 177°C</p>
                        </div>
                        <div class="quick-conversion-item">
                            <h4>1 colher de sopa</h4>
                            <p>= 15ml</p>
                        </div>
                    </div>
                </div>

                <!-- Widget Receitas em Destaque -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">📝 Receitas Populares</h3>
                    <div class="recent-posts">
                        <?php
                        $recent_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'post_status' => 'publish',
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC'
                        );

                        $recent_posts = new WP_Query($recent_args);

                        if ($recent_posts->have_posts()) :
                            while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="recent-post-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="recent-post-image">
                                            <?php the_post_thumbnail('thumbnail', array('loading' => 'lazy')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="recent-post-content">
                                        <h4><?php the_title(); ?></h4>
                                        <span class="recent-post-date"><?php echo get_the_date('d/m/Y'); ?></span>
                                    </div>
                                </a>
                        <?php endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<style>
/* Estilos específicos para o conversor - Integrados ao CSS memorizado */
.converter-container {
    margin: 40px 0;
}

.converter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.converter-box {
    background: white;
    padding: 30px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 2px solid var(--light);
    transition: var(--transition);
}

.converter-box:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
}

.converter-title {
    color: var(--dark);
    margin-bottom: 25px;
    font-size: 1.4rem;
    text-align: center;
    border-bottom: 2px solid var(--light);
    padding-bottom: 15px;
}

.converter-form {
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--light);
    border-radius: var(--radius);
    font-size: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.result {
    background: var(--light);
    padding: 20px;
    border-radius: var(--radius);
    margin: 20px 0;
    text-align: center;
    font-size: 1.1rem;
    display: none;
}

.result.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

.result strong {
    color: var(--primary);
    font-size: 1.2rem;
}

.converter-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: var(--radius);
    border-left: 4px solid var(--primary);
}

.converter-info h3 {
    color: var(--dark);
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.converter-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.converter-info li {
    padding: 5px 0;
    position: relative;
    padding-left: 20px;
}

.converter-info li::before {
    content: '•';
    color: var(--primary);
    position: absolute;
    left: 0;
    font-weight: bold;
}

/* Quick Conversions */
.quick-conversions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-conversion-item {
    background: var(--light);
    padding: 15px;
    border-radius: var(--radius);
    text-align: center;
    transition: var(--transition);
}

.quick-conversion-item:hover {
    background: var(--primary);
    color: white;
}

.quick-conversion-item h4 {
    margin: 0 0 5px 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.quick-conversion-item p {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
}

/* Animações */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsividade */
@media (max-width: 768px) {
    .converter-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .converter-box {
        padding: 20px;
    }
}
</style>

<script>
// Conversor de xícaras para gramas
const cupsForm = document.getElementById('cupsForm');
const cupsResult = document.getElementById('cupsResult');

// Densidades aproximadas em g/xícara
const densities = {
    'flour': 125,       // Farinha de trigo
    'sugar': 200,       // Açúcar refinado
    'brown-sugar': 180, // Açúcar mascavo
    'butter': 227,      // Manteiga
    'oil': 218,         // Óleo
    'milk': 245,        // Leite
    'water': 240,       // Água
    'cocoa': 100,       // Cacau em pó
    'rice': 200,        // Arroz
    'oats': 90          // Aveia
};

// Nomes dos ingredientes
const ingredientNames = {
    'flour': 'Farinha de trigo',
    'sugar': 'Açúcar refinado',
    'brown-sugar': 'Açúcar mascavo',
    'butter': 'Manteiga',
    'oil': 'Óleo',
    'milk': 'Leite',
    'water': 'Água',
    'cocoa': 'Cacau em pó',
    'rice': 'Arroz',
    'oats': 'Aveia'
};

cupsForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const ingredient = document.getElementById('ingredient').value;
    const cups = parseFloat(document.getElementById('cups').value);
    
    if (ingredient && !isNaN(cups)) {
        const grams = cups * densities[ingredient];
        cupsResult.innerHTML = `
            <div style="text-align: center;">
                <div style="font-size: 1.3rem; margin-bottom: 10px;">
                    ${cups} xícara(s) de ${ingredientNames[ingredient]}
                </div>
                <div style="font-size: 1.8rem; color: var(--primary); font-weight: bold;">
                    = ${grams.toFixed(1)} gramas
                </div>
            </div>
        `;
        cupsResult.classList.add('active');
    }
});

// Conversor de Fahrenheit para Celsius
const tempForm = document.getElementById('tempForm');
const tempResult = document.getElementById('tempResult');

tempForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fahrenheit = parseFloat(document.getElementById('fahrenheit').value);
    
    if (!isNaN(fahrenheit)) {
        const celsius = (fahrenheit - 32) * 5/9;
        
        // Adiciona descrição baseada na temperatura
        let description = '';
        let icon = '🌡️';
        
        if (celsius < 0) {
            description = 'Temperatura abaixo de zero - congelamento';
            icon = '❄️';
        } else if (celsius >= 0 && celsius < 30) {
            description = 'Temperatura ambiente';
            icon = '🌤️';
        } else if (celsius >= 30 && celsius < 60) {
            description = 'Temperatura morna';
            icon = '☀️';
        } else if (celsius >= 60 && celsius < 100) {
            description = 'Temperatura quente';
            icon = '🔥';
        } else if (celsius >= 100) {
            description = 'Temperatura de ebulição';
            icon = '💨';
        }
        
        tempResult.innerHTML = `
            <div style="text-align: center;">
                <div style="font-size: 1.3rem; margin-bottom: 10px;">
                    ${fahrenheit}°F
                </div>
                <div style="font-size: 1.8rem; color: var(--primary); font-weight: bold; margin-bottom: 10px;">
                    = ${celsius.toFixed(1)}°C
                </div>
                <div style="font-size: 0.9rem; color: var(--gray);">
                    ${icon} ${description}
                </div>
            </div>
        `;
        tempResult.classList.add('active');
    }
});

// Limpar resultados ao modificar os inputs
document.getElementById('ingredient').addEventListener('change', function() {
    cupsResult.classList.remove('active');
});

document.getElementById('cups').addEventListener('input', function() {
    cupsResult.classList.remove('active');
});

document.getElementById('fahrenheit').addEventListener('input', function() {
    tempResult.classList.remove('active');
});
</script>

<?php get_footer(); ?>