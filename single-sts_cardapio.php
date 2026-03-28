<?php
/**
 * Template Name: Cardápio App (Fiel ao Modelo do Usuário)
 * Template Post Type: sts_cardapio
 */

get_header();

$cardapio_data = get_post_meta(get_the_ID(), '_sts_cardapio_data', true) ?: array();
$dias_labels = array(
    'domingo' => array('label' => 'Domingo', 'num' => '07'),
    'segunda' => array('label' => 'Segunda', 'num' => '01'),
    'terca'   => array('label' => 'Terça', 'num' => '02'),
    'quarta'  => array('label' => 'Quarta', 'num' => '03'),
    'quinta'  => array('label' => 'Quinta', 'num' => '04'),
    'sexta'   => array('label' => 'Sexta', 'num' => '05'),
    'sabado'  => array('label' => 'Sábado', 'num' => '06')
);

// Reordenar para começar na segunda se preferir, ou manter como está
$all_ids = array();
foreach($cardapio_data as $dia) if(is_array($dia)) foreach($dia as $id) if($id) $all_ids[] = $id;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* CSS FIEL AO MODELO FORNECIDO */
    :root {
        --primary: #e74c3c;
        --primary-dark: #c0392b;
        --secondary: #2ecc71;
        --dark: #2c3e50;
        --light: #ecf0f1;
        --gray: #95a5a6;
        --transition: all 0.3s ease;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --radius: 8px;
    }
    
    body {
        font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9f9f9;
        padding-bottom: 80px;
    }
    
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Week Selector */
    .week-selector {
        background: white;
        padding: 15px 0;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 72px; /* Ajuste para o header do seu site */
        z-index: 900;
    }
    
    .week-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    
    .week-btn {
        background: var(--light);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        color: var(--dark);
        text-decoration: none;
    }
    
    .week-btn:hover {
        background: var(--primary);
        color: white;
    }
    
    .current-week {
        text-align: center;
        flex: 1;
    }
    
    .week-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .week-dates {
        font-size: 0.9rem;
        color: var(--gray);
    }
    
    /* Day Card */
    .day-card {
        background: white;
        border-radius: var(--radius);
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    
    .day-card.active {
        border-left: 5px solid var(--primary);
    }
    
    .day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }
    
    .day-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .day-number {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 50%;
        box-shadow: var(--shadow);
    }
    
    .day-name { font-weight: 700; color: var(--dark); }
    .day-date { font-size: 0.85rem; color: var(--gray); }
    
    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--gray);
        margin-left: 5px;
    }
    .status-dot.completed { background: var(--secondary); }
    
    /* Meal Section */
    .meal-section {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    .meal-section:last-child { border-bottom: none; }
    
    .meal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .meal-title { font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 0.95rem; }
    .meal-icon { color: var(--primary); }
    .meal-time { font-size: 0.8rem; color: var(--gray); }
    
    .meal-content { display: flex; gap: 15px; align-items: flex-start; text-decoration: none; color: inherit; }
    
    .meal-image {
        width: 90px;
        height: 90px;
        border-radius: var(--radius);
        overflow: hidden;
        flex-shrink: 0;
        background: #eee;
    }
    .meal-image img { width: 100%; height: 100%; object-fit: cover; }
    
    .meal-details { flex: 1; min-width: 0; }
    .meal-name { font-weight: 700; color: var(--dark); margin-bottom: 4px; font-size: 1rem; line-height: 1.3; }
    .meal-description { font-size: 0.85rem; color: var(--gray); margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .meal-tag {
        display: inline-block;
        padding: 3px 10px;
        background: #f0f2f5;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--dark);
        margin-right: 5px;
    }
    
    /* Bottom Nav & Items */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.08);
        z-index: 1000;
    }
    
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--gray);
        flex: 1;
        transition: var(--transition);
        border: none;
        background: none;
        cursor: pointer;
    }
    
    .nav-item.active { color: var(--primary); }
    .nav-icon { font-size: 1.3rem; margin-bottom: 4px; }
    .nav-label { font-size: 0.7rem; font-weight: 700; }

    /* Stats Card */
    .stats-card { background: white; border-radius: var(--radius); padding: 20px; margin-top: 20px; box-shadow: var(--shadow); }
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .stat-item { text-align: center; padding: 15px; background: #f8f9fa; border-radius: var(--radius); }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 2px; }
    .stat-label { font-size: 0.75rem; color: var(--gray); font-weight: 600; }

    /* Floating Action */
    .quick-actions { position: fixed; bottom: 85px; right: 20px; z-index: 999; }
    .action-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        transition: var(--transition);
    }
    .action-btn:hover { transform: scale(1.1); background: var(--primary-dark); }

    /* Shopping List Modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex; align-items: center; justify-content: center;
        z-index: 2000; padding: 20px;
        opacity: 0; visibility: hidden; transition: var(--transition);
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-content {
        background: white; border-radius: 12px;
        width: 100%; max-width: 500px; max-height: 80vh; overflow-y: auto;
        padding: 30px; position: relative;
    }
    .modal-close { position: absolute; top: 15px; right: 15px; border: none; background: none; font-size: 1.5rem; cursor: pointer; color: var(--gray); }

    /* Utilities */
    .hidden { display: none !important; }
</style>

<div class="cardapio-app-wrapper">

    <!-- Week Selector -->
    <section class="week-selector">
        <div class="container">
            <div class="week-nav">
                <?php 
                $prev = get_previous_post();
                $next = get_next_post();
                ?>
                <a href="<?php echo ($prev) ? get_permalink($prev->ID) : '#'; ?>" class="week-btn <?php echo !$prev ? 'opacity-30' : ''; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <div class="current-week">
                    <?php 
                    $mes_ctrl = get_post_meta(get_the_ID(), '_sts_cardapio_mes', true) ?: date_i18n('F');
                    $sem_ctrl = get_post_meta(get_the_ID(), '_sts_cardapio_semana', true) ?: 'Semana 1';
                    ?>
                    <h2 class="week-title"><?php echo esc_html($sem_ctrl); ?></h2>
                    <p class="week-dates"><?php echo esc_html($mes_ctrl); ?> de <?php echo date('Y'); ?></p>
                </div>
                
                <a href="<?php echo ($next) ? get_permalink($next->ID) : '#'; ?>" class="week-btn <?php echo !$next ? 'opacity-30' : ''; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container meal-plan-container" id="cardapio-view">
        <div id="mealPlan">
            <?php 
            $i = 0;
            $hoje = strtolower(date_i18n('l'));
            foreach ($dias_labels as $day_key => $day_info) : 
                $i++;
                $is_active = ($day_key == $hoje);
                $meals = isset($cardapio_data[$day_key]) ? $cardapio_data[$day_key] : array();
                
                $meals_config = array(
                    'cafe' => array('label' => 'Café da Manhã', 'icon' => 'fas fa-coffee', 'time' => '07:30'),
                    'almoco' => array('label' => 'Almoço', 'icon' => 'fas fa-utensils', 'time' => '12:30'),
                    'jantar' => array('label' => 'Jantar', 'icon' => 'fas fa-moon', 'time' => '19:30')
                );
            ?>
                <div class="day-card <?php echo $is_active ? 'active' : ''; ?>">
                    <div class="day-header">
                        <div class="day-info">
                            <div class="day-number"><?php echo sprintf('%02d', $i); ?></div>
                            <div>
                                <div class="day-name"><?php echo $day_info['label']; ?></div>
                                <div class="day-date"><?php echo ($is_active) ? 'Hoje' : ''; ?></div>
                            </div>
                        </div>
                        <div class="day-status">
                            <span class="status-dot completed"></span>
                            <span class="status-dot completed"></span>
                            <span class="status-dot"></span>
                        </div>
                    </div>

                    <?php foreach ($meals_config as $m_key => $m_data) : 
                        $recipe_id = isset($meals[$m_key]) ? $meals[$m_key] : null;
                        if ($recipe_id) :
                            $recipe = get_post($recipe_id);
                            $thumb = get_the_post_thumbnail_url($recipe_id, 'medium');
                            $tempo = get_post_meta($recipe_id, '_tempo_preparo', true) ?: '20 min';
                    ?>
                        <div class="meal-section">
                            <div class="meal-header">
                                <h4 class="meal-title">
                                    <i class="<?php echo $m_data['icon']; ?> meal-icon"></i>
                                    <?php echo $m_data['label']; ?>
                                </h4>
                                <span class="meal-time"><?php echo $m_data['time']; ?></span>
                            </div>
                            <a href="<?php echo get_permalink($recipe_id); ?>" class="meal-content">
                                <div class="meal-image">
                                    <img src="<?php echo $thumb; ?>" alt="<?php echo $recipe->post_title; ?>">
                                </div>
                                <div class="meal-details">
                                    <h4 class="meal-name"><?php echo $recipe->post_title; ?></h4>
                                    <p class="meal-description"><?php echo wp_trim_words($recipe->post_content, 12, '...'); ?></p>
                                    <div class="meal-tags">
                                        <span class="meal-tag">Rápido</span>
                                        <span class="meal-tag"><?php echo $tempo; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Shopping Modal Overlay -->
    <div class="modal-overlay" id="shoppingModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeShoppingModal()">&times;</button>
            <h3 style="font-weight:800; text-transform:uppercase; color:var(--primary); margin-bottom:20px;">Lista da Semana</h3>
            <div id="shoppingListContent">
                 <div style="text-align:center; padding:40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size:2rem; color:var(--primary);"></i>
                    <p style="margin-top:15px; font-weight:600;">Gerando sua lista...</p>
                 </div>
            </div>
        </div>
    </div>

</div>

<script>
const cardapioRecipeIds = <?php echo json_encode($all_ids); ?>;

function changeView(view) {
    if(view === 'cardapio') {
         window.scrollTo({top: 0, behavior: 'smooth'});
    }
}

async function openFullShoppingList() {
    const modal = document.getElementById('shoppingModal');
    modal.classList.add('active');
    
    const content = document.getElementById('shoppingListContent');
    
    try {
        const response = await fetch(window.themeConfig.ajaxUrl + '?action=get_cardapio_ingredients&ids=' + cardapioRecipeIds.join(','));
        const res = await response.json();

        if (res.success) {
            let html = '<ul style="list-style:none; padding:0;">';
            res.data.forEach(item => {
                html += `
                <li style="padding:12px 0; border-bottom:1px solid #eee; display:flex; align-items:center;">
                    <input type="checkbox" style="width:20px; height:20px; margin-right:15px; accent-color:var(--primary);">
                    <span style="font-weight:600; font-size:0.9rem; color:var(--dark);">${item}</span>
                </li>`;
            });
            html += '</ul>';
            html += '<button onclick="window.print()" class="week-btn" style="width:100%; border-radius:8px; margin-top:20px; background:var(--primary); color:white;">Imprimir Lista</button>';
            content.innerHTML = html;
        } else {
            content.innerHTML = '<p>Erro ao carregar lista.</p>';
        }
    } catch(e) {
        content.innerHTML = '<p>Erro fatal ao carregar ingredientes.</p>';
    }
}

function closeShoppingModal() {
    document.getElementById('shoppingModal').classList.remove('active');
}
</script>

<?php get_footer(); ?>
