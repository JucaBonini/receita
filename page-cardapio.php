<?php
/* Template Name: Cardápio da Semana */
get_header('header-cardapio');

/**
 * Data atual
 */
$hoje = date('Y-m-d');

/**
 * Busca apenas o cardápio da semana vigente
 */
$args = [
    'post_type' => 'cardapio',
    'posts_per_page' => 1,
    'meta_query' => [
        [
            'key'     => '_semana_inicio',
            'value'   => $hoje,
            'compare' => '<=',
            'type'    => 'DATE'
        ],
        [
            'key'     => '_semana_fim',
            'value'   => $hoje,
            'compare' => '>=',
            'type'    => 'DATE'
        ],
    ]
];

$query = new WP_Query($args);

if (!$query->have_posts()) :
?>
    <div class="container">
        <div class="empty-state">
            <div class="empty-icon">🍽️</div>
            <h2 class="empty-title">Cardápio indisponível</h2>
            <p class="empty-text">O cardápio desta semana ainda não foi publicado.</p>
        </div>
    </div>
<?php
    get_footer();
    exit;
endif;

$query->the_post();

$inicio = get_post_meta(get_the_ID(), '_semana_inicio', true);
$fim    = get_post_meta(get_the_ID(), '_semana_fim', true);
?>

<!-- HEADER -->
<header class="app-header">
    <div class="container header-content">
        <div>
            <h1 class="app-title">Cardápio da Semana</h1>
            <p class="app-subtitle">Receitas práticas e não repetitivas</p>
        </div>
    </div>
</header>

<!-- WEEK INFO -->
<section class="week-selector">
    <div class="container">
        <div class="current-week">
            <h2 class="week-title">
                Semana <?= date('W', strtotime($inicio)); ?>
            </h2>
            <p class="week-dates">
                <?= date('d/m', strtotime($inicio)); ?> - <?= date('d/m', strtotime($fim)); ?>
            </p>
        </div>
    </div>
</section>

<main class="container meal-plan-container">

<?php
/**
 * Dias da semana fixos
 */
$dias = [
    'segunda','terca','quarta','quinta','sexta','sabado','domingo'
];

foreach ($dias as $dia) :

    $args_refeicoes = [
        'post_type' => 'cardapio',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key'   => '_dia_semana',
                'value' => $dia
            ],
            [
                'key'   => '_semana_inicio',
                'value' => $inicio
            ]
        ]
    ];

    $refeicoes = new WP_Query($args_refeicoes);
    if (!$refeicoes->have_posts()) continue;
?>

<div class="day-card <?= ($dia === strtolower(date('l'))) ? 'active' : ''; ?>">
    <div class="day-header">
        <div class="day-info">
            <div class="day-number"><?= date('d'); ?></div>
            <div>
                <div class="day-name"><?= ucfirst($dia); ?></div>
            </div>
        </div>
    </div>

<?php while ($refeicoes->have_posts()) : $refeicoes->the_post();

    $receita_id = get_post_meta(get_the_ID(), '_receita_id', true);
    $tipo       = get_post_meta(get_the_ID(), '_tipo_refeicao', true);
    $horario    = get_post_meta(get_the_ID(), '_horario', true);

    $receita = get_post($receita_id);
?>

    <div class="meal-section">
        <div class="meal-header">
            <h4 class="meal-title">
                <i class="fas fa-utensils meal-icon"></i>
                <?= esc_html($tipo); ?>
            </h4>
            <span class="meal-time"><?= esc_html($horario); ?></span>
        </div>

        <div class="meal-content">
            <div class="meal-image">
                <?= get_the_post_thumbnail($receita_id, 'medium'); ?>
            </div>

            <div class="meal-details">
                <h4 class="meal-name"><?= esc_html($receita->post_title); ?></h4>
                <p class="meal-description">
                    <?= wp_trim_words($receita->post_content, 20); ?>
                </p>

                <a href="<?= get_permalink($receita_id); ?>" class="btn">
                    Ver Receita Completa
                </a>
            </div>
        </div>
    </div>

<?php endwhile; wp_reset_postdata(); ?>
</div>

<?php endforeach; ?>

</main>

<?php get_footer(); ?>


<?php
get_footer('footer-cardapio');