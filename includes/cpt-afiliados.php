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

/**
 * Página de Configurações da API Shopee (Submenu de Indicações)
 */
function sts_add_shopee_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=sts_indicacoes',
        'Configurações Shopee',
        'Configurações Shopee',
        'manage_options',
        'sts_shopee_settings',
        'sts_render_shopee_settings_page'
    );
}
add_action('admin_menu', 'sts_add_shopee_settings_menu');

function sts_render_shopee_settings_page() {
    // Salvar as opções se enviadas
    if (isset($_POST['sts_save_shopee_settings']) && check_admin_referer('sts_shopee_settings_nonce', 'sts_shopee_settings_nonce_field')) {
        update_option('sts_shopee_app_id', sanitize_text_field($_POST['sts_shopee_app_id']));
        update_option('sts_shopee_secret_key', sanitize_text_field($_POST['sts_shopee_secret_key']));
        echo '<div class="updated"><p>Configurações salvas com sucesso!</p></div>';
    }

    $app_id = get_option('sts_shopee_app_id', '');
    $secret_key = get_option('sts_shopee_secret_key', '');
    ?>
    <div class="wrap">
        <h1>Configurações da API Shopee</h1>
        <p class="description">Insira suas credenciais da API de Afiliados da Shopee obtidas no Console de Afiliados.</p>
        <form method="post" action="">
            <?php wp_nonce_field('sts_shopee_settings_nonce', 'sts_shopee_settings_nonce_field'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">App ID da Shopee:</th>
                    <td><input type="text" name="sts_shopee_app_id" value="<?php echo esc_attr($app_id); ?>" class="regular-text" placeholder="Ex: 123456789" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Secret Key da Shopee:</th>
                    <td><input type="password" name="sts_shopee_secret_key" value="<?php echo esc_attr($secret_key); ?>" class="regular-text" placeholder="Sua Chave Secreta da API" /></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="sts_save_shopee_settings" class="button-primary" value="Salvar Configurações" />
            </p>
        </form>
    </div>
    <?php
}

/**
 * Enqueue scripts e styles no admin de Indicações
 */
function sts_enqueue_shopee_importer_scripts($hook) {
    global $post_type;
    if ($post_type !== 'sts_indicacoes') return;

    wp_enqueue_script('sts-shopee-importer', get_template_directory_uri() . '/assets/js/shopee-importer.js', array('jquery'), '1.0.0', true);
    wp_localize_script('sts-shopee-importer', 'sts_shopee_importer', array(
        'nonce' => wp_create_nonce('sts_fetch_shopee_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'sts_enqueue_shopee_importer_scripts');

/**
 * Endpoint AJAX para buscar dados do produto na Shopee
 */
add_action('wp_ajax_sts_fetch_shopee_product', 'sts_ajax_fetch_shopee_product');
function sts_ajax_fetch_shopee_product() {
    check_ajax_referer('sts_fetch_shopee_nonce', 'nonce');
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Permissão negada.');
    }

    $product_url = isset($_POST['product_url']) ? esc_url_raw($_POST['product_url']) : '';
    if (empty($product_url)) {
        wp_send_json_error('URL do produto inválida.');
    }

    // Expandir URLs encurtadas da Shopee (shope.ee) ou links de redirecionamento universal
    if (strpos($product_url, 'shope.ee') !== false || strpos($product_url, 'shopee.com.br/universal-link') !== false) {
        $response = wp_remote_head($product_url, array('redirection' => 5));
        if (!is_wp_error($response)) {
            $effective_url = wp_remote_retrieve_header($response, 'location');
            if ($effective_url) {
                $product_url = $effective_url;
            }
        }
    }

    // Extração do ItemID da URL da Shopee usando Regex
    $item_id = 0;
    if (preg_match('/-i\.(\d+)\.(\d+)/', $product_url, $matches)) {
        $item_id = (int)$matches[2];
    } elseif (preg_match('/\/product\/(\d+)\/(\d+)/', $product_url, $matches)) {
        $item_id = (int)$matches[2];
    } elseif (preg_match('/itemid=(\d+)/i', $product_url, $matches)) {
        $item_id = (int)$matches[1];
    }

    if (!$item_id) {
        wp_send_json_error('Não foi possível extrair o ID do produto da URL. Certifique-se de colar uma URL de produto válida.');
    }

    // Buscar as credenciais configuradas
    $app_id = get_option('sts_shopee_app_id', '');
    $secret = get_option('sts_shopee_secret_key', '');

    if (empty($app_id) || empty($secret)) {
        wp_send_json_error('Credenciais da API da Shopee não configuradas. Acesse o menu lateral Indicações Mary > Configurações Shopee.');
    }

    $timestamp = time();
    $graphql_query = array(
        'query' => 'query FetchProduct($itemId: Long!) { productOfferV2(itemId: $itemId) { nodes { itemId productName priceMin productLink imageUrls } } }',
        'variables' => array('itemId' => $item_id)
    );
    $payload = json_encode($graphql_query);

    // Gerar Assinatura HMAC-SHA256
    $base_string = $app_id . $timestamp . $payload . $secret;
    $signature = hash_hmac('sha256', $base_string, $secret);

    // Preparar cabeçalhos da requisição
    $headers = array(
        'Content-Type'  => 'application/json',
        'Authorization' => "SHA256 Credential={$app_id},Timestamp={$timestamp},Signature={$signature}"
    );

    // Enviar requisição para o endpoint oficial
    $api_url = 'https://open-api.affiliate.shopee.com.br/graphql';
    $response = wp_remote_post($api_url, array(
        'headers'   => $headers,
        'body'      => $payload,
        'method'    => 'POST',
        'timeout'   => 15
    ));

    if (is_wp_error($response)) {
        wp_send_json_error('Erro ao conectar com o servidor da Shopee: ' . $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['errors']) && !empty($data['errors'])) {
        wp_send_json_error('Erro da API Shopee: ' . $data['errors'][0]['message']);
    }

    $nodes = isset($data['data']['productOfferV2']['nodes']) ? $data['data']['productOfferV2']['nodes'] : array();
    if (empty($nodes)) {
        wp_send_json_error('Produto não encontrado ou indisponível na API de afiliados da Shopee.');
    }

    $product = $nodes[0];
    $title = $product['productName'];
    $price = isset($product['priceMin']) ? 'R$ ' . number_format($product['priceMin'], 2, ',', '.') : '';
    $image_url = !empty($product['imageUrls']) ? $product['imageUrls'][0] : '';
    $affiliate_link = isset($product['productLink']) ? $product['productLink'] : '';

    // Tratar imagem destacada
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $featured_image_id = 0;

    if ($post_id && $image_url) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attachment_id = media_sideload_image($image_url, $post_id, $title, 'id');
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, $attachment_id);
            $featured_image_id = $attachment_id;
        }
    }

    wp_send_json_success(array(
        'title'          => $title,
        'price'          => $price,
        'affiliate_link' => $affiliate_link,
        'image_id'       => $featured_image_id,
        'image_url'      => $image_url ? esc_url($image_url) : ''
    ));
}
