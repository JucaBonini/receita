<?php
/**
 * Post Type: E-books com Regras de Negócio (Grátis/Pago)
 */

function sts_register_cpt_ebooks() {
    $labels = array(
        'name'               => 'E-books',
        'singular_name'      => 'E-book',
        'menu_name'          => 'Biblioteca Digital',
        'add_new'            => 'Novo E-book',
        'add_new_item'       => 'Adicionar Novo E-book',
        'edit_item'          => 'Editar E-book',
        'all_items'          => 'Todos os E-books',
        'search_items'       => 'Procurar E-books',
        'not_found'          => 'Nenhum e-book encontrado',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'ebook'),
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('ebook', $args);
}
add_action('init', 'sts_register_cpt_ebooks');

// Meta Boxes: Configurações do E-book e Regras de Negócio
function sts_ebook_add_meta_boxes() {
    add_meta_box('ebook_details', 'Configurações do E-book', 'sts_ebook_meta_box_html', 'ebook', 'normal', 'high');
}
add_action('add_meta_boxes', 'sts_ebook_add_meta_boxes');

function sts_ebook_meta_box_html($post) {
    $subtitle    = get_post_meta($post->ID, '_ebook_subtitle', true);
    $pages       = get_post_meta($post->ID, '_ebook_pages', true);
    $pdf_url     = get_post_meta($post->ID, '_ebook_pdf', true);
    $is_featured = get_post_meta($post->ID, '_ebook_featured', true);
    
    // Regras de Negócio (Novas!)
    $type        = get_post_meta($post->ID, '_ebook_type', true) ?: 'free';
    $price       = get_post_meta($post->ID, '_ebook_price', true);
    $pay_link    = get_post_meta($post->ID, '_ebook_payment_url', true);
    ?>
    <div style="padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 12px;">
        <!-- Básicos -->
        <p>
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Subtítulo (Curto):</label>
            <input type="text" name="ebook_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat" placeholder="Ex: Receitas de Páscoa">
        </p>
        <p>
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Número de Páginas:</label>
            <input type="number" name="ebook_pages" value="<?php echo esc_attr($pages); ?>" class="widefat">
        </p>
        <p>
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">URL do PDF (Media Library):</label>
            <input type="text" name="ebook_pdf" id="ebook_pdf" value="<?php echo esc_attr($pdf_url); ?>" style="width: 80%;">
            <button type="button" class="button st-upload-pdf">Subir PDF</button>
        </p>

        <hr style="margin: 20px 0;">

        <!-- Monetização (Regras de Negócio) -->
        <div style="background: #fff; padding: 15px; border-left: 4px solid #2271b1; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="margin-top: 0; color: #2271b1;">🛒 Regras de Monetização</h4>
            <p>
                <label style="font-weight: bold;">Tipo de Acesso:</label><br>
                <label><input type="radio" name="ebook_type" value="free" <?php checked($type, 'free'); ?>> Gratuito (Acesso Imediato)</label> &nbsp; 
                <label><input type="radio" name="ebook_type" value="paid" <?php checked($type, 'paid'); ?>> Pago (Bloqueado/Premium)</label>
            </p>
            <p>
                <label style="font-weight: bold;">Preço Simbólico (R$):</label>
                <input type="text" name="ebook_price" value="<?php echo esc_attr($price); ?>" class="widefat" placeholder="Ex: 9,90">
            </p>
            <p>
                <label style="font-weight: bold;">Link de Pagamento (Mercado Pago / Checkout):</label>
                <input type="url" name="ebook_payment_url" value="<?php echo esc_url($pay_link); ?>" class="widefat" placeholder="https://link.mercadopago.com.br/seu-ebook">
            </p>
        </div>

        <p>
            <label><input type="checkbox" name="ebook_featured" <?php checked($is_featured, 'on'); ?>> Destacar na Biblioteca</label>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($){
        $('.st-upload-pdf').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Escolher PDF',
                button: { text: 'Usar este PDF' },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#ebook_pdf').val(attachment.url);
            }).open();
        });
    });
    </script>
    <?php
}

function sts_ebook_save_meta_box($post_id) {
    $fields = array(
        'ebook_subtitle'    => '_ebook_subtitle',
        'ebook_pages'       => '_ebook_pages',
        'ebook_pdf'         => '_ebook_pdf',
        'ebook_featured'    => '_ebook_featured',
        'ebook_type'        => '_ebook_type',
        'ebook_price'       => '_ebook_price',
        'ebook_payment_url' => '_ebook_payment_url'
    );

    foreach ($fields as $key => $meta_key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$key]));
        } else if ($key == 'ebook_featured') {
             delete_post_meta($post_id, $meta_key);
        }
    }
}
add_action('save_post', 'sts_ebook_save_meta_box');
