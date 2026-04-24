<?php
/**
 * STS Ads Master - Central de Monetização de Elite (God Mode)
 * Otimizado para LCP, CLS e Conversão Máxima.
 * Desenvolvido por Antigravity (Super Saiyajin Dev).
 */

if (!defined('ABSPATH')) exit;

/**
 * Renderiza um slot de anúncio com blindagem técnica
 */
function sts_render_ad($slot_name, $classes = '') {
    // Busca o código dinamicamente da Página ADS MASTER (Banco de Dados)
    $ad_code = get_option('sts_ad_' . $slot_name, '');

    // Se estiver vazio e não for admin, o slot fica invisível e não ocupa espaço
    if (empty($ad_code) && !is_user_logged_in()) return;

    // Inteligência de Dimensões (Para matar o CLS)
    $is_mobile = wp_is_mobile();
    $min_height = '100px';

    if (str_contains($slot_name, 'billboard')) $min_height = $is_mobile ? '100px' : '280px';
    if (str_contains($slot_name, 'sidebar'))   $min_height = '600px';
    if (str_contains($slot_name, 'mid'))       $min_height = '250px';

    echo "\n<!-- ADS MASTER: " . esc_html($slot_name) . " -->\n";
    ?>
    <style>
        .sts-ad-slot-<?php echo $slot_name; ?> {
            min-height: <?php echo $min_height; ?>;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center; justify-content: center;
            margin: 2rem auto;
            background: rgba(0,0,0,0.02);
            border-radius: 32px;
            position: relative;
            overflow: hidden; /* Blindagem contra vazamento lateral */
            transition: all 0.3s ease;
            z-index: 1;
        }
        /* Força qualquer conteúdo interno a respeitar o limite */
        .sts-ad-slot-<?php echo $slot_name; ?> * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        /* Efeito Skeleton (Movido para trás do conteúdo e sem interferência de clique) */
        .sts-ad-slot-<?php echo $slot_name; ?>::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(236, 91, 19, 0.05), transparent);
            transform: translateX(-100%);
            animation: sts-shimmer 2s infinite;
            z-index: -1; 
            pointer-events: none;
        }
        @keyframes sts-shimmer { 100% { transform: translateX(100%); } }
        .dark .sts-ad-slot-<?php echo $slot_name; ?> { background: rgba(255,255,255,0.03); }
    </style>

    <div class="sts-ad-slot-<?php echo $slot_name; ?> <?php echo esc_attr($classes); ?> ads-master-container">
        <?php if (empty($ad_code) && is_user_logged_in()) : ?>
            <div style="text-align: center; font-family: monospace; font-size: 10px; font-weight: 900; color: #ec5b13; letter-spacing: 0.1em;">
                <span class="block text-xl opacity-30 mb-2">⚡</span>
                ADS MASTER: <?php echo strtoupper(str_replace('_', ' ', $slot_name)); ?>
            </div>
        <?php else : ?>
            <div class="ads-master-wrapper w-full" style="position: relative; z-index: 10;">
                <!-- Top Wrapper (Label de Anúncio) -->
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="display: inline-block; border-top: 1px solid rgba(128,128,128,0.3); width: 250px; position: relative;">
                        <div style="display: inline-block; position: relative; top: -10px; background-color: white; font-size: 10px; padding: 0px 15px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.05em;" class="ad-label-text">
                            Anúncio
                        </div>
                    </div>
                </div>

                <!-- Container do AdSense (Block + Center para compatibilidade total) -->
                <div class="sts-ad-content" style="width: 100%; text-align: center; min-height: 50px;">
                    <?php 
                    // Limpeza automática de aspas e injeção do código
                    echo str_replace('\&quot;', '"', $ad_code); 
                    ?>
                </div>

                <!-- Bottom Wrapper -->
                <div style="text-align: center; margin-top: 15px;">
                    <div style="display: inline-block; border-bottom: 1px solid rgba(128,128,128,0.3); width: 250px;"></div>
                </div>
            </div>

            <style>
                .dark .ad-label-text { background-color: #0f172a !important; color: #475569 !important; }
            </style>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Injeção Automática no Conteúdo (Single Posts)
 * Injeta um anúncio após o parágrafo X
 */
add_filter('the_content', function($content) {
    if (!is_singular('post')) return $content;

    $ad_code = sts_render_ad_to_string('single_mid_paragraphs');
    if (empty($ad_code)) return $content;

    $paragraphs = explode('</p>', $content);
    $insert_after = 4; // Injetar após o 5º parágrafo para evitar conflito com WhatsApp Banner

    foreach ($paragraphs as $index => $paragraph) {
        if (trim($paragraph)) {
            $paragraphs[$index] .= ($index == $insert_after) ? $ad_code : '';
        }
    }

    return implode('</p>', $paragraphs);
}, 20);

/**
 * Função Auxiliar: Captura o render do Ad em String
 */
function sts_render_ad_to_string($slot_name) {
    ob_start();
    sts_render_ad($slot_name);
    return ob_get_clean();
}
