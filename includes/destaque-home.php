<?php
// Shortcode para Receitas em Destaque
function featured_recipes_shortcode($atts) {
    // Configurações padrão
    $atts = shortcode_atts(array(
        'posts_per_page' => 6,
        'category' => '',
        'orderby' => 'date',
        'order' => 'DESC'
    ), $atts);
    
    // Query para posts marcados como destaque
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'meta_query' => array(
            array(
                'key' => 'destaque',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => $atts['orderby'],
        'order' => $atts['order']
    );
    
    // Se categoria for especificada
    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }
    
    $featured_recipes = new WP_Query($args);
    
    // Inicia o output
    $output = '
    <!-- Receitas em Destaque -->
    <section class="featured-recipes">
        <div class="container">
            <h2 class="section-title">Receitas em Destaque</h2>
            
            <div class="recipes-grid">';
    
    // Loop pelos posts
    if ($featured_recipes->have_posts()) {
        while ($featured_recipes->have_posts()) {
            $featured_recipes->the_post();
            
            // Pega os campos do ACF
            $tempo_preparo = get_field('tempo');
            $rendimento = get_field('rendimento');
            $dificuldade = get_field('dificuldade');
            
            // Fallback para meta antigos se ACF estiver vazio
            if (!$tempo_preparo) {
                $tempo_preparo = get_post_meta(get_the_ID(), 'tempo', true);
            }
            if (!$rendimento) {
                $rendimento = get_post_meta(get_the_ID(), 'porcoes', true);
            }
            if (!$dificuldade) {
                $dificuldade = get_post_meta(get_the_ID(), 'dificuldade', true);
            }
            
            $imagem_destaque = get_the_post_thumbnail_url(get_the_ID(), 'large');
            
            // Imagem padrão se não tiver
            if (!$imagem_destaque) {
                $imagem_destaque = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
            }
            
            // Define a classe CSS baseada na dificuldade
            $dificuldade_class = 'recipe-difficulty ';
            switch ($dificuldade) {
                case 'Fácil':
                    $dificuldade_class .= 'difficulty-easy';
                    break;
                case 'Médio':
                    $dificuldade_class .= 'difficulty-medium';
                    break;
                case 'Difícil':
                    $dificuldade_class .= 'difficulty-hard';
                    break;
                default:
                    $dificuldade_class .= 'difficulty-easy'; // Padrão
            }
            
            $output .= '
                <div class="recipe-card">
                    <div class="recipe-img" style="background-image: url(\'' . esc_url($imagem_destaque) . '\');">
                        ' . ($tempo_preparo ? '<div class="recipe-time">' . esc_html($tempo_preparo) . ' </div>' : '') . '
                    </div>
                    <div class="recipe-content">
                        <h3>' . get_the_title() . '</h3>
                        <div class="recipe-meta">
                            ' . ($rendimento ? '<span>' . esc_html($rendimento) . '</span>' : '') . '
                            ' . ($dificuldade ? '<span class="' . esc_attr($dificuldade_class) . '">' . esc_html($dificuldade) . '</span>' : '') . '
                        </div>
                        <a href="' . get_permalink() . '" class="btn">Ver Receita</a>
                    </div>
                </div>';
        }
        wp_reset_postdata();
    } else {
        // Fallback se não houver posts em destaque
        $output .= '
                <div class="no-recipes">
                    <p>Nenhuma receita em destaque no momento.</p>
                </div>';
    }
    
    $output .= '
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="' . get_permalink(get_option('page_for_posts')) . '" class="btn btn-secondary">Ver Todas as Receitas</a>
            </div>
        </div>
    </section>';
    
    return $output;
}
add_shortcode('receitas_destaque', 'featured_recipes_shortcode');

// Adicionar campos personalizados para receitas
function add_recipe_meta_fields() {
    add_meta_box(
        'recipe_details',
        'Detalhes da Receita',
        'recipe_meta_fields_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_recipe_meta_fields');

function recipe_meta_fields_callback($post) {
    wp_nonce_field('recipe_meta_nonce', 'recipe_meta_nonce');
    
    $destaque = get_post_meta($post->ID, 'destaque', true);
    ?>
    
    <div style="margin: 15px 0;">
        <div>
            <label for="destaque" style="display: block; margin-bottom: 5px;">
                <input type="checkbox" id="destaque" name="destaque" value="1" <?php checked($destaque, '1'); ?> />
                Marcar como destaque
            </label>
            <p class="description">
                <small>Esta opção controla apenas o campo "destaque". Os outros campos (tempo, rendimento, dificuldade) agora são gerenciados pelo ACF (Advanced Custom Fields).</small>
            </p>
        </div>
    </div>
    <?php
}

function save_recipe_meta_fields($post_id) {
    if (!isset($_POST['recipe_meta_nonce']) || !wp_verify_nonce($_POST['recipe_meta_nonce'], 'recipe_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Apenas o campo 'destaque' permanece
    if (isset($_POST['destaque'])) {
        update_post_meta($post_id, 'destaque', sanitize_text_field($_POST['destaque']));
    } else {
        delete_post_meta($post_id, 'destaque');
    }
}
add_action('save_post', 'save_recipe_meta_fields');
?>