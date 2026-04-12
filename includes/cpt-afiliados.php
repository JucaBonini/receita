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
