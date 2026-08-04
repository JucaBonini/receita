<?php
/**
 * CPT: Indicações de Afiliados (Indicações da Mary)
 */

if (!defined('ABSPATH')) exit;

function sts_register_afiliados_cpt() {
    $labels = array(
        'name'                  => 'Indicações',
        'singular_name'         => 'Indicação',
        'menu_name'             => 'Indicações Mary',
        'add_new'               => 'Nova Indicação',
        'add_new_item'          => 'Adicionar Nova Indicação',
        'edit_item'             => 'Editar Indicação',
        'all_items'             => 'Todas as Indicações',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-amazon',
        'supports'           => array('title', 'thumbnail'),
        'show_in_rest'       => true,
    );

    register_post_type('sts_indicacoes', $args);
}
add_action('init', 'sts_register_afiliados_cpt');

/**
 * Meta Boxes para Links e Marketplace
 */
function sts_add_indicacoes_meta_boxes() {
    add_meta_box('sts_indicacoes_meta', 'Detalhes do Produto', 'sts_render_indicacoes_meta_box', 'sts_indicacoes', 'normal', 'high');
}
add_action('add_meta_boxes', 'sts_add_indicacoes_meta_boxes');

function sts_render_indicacoes_meta_box($post) {
    $url = get_post_meta($post->ID, '_sts_product_url', true);
    $price = get_post_meta($post->ID, '_sts_product_price', true);
    $marketplace = get_post_meta($post->ID, '_sts_marketplace', true);
    
    wp_nonce_field('sts_indicacoes_meta_nonce', 'sts_indicacoes_meta_nonce_field');
    ?>
    <div style="padding: 10px 0;">
        <p><strong>Link de Afiliado:</strong></p>
        <input type="url" name="sts_product_url" value="<?php echo esc_url($url); ?>" style="width: 100%; padding: 8px;" placeholder="https://shope.ee/..." />
        
        <p><strong>Preço (Opcional):</strong></p>
        <input type="text" name="sts_product_price" value="<?php echo esc_attr($price); ?>" style="width: 100%; padding: 8px;" placeholder="Ex: R$ 49,90" />
        
        <p><strong>Marketplace:</strong></p>
        <select name="sts_marketplace" style="width: 100%; padding: 8px;">
            <option value="shopee" <?php selected($marketplace, 'shopee'); ?>>Shopee</option>
            <option value="amazon" <?php selected($marketplace, 'amazon'); ?>>Amazon</option>
            <option value="mercado_livre" <?php selected($marketplace, 'mercado_livre'); ?>>Mercado Livre</option>
            <option value="outros" <?php selected($marketplace, 'outros'); ?>>Outros</option>
        </select>
    </div>
    <?php
}

function sts_save_indicacoes_meta($post_id) {
    if (!isset($_POST['sts_indicacoes_meta_nonce_field']) || !wp_verify_nonce($_POST['sts_indicacoes_meta_nonce_field'], 'sts_indicacoes_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['sts_product_url'])) update_post_meta($post_id, '_sts_product_url', esc_url_raw($_POST['sts_product_url']));
    if (isset($_POST['sts_product_price'])) update_post_meta($post_id, '_sts_product_price', sanitize_text_field($_POST['sts_product_price']));
    if (isset($_POST['sts_marketplace'])) update_post_meta($post_id, '_sts_marketplace', sanitize_text_field($_POST['sts_marketplace']));
}
add_action('save_post', 'sts_save_indicacoes_meta');

/**
 * Adiciona Coluna na Listagem do Admin
 */
function sts_set_indicacoes_columns($columns) {
    $columns['sts_price'] = 'Preço';
    $columns['sts_marketplace'] = 'Marketplace';
    $columns['sts_impressions'] = 'Visualizações';
    $columns['date'] = 'Data';
    return $columns;
}
add_filter('manage_sts_indicacoes_posts_columns', 'sts_set_indicacoes_columns');

/**
 * Exibir dados nas colunas customizadas
 */
function sts_custom_indicacoes_column($column, $post_id) {
    switch ($column) {
        case 'sts_price':
            $price = get_post_meta($post_id, '_sts_product_price', true);
            echo $price ? esc_html($price) : '—';
            break;
            
        case 'sts_marketplace':
            $mkt = get_post_meta($post_id, '_sts_marketplace', true);
            $icons = array(
                'shopee' => '<span style="color:#D73211; font-weight:bold;">🛍️ Shopee</span>',
                'amazon' => '<span style="color:#FF9900; font-weight:bold;">📦 Amazon</span>',
                'mercado_livre' => '<span style="color:#000000; font-weight:bold;">🤝 M. Livre</span>'
            );
            echo isset($icons[$mkt]) ? $icons[$mkt] : '<span style="color:#94a3b8;">Outros</span>';
            break;

        case 'sts_impressions':
            $views = get_post_meta($post_id, '_sts_impressions', true);
            echo '<strong>' . ($views ? number_format($views, 0, ',', '.') : '0') . '</strong>';
            break;
    }
}
add_action('manage_sts_indicacoes_posts_custom_column', 'sts_custom_indicacoes_column', 10, 2);

/**
 * Tornar a coluna de visualizações ordenável
 */
function sts_impressions_column_sortable($columns) {
    $columns['sts_impressions'] = 'sts_impressions';
    return $columns;
}
add_filter('manage_edit-sts_indicacoes_sortable_columns', 'sts_impressions_column_sortable');

/**
 * Função para registrar visualização do produto
 */
function sts_track_product_impression($post_id) {
    if (empty($post_id)) return;
    $views = get_post_meta($post_id, '_sts_impressions', true);
    $views = $views ? (int)$views + 1 : 1;
    update_post_meta($post_id, '_sts_impressions', $views);
}
