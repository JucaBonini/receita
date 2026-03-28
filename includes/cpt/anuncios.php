<?php
/**
 * Advanced Ad Management System (Ad Inserter Engine)
 * Controls Ads with surgical precision: before/after P, H2, H3 tags in recipes.
 */

if (!defined('ABSPATH')) exit;

/**
 * 1. Register Ad System CPT (Anúncios)
 */
function sts_register_ads_cpt() {
    $labels = array(
        'name'               => 'Gerenciar Anúncios',
        'singular_name'      => 'Anúncio',
        'menu_name'          => 'Publicidade (ADS)',
        'add_new'            => 'Configurar Bloco ADS',
        'add_new_item'       => 'Configurar Novo Bloco de Anúncio',
        'edit_item'          => 'Editar Bloco ADS',
        'new_item'           => 'Novo Bloco ADS',
        'search_items'       => 'Buscar Blocos',
        'all_items'          => 'Ver Todos os Blocos',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-megaphone',
        'supports'           => array('title'), 
    );

    register_post_type('sts_anuncios', $args);
}
add_action('init', 'sts_register_ads_cpt');

/**
 * 2. Ad Configuration Metabox (Advanced Strategy)
 */
function sts_add_ad_metaboxes() {
    add_meta_box(
        'sts_ad_advanced_config',
        '⚙️ Configurações do Bloco (Estilo Ad Inserter)',
        'sts_render_advanced_ad_metabox',
        'sts_anuncios',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sts_add_ad_metaboxes');

function sts_render_advanced_ad_metabox($post) {
    wp_nonce_field('sts_save_ad_meta', 'sts_ad_nonce');
    
    $ad_code      = get_post_meta($post->ID, '_sts_ad_code', true);
    $ad_position  = get_post_meta($post->ID, '_sts_ad_position', true);
    $target_tag   = get_post_meta($post->ID, '_sts_ad_target_tag', true) ?: 'p';
    $target_logic = get_post_meta($post->ID, '_sts_ad_target_logic', true) ?: 'after';
    $target_index = get_post_meta($post->ID, '_sts_ad_target_index', true) ?: '1';
    $ad_status    = get_post_meta($post->ID, '_sts_ad_status', true) ?: 'active';
    ?>
    <style>
        .sts-ad-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .sts-ad-label { font-weight: bold; display: block; margin-bottom: 8px; color: #1e1e1e; }
        .sts-ad-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        .sts-ad-help { color: #666; font-size: 11px; margin-top: 5px; font-style: italic; }
    </style>

    <div style="padding: 10px;">
        <!-- Posição Universal -->
        <div style="margin-bottom: 30px; background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
            <label class="sts-ad-label">Posição Principal (Universal):</label>
            <select name="sts_ad_position" class="sts-ad-input" id="ad-pos-switcher">
                <option value="in_content" <?php selected($ad_position, 'in_content'); ?>>Dentro do Artigo (Injetor Cirúrgico)</option>
                <option value="before_content" <?php selected($ad_position, 'before_content'); ?>>Antes do Artigo (Single)</option>
                <option value="after_content" <?php selected($ad_position, 'after_content'); ?>>Fim do Artigo (Single)</option>
                <option value="header_top" <?php selected($ad_position, 'header_top'); ?>>Topo do Site (Universal)</option>
                <option value="after_title" <?php selected($ad_position, 'after_title'); ?>>Abaixo do Título (Single/Página)</option>
                <option value="after_recipe" <?php selected($ad_position, 'after_recipe'); ?>>Abaixo da Receita (Engajamento)</option>
                <option value="archive_grid" <?php selected($ad_position, 'archive_grid'); ?>>No Grids (Home/Arquivos)</option>
                <option value="sidebar" <?php selected($ad_position, 'sidebar'); ?>>Barra Lateral (Desktop)</option>
            </select>
            <p class="sts-ad-help">Se escolher "Dentro do Artigo", as opções cirúrgicas abaixo serão aplicadas automaticamente.</p>
        </div>

        <!-- Injetor Cirúrgico (Apenas se In-Content) -->
        <div id="surgical-injector-settings" style="margin-bottom: 30px; border-left: 4px solid #ec5b13; padding-left: 20px;">
            <h4 style="margin-top: 0;">🎯 Localização Cirúrgica no Artigo:</h4>
            <div class="sts-ad-grid">
                <div>
                    <label class="sts-ad-label">Aplicar em qual tag?</label>
                    <select name="sts_ad_target_tag" class="sts-ad-input">
                        <option value="p" <?php selected($target_tag, 'p'); ?>>Parágrafo (p)</option>
                        <option value="h2" <?php selected($target_tag, 'h2'); ?>>Título Médio (h2)</option>
                        <option value="h3" <?php selected($target_tag, 'h3'); ?>>Título Menor (h3)</option>
                    </select>
                </div>
                <div>
                    <label class="sts-ad-label">Lógica de Inserção:</label>
                    <select name="sts_ad_target_logic" class="sts-ad-input">
                        <option value="after" <?php selected($target_logic, 'after'); ?>>Depois de...</option>
                        <option value="before" <?php selected($target_logic, 'before'); ?>>Antes de...</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <label class="sts-ad-label">Número da Tag (Ex: 1 para o primeiro, 3 para o terceiro):</label>
                <input type="number" name="sts_ad_target_index" class="sts-ad-input" value="<?php echo esc_attr($target_index); ?>" min="1">
            </div>
        </div>

        <!-- Código do Anúncio -->
        <div style="margin-bottom: 25px;">
            <label class="sts-ad-label">Código do Anúncio (Scripts Google AdSense / HTML):</label>
            <textarea name="sts_ad_code" rows="12" class="sts-ad-input" style="font-family: monospace;" placeholder="Cole aqui seu script do Google AdSense..."><?php echo esc_textarea($ad_code); ?></textarea>
        </div>

        <!-- Status -->
        <div class="sts-ad-grid">
            <div>
                <label class="sts-ad-label">Status do Bloco:</label>
                <select name="sts_ad_status" class="sts-ad-input">
                    <option value="active" <?php selected($ad_status, 'active'); ?>>Bloco Ativo (Exibir)</option>
                    <option value="paused" <?php selected($ad_status, 'paused'); ?>>Bloco Pausado (Ocultar)</option>
                </select>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 3. Save Ad Meta with Security
 */
function sts_save_advanced_ad_config($post_id) {
    if (!isset($_POST['sts_ad_nonce']) || !wp_verify_nonce($_POST['sts_ad_nonce'], 'sts_save_ad_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array(
        'sts_ad_code', 'sts_ad_position', 'sts_ad_target_tag', 
        'sts_ad_target_logic', 'sts_ad_target_index', 'sts_ad_status'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = ($field === 'sts_ad_code') ? $_POST[$field] : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'sts_save_advanced_ad_config');

/**
 * 4. Master Display Function
 */
function sts_display_ad($position) {
    if ($position === 'in_content') return; // In-content handled by the filter loop

    $args = array(
        'post_type'      => 'sts_anuncios',
        'posts_per_page' => -1, // Use multi blocks for positions like archive_grid
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => '_sts_ad_position', 'value' => $position, 'compare' => '='),
            array('key' => '_sts_ad_status',   'value' => 'active',  'compare' => '=')
        )
    );

    $ad_query = new WP_Query($args);
    if ($ad_query->have_posts()) {
        while ($ad_query->have_posts()) {
            $ad_query->the_post();
            $code = get_post_meta(get_the_ID(), '_sts_ad_code', true);
            echo '<div class="sts-ad-space sts-ad-' . esc_attr($position) . ' py-8 flex justify-center items-center overflow-hidden">';
            echo $code;
            echo '</div>';
        }
        wp_reset_postdata();
    }
}