<?php
/**
 * STS PagSeguro (PagBank) Handler
 * Ponte de comunicação para Checkout Transparente
 */

if (!defined('ABSPATH')) exit; // Segurança

// CONFIGURAÇÃO: Substitua pelos seus dados do PagSeguro (sandbox = teste / production = real)
define('PAGSEGURO_ENV', 'production'); 
define('PAGSEGURO_EMAIL', 'seu-email@exemplo.com'); 
define('PAGSEGURO_TOKEN', 'SEU-TOKEN-AQUI'); 

class STSPagSeguro {

    private static $api_url = PAGSEGURO_ENV === 'production' 
        ? 'https://api.pagseguro.com/orders' 
        : 'https://sandbox.api.pagseguro.com/orders';

    public static function process_checkout() {
        // 1. Receber dados do AJAX
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) wp_send_json_error(['message' => 'Dados inválidos']);

        $ebook_id = intval($data['ebook_id']);
        $price = get_post_meta($ebook_id, '_ebook_price', true);
        $amount = intval(str_replace([',', '.'], '', $price)); // Preço em centavos (ex: 990 para R$ 9,90)

        // 2. Montar Payload (PagBank API v4)
        $payload = [
            'reference_id' => 'EBOOK-' . $ebook_id . '-' . time(),
            'customer' => [
                'name'  => $data['payer_name'] ?: 'Cliente Biblioteca',
                'email' => $data['payer_email'] ?: 'cliente@exemplo.com',
                'tax_id' => str_replace(['.', '-'], '', $data['payer_cpf']),
                'phones' => [
                    [
                        'country' => '55',
                        'area'    => substr(preg_replace('/\D/', '', $data['payer_phone']), 0, 2),
                        'number'  => substr(preg_replace('/\D/', '', $data['payer_phone']), 2),
                        'type'    => 'MOBILE'
                    ]
                ]
            ],
            'items' => [
                [
                    'name' => 'E-book: ' . get_the_title($ebook_id),
                    'quantity' => 1,
                    'unit_amount' => $amount ?: 990
                ]
            ],
            'notification_urls' => [ get_site_url() . '/wp-json/sts/v1/pagseguro-notification' ]
        ];

        // 3. Método de Pagamento (PIX ou CARTÃO)
        if ($data['method'] === 'pix') {
            $payload['qr_codes'] = [
                [
                    'amount' => [ 'value' => $amount ?: 990 ],
                    'expiration_date' => date('c', strtotime('+1 hour'))
                ]
            ];
        } else {
            // Cartão de Crédito (Simplificado para o exemplo)
            $payload['charge'] = [
                'amount' => [ 'value' => $amount ?: 990, 'currency' => 'BRL' ],
                'payment_method' => [
                    'type' => 'CREDIT_CARD',
                    'installments' => 1,
                    'capture' => true,
                    'card' => [
                        'encrypted' => $data['card_token']
                    ]
                ]
            ];
        }

        // 4. Disparar Requisição
        $response = wp_remote_post(self::$api_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . PAGSEGURO_TOKEN,
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode($payload),
            'timeout' => 45
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Erro na conexão: ' . $response->get_error_message()]);
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        // 5. Retornar Resultado
        if (isset($body['qr_codes'][0]['text'])) {
            wp_send_json_success([
                'type' => 'pix',
                'qrcode' => $body['qr_codes'][0]['links'][0]['href'], // Link para imagem do QR
                'copy'   => $body['qr_codes'][0]['text'] // Texto Copia e Cola
            ]);
        } elseif (isset($body['status']) && $body['status'] === 'PAID') {
            wp_send_json_success(['type' => 'approved', 'message' => 'Pagamento Aprovado!']);
        } else {
            wp_send_json_error(['message' => 'Pagamento Pendente ou Recusado', 'log' => $body]);
        }
    }
}

// Hook para Processar o Checkout via AJAX (WordPress Native)
add_action('wp_ajax_sts_process_payment', ['STSPagSeguro', 'process_checkout']);
add_action('wp_ajax_nopriv_sts_process_payment', ['STSPagSeguro', 'process_checkout']);
