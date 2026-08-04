<?php
/**
 * CLOUDFLARE NATIVE CACHE PURGE - THEME VERSION 1.0.12
 * Limpeza automática de cache para Google Discover & Performance
 */

if (!defined('ABSPATH')) exit;

/**
 * 1. Registrar Configurações no Customizer
 */
function sts_customize_register_cloudflare($wp_customize) {
    if (!$wp_customize) return;

    $wp_customize->add_section('sts_cloudflare_section', array(
        'title'    => __('Integração Cloudflare', 'sts-recipe-2'),
        'priority' => 31,
        'description' => 'Configure aqui as chaves para limpeza automática de cache.'
    ));

    $settings = array(
        'cf_zone_id' => 'Cloudflare Zone ID',
        'cf_api_email' => 'Cloudflare Email',
        'cf_api_key' => 'Cloudflare API Key (Global)',
    );

    foreach ($settings as $id => $label) {
        $wp_customize->add_setting($id, array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control($id, array(
            'label'    => $label,
            'section'  => 'sts_cloudflare_section',
            'type'     => 'text',
        ));
    }
}
add_action('customize_register', 'sts_customize_register_cloudflare');

/**
 * 2. Função Principal de Limpeza (Purge)
 */
function sts_purge_cloudflare_cache($urls = array()) {
    $zone_id = get_theme_mod('cf_zone_id');
    $email   = get_theme_mod('cf_api_email');
    $api_key = get_theme_mod('cf_api_key');

    if (empty($zone_id) || empty($email) || empty($api_key)) {
        return false;
    }

    $endpoint = "https://api.cloudflare.com/client/v4/zones/" . trim($zone_id) . "/purge_cache";
    
    // Se não passar URLs, limpa TUDO (Purge Everything)
    $data = empty($urls) ? array('purge_everything' => true) : array('files' => $urls);

    $response = wp_remote_post($endpoint, array(
        'headers' => array(
            'X-Auth-Email' => trim($email),
            'X-Auth-Key'   => trim($api_key),
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($data),
        'method'    => 'POST',
        'timeout'   => 15
    ));

    if (is_wp_error($response)) {
        error_log('[Cloudflare] Erro na requisição: ' . $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response));
    return isset($body->success) && $body->success;
}

/**
 * 3. Gatilho Automático: Ao salvar post ou atualizar configurações
 */
function sts_cf_purge_on_post_update($post_id, $post, $update) {
    // Não limpa se for rascunho ou revisão
    if (wp_is_post_revision($post_id) || $post->post_status != 'publish') {
        return;
    }

    // URLs para limpar: O próprio post e a Home
    $urls = array(
        get_permalink($post_id),
        home_url('/')
    );

    // Limpa também a categoria principal do post
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        foreach ($categories as $cat) {
            $urls[] = get_category_link($cat->term_id);
        }
    }

    sts_purge_cloudflare_cache($urls);
}
add_action('wp_after_insert_post', 'sts_cf_purge_on_post_update', 10, 3);

/**
 * 4. Adicionar Botão de Limpeza Manual no Admin Bar
 */
function sts_cf_admin_bar_purge($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;

    $wp_admin_bar->add_node(array(
        'id'    => 'sts-purge-cf',
        'title' => '<span class="ab-icon dashicons dashicons-cloud"></span> Limpar Cloudflare',
        'href'  => wp_nonce_url(admin_url('admin-post.php?action=sts_manual_purge_cf'), 'sts_purge_cf_nonce'),
        'meta'  => array('title' => 'Limpa o cache de todo o site na Cloudflare')
    ));
}
add_action('admin_bar_menu', 'sts_cf_admin_bar_purge', 100);

/**
 * 5. Handler do clique manual
 */
function sts_handle_manual_purge_cf() {
    if (!current_user_can('manage_options')) wp_die('Acesso negado.');
    check_admin_referer('sts_purge_cf_nonce');

    $result = sts_purge_cloudflare_cache(); // Sem URLs = Purge Everything

    if ($result) {
        set_transient('sts_cf_msg', 'Cache da Cloudflare limpo com sucesso!', 30);
    } else {
        set_transient('sts_cf_msg', 'Erro ao limpar cache. Verifique suas credenciais no Customizer.', 30);
    }

    wp_redirect(wp_get_referer() ?: admin_url());
    exit;
}
add_action('admin_post_sts_manual_purge_cf', 'sts_handle_manual_purge_cf');

/**
 * 6. Mostrar Notificação após limpeza
 */
function sts_cf_admin_notices() {
    $msg = get_transient('sts_cf_msg');
    if ($msg) {
        $class = strpos($msg, 'sucesso') !== false ? 'notice-success' : 'notice-error';
        echo "<div class='notice $class is-dismissible'><p>$msg</p></div>";
        delete_transient('sts_cf_msg');
    }
}
add_action('admin_notices', 'sts_cf_admin_notices');
