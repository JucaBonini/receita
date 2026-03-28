<?php
/**
 * CPT: Cardápio da Semana
 */
function dr_register_cpt_cardapio() {

    register_post_type('cardapio', [
        'labels' => [
            'name'               => 'Cardápios',
            'singular_name'      => 'Cardápio',
            'menu_name'          => 'Cardápio Semanal',
            'add_new'            => 'Adicionar Cardápio',
            'add_new_item'       => 'Novo Cardápio Semanal',
            'edit_item'          => 'Editar Cardápio',
            'new_item'           => 'Novo Cardápio',
            'view_item'          => 'Ver Cardápio',
            'search_items'       => 'Buscar Cardápio',
            'not_found'          => 'Nenhum cardápio encontrado',
            'not_found_in_trash' => 'Nenhum cardápio na lixeira',
        ],
        'public'              => true,
        'has_archive'         => false,
        'menu_icon'           => 'dashicons-calendar-alt',
        'rewrite'             => ['slug' => 'cardapio'],
        'supports'            => ['title'],
        'show_in_rest'        => true,
    ]);

}
add_action('init', 'dr_register_cpt_cardapio');
/**
 * Metas do Cardápio (Semana)
 */
function dr_register_cardapio_meta() {

    register_post_meta('cardapio', '_semana_inicio', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('cardapio', '_semana_fim', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('cardapio', '_dia_semana', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('cardapio', '_tipo_refeicao', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('cardapio', '_horario', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('cardapio', '_receita_id', [
        'type'         => 'integer',
        'single'       => true,
        'show_in_rest' => true,
    ]);

}
add_action('init', 'dr_register_cardapio_meta');
/**
 * Metabox Cardápio Semanal
 */
function dr_cardapio_metabox() {

    add_meta_box(
        'dr_cardapio_dados',
        'Dados do Cardápio',
        'dr_cardapio_metabox_callback',
        'cardapio',
        'normal',
        'high'
    );

}
add_action('add_meta_boxes', 'dr_cardapio_metabox');
/**
 * CPT: Receitas
 */
function dr_register_cpt_receitas() {

    register_post_type('receita', [
        'labels' => [
            'name'          => 'Receitas',
            'singular_name' => 'Receita',
            'menu_name'     => 'Receitas',
            'add_new_item'  => 'Nova Receita',
        ],
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-carrot',
        'rewrite'       => ['slug' => 'receitas'],
        'supports'      => ['title', 'editor', 'thumbnail'],
        'show_in_rest'  => true,
    ]);

}
add_action('init', 'dr_register_cpt_receitas');

/**
 * Conteúdo do Metabox
 */
function dr_cardapio_metabox_callback($post) {

    wp_nonce_field('dr_cardapio_nonce', 'dr_cardapio_nonce_field');

    $inicio   = get_post_meta($post->ID, '_semana_inicio', true);
    $fim      = get_post_meta($post->ID, '_semana_fim', true);
    $dia      = get_post_meta($post->ID, '_dia_semana', true);
    $tipo     = get_post_meta($post->ID, '_tipo_refeicao', true);
    $horario  = get_post_meta($post->ID, '_horario', true);
    $receita  = get_post_meta($post->ID, '_receita_id', true);

    $dias = [
        'segunda' => 'Segunda-feira',
        'terca'   => 'Terça-feira',
        'quarta' => 'Quarta-feira',
        'quinta' => 'Quinta-feira',
        'sexta'  => 'Sexta-feira',
        'sabado' => 'Sábado',
        'domingo'=> 'Domingo',
    ];

    $tipos = ['Café da Manhã', 'Almoço', 'Jantar'];

    $receitas = get_posts([
        'post_type'      => 'receita',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ]);
    ?>

    <style>
        .dr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .dr-field {
            display: flex;
            flex-direction: column;
        }
        .dr-field label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .dr-field input,
        .dr-field select {
            padding: 8px;
            max-width: 100%;
        }
    </style>

    <div class="dr-grid">

        <div class="dr-field">
            <label>Semana – Início</label>
            <input type="date" name="semana_inicio" value="<?= esc_attr($inicio); ?>">
        </div>

        <div class="dr-field">
            <label>Semana – Fim</label>
            <input type="date" name="semana_fim" value="<?= esc_attr($fim); ?>">
        </div>

        <div class="dr-field">
            <label>Dia da Semana</label>
            <select name="dia_semana">
                <option value="">Selecione</option>
                <?php foreach ($dias as $key => $label): ?>
                    <option value="<?= $key; ?>" <?= selected($dia, $key); ?>>
                        <?= $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="dr-field">
            <label>Tipo de Refeição</label>
            <select name="tipo_refeicao">
                <option value="">Selecione</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t; ?>" <?= selected($tipo, $t); ?>>
                        <?= $t; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="dr-field">
            <label>Horário</label>
            <input type="time" name="horario" value="<?= esc_attr($horario); ?>">
        </div>

        <div class="dr-field">
            <label>Receita</label>
            <select name="receita_id">
                <option value="">Selecione a receita</option>
                <?php foreach ($receitas as $r): ?>
                    <option value="<?= $r->ID; ?>" <?= selected($receita, $r->ID); ?>>
                        <?= esc_html($r->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

<?php
}

/**
 * Salvar Metabox
 */
function dr_save_cardapio_metabox($post_id) {

    if (!isset($_POST['dr_cardapio_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['dr_cardapio_nonce_field'], 'dr_cardapio_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'semana_inicio' => '_semana_inicio',
        'semana_fim'    => '_semana_fim',
        'dia_semana'    => '_dia_semana',
        'tipo_refeicao' => '_tipo_refeicao',
        'horario'       => '_horario',
        'receita_id'    => '_receita_id',
    ];

    foreach ($fields as $post_key => $meta_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }
}
add_action('save_post', 'dr_save_cardapio_metabox');