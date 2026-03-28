<?php
// Adicione este código no functions.php do seu tema

// Registrar Custom Post Type "Achadinhos"
function registrar_cpt_achadinhos() {
    $labels = array(
        'name' => 'Achadinhos',
        'singular_name' => 'Achadinho',
        'menu_name' => 'Achadinhos',
        'name_admin_bar' => 'Achadinho',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Achadinho',
        'new_item' => 'Novo Achadinho',
        'edit_item' => 'Editar Achadinho',
        'view_item' => 'Ver Achadinho',
        'all_items' => 'Todos os Achadinhos',
        'search_items' => 'Buscar Achadinhos',
        'not_found' => 'Nenhum achadinho encontrado.',
        'not_found_in_trash' => 'Nenhum achadinho na lixeira.'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'achadinhos'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-cart',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );

    register_post_type('achadinhos', $args);
}
add_action('init', 'registrar_cpt_achadinhos');

// Registrar Taxonomia "Categoria de Achadinhos"
function registrar_taxonomia_categoria_achadinhos() {
    $labels = array(
        'name' => 'Categorias de Achadinhos',
        'singular_name' => 'Categoria',
        'search_items' => 'Buscar Categorias',
        'all_items' => 'Todas as Categorias',
        'parent_item' => 'Categoria Pai',
        'parent_item_colon' => 'Categoria Pai:',
        'edit_item' => 'Editar Categoria',
        'update_item' => 'Atualizar Categoria',
        'add_new_item' => 'Adicionar Nova Categoria',
        'new_item_name' => 'Novo Nome da Categoria',
        'menu_name' => 'Categorias'
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'categoria-achadinhos'),
        'show_in_rest' => true,
    );

    register_taxonomy('categoria_achadinhos', array('achadinhos'), $args);
}
add_action('init', 'registrar_taxonomia_categoria_achadinhos');

// Campos personalizados para Achadinhos
function adicionar_campos_personalizados_achadinhos() {
    add_meta_box(
        'achadinhos_metabox',
        'Informações do Produto',
        'renderizar_metabox_achadinhos',
        'achadinhos',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_campos_personalizados_achadinhos');

function renderizar_metabox_achadinhos($post) {
    wp_nonce_field('salvar_achadinhos_meta', 'achadinhos_nonce');
    
    $preco_atual = get_post_meta($post->ID, '_preco_atual', true);
    $preco_original = get_post_meta($post->ID, '_preco_original', true);
    $link_afiliado = get_post_meta($post->ID, '_link_afiliado', true);
    $avaliacao = get_post_meta($post->ID, '_avaliacao', true);
    $numero_avaliacoes = get_post_meta($post->ID, '_numero_avaliacoes', true);
    $badge = get_post_meta($post->ID, '_badge', true);
    $frete_gratis = get_post_meta($post->ID, '_frete_gratis', true);
    
    ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <p>
                <label for="preco_atual"><strong>Preço Atual:</strong></label>
                <input type="text" id="preco_atual" name="preco_atual" value="<?php echo esc_attr($preco_atual); ?>" style="width: 100%;">
            </p>
            <p>
                <label for="preco_original"><strong>Preço Original:</strong></label>
                <input type="text" id="preco_original" name="preco_original" value="<?php echo esc_attr($preco_original); ?>" style="width: 100%;">
            </p>
            <p>
                <label for="link_afiliado"><strong>Link de Afiliado:</strong></label>
                <input type="url" id="link_afiliado" name="link_afiliado" value="<?php echo esc_url($link_afiliado); ?>" style="width: 100%;">
            </p>
        </div>
        <div>
            <p>
                <label for="avaliacao"><strong>Avaliação (0-5):</strong></label>
                <input type="number" id="avaliacao" name="avaliacao" value="<?php echo esc_attr($avaliacao); ?>" min="0" max="5" step="0.1" style="width: 100%;">
            </p>
            <p>
                <label for="numero_avaliacoes"><strong>Número de Avaliações:</strong></label>
                <input type="number" id="numero_avaliacoes" name="numero_avaliacoes" value="<?php echo esc_attr($numero_avaliacoes); ?>" style="width: 100%;">
            </p>
            <p>
                <label for="badge"><strong>Badge:</strong></label>
                <select id="badge" name="badge" style="width: 100%;">
                    <option value="">Nenhum</option>
                    <option value="Mais Vendido" <?php selected($badge, 'Mais Vendido'); ?>>Mais Vendido</option>
                    <option value="Novidade" <?php selected($badge, 'Novidade'); ?>>Novidade</option>
                    <option value="Oferta Especial" <?php selected($badge, 'Oferta Especial'); ?>>Oferta Especial</option>
                    <option value="Premium" <?php selected($badge, 'Premium'); ?>>Premium</option>
                </select>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="frete_gratis" value="1" <?php checked($frete_gratis, '1'); ?>>
                    <strong>Frete Grátis</strong>
                </label>
            </p>
        </div>
    </div>
    <?php
}

function salvar_metabox_achadinhos($post_id) {
    if (!isset($_POST['achadinhos_nonce']) || !wp_verify_nonce($_POST['achadinhos_nonce'], 'salvar_achadinhos_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $campos = array(
        'preco_atual',
        'preco_original',
        'link_afiliado',
        'avaliacao',
        'numero_avaliacoes',
        'badge'
    );
    
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            update_post_meta($post_id, '_' . $campo, sanitize_text_field($_POST[$campo]));
        }
    }
    
    $frete_gratis = isset($_POST['frete_gratis']) ? '1' : '0';
    update_post_meta($post_id, '_frete_gratis', $frete_gratis);
}
add_action('save_post', 'salvar_metabox_achadinhos');

// Shortcode para exibir Achadinhos
function shortcode_achadinhos($atts) {
    $atts = shortcode_atts(array(
        'categoria' => '',
        'limite' => 6,
        'mostrar_filtros' => 'true'
    ), $atts);
    
    ob_start();
    exibir_secao_achadinhos($atts);
    return ob_get_clean();
}
add_shortcode('achadinhos', 'shortcode_achadinhos');

// functions.php - Função para exibir a seção
function exibir_secao_achadinhos($args = array()) {
    $defaults = array(
        'categoria' => '',
        'limite' => 6,
        'mostrar_filtros' => true
    );
    $args = wp_parse_args($args, $defaults);
    
    $query_args = array(
        'post_type' => 'achadinhos',
        'posts_per_page' => $args['limite'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    if (!empty($args['categoria'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_achadinhos',
                'field' => 'slug',
                'terms' => $args['categoria']
            )
        );
    }
    
    $achadinhos_query = new WP_Query($query_args);
    
    if (!$achadinhos_query->have_posts()) {
        return;
    }
    
    // Obter categorias para os filtros
    $categorias = get_terms(array(
        'taxonomy' => 'categoria_achadinhos',
        'hide_empty' => true,
    ));
    ?>
    
    <!-- Seção Achadinhos da Cozinha -->
    <section class="kitchen-finds">
        <div class="container">
            <h2 class="section-title">🛍️ Achadinhos da Cozinha</h2>
            <p class="section-subtitle">Produtos, utensílios e ingredientes especiais que vão transformar sua experiência na cozinha</p>
            
            <?php if ($args['mostrar_filtros'] && !is_wp_error($categorias) && !empty($categorias)): ?>
            <div class="finds-filter">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <?php foreach ($categorias as $categoria): ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr($categoria->slug); ?>">
                        <?php echo esc_html($categoria->name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="finds-grid">
                <?php while ($achadinhos_query->have_posts()): $achadinhos_query->the_post(); 
                    $categorias_post = wp_get_post_terms(get_the_ID(), 'categoria_achadinhos');
                    $categoria_slug = !empty($categorias_post) ? $categorias_post[0]->slug : '';
                    
                    $preco_atual = get_post_meta(get_the_ID(), '_preco_atual', true);
                    $preco_original = get_post_meta(get_the_ID(), '_preco_original', true);
                    $link_afiliado = get_post_meta(get_the_ID(), '_link_afiliado', true);
                    $avaliacao = get_post_meta(get_the_ID(), '_avaliacao', true);
                    $numero_avaliacoes = get_post_meta(get_the_ID(), '_numero_avaliacoes', true);
                    $badge = get_post_meta(get_the_ID(), '_badge', true);
                    $frete_gratis = get_post_meta(get_the_ID(), '_frete_gratis', true);
                    
                    // Calcular desconto
                    $desconto = '';
                    if ($preco_original && $preco_atual && $preco_original > $preco_atual) {
                        $percentual = (($preco_original - $preco_atual) / $preco_original) * 100;
                        $desconto = '-' . round($percentual) . '%';
                    }
                ?>
                
                <div class="find-card" data-category="<?php echo esc_attr($categoria_slug); ?>">
                    <?php if ($badge): ?>
                        <div class="find-badge"><?php echo esc_html($badge); ?></div>
                    <?php endif; ?>
                    
                    <div class="find-image">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('medium', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
                        <?php else: ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-achadinho.jpg'); ?>" alt="<?php the_title(); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    
                    <div class="find-content">
                        <?php if (!empty($categorias_post)): ?>
                            <span class="find-category"><?php echo esc_html($categorias_post[0]->name); ?></span>
                        <?php endif; ?>
                        
                        <h3 class="find-title"><?php the_title(); ?></h3>
                        
                        <p class="find-description"><?php echo get_the_excerpt(); ?></p>
                        
                        <?php if ($avaliacao): ?>
                        <div class="find-rating">
                            <?php echo gerar_estrelas_avaliacao($avaliacao); ?>
                            <?php if ($numero_avaliacoes): ?>
                                <span class="rating-count">(<?php echo esc_html($numero_avaliacoes); ?> avaliações)</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="find-price">
                            <?php if ($preco_atual): ?>
                                <span class="current-price"><?php echo esc_html($preco_atual); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($preco_original && $preco_original != $preco_atual): ?>
                                <span class="original-price"><?php echo esc_html($preco_original); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($desconto): ?>
                                <span class="discount"><?php echo esc_html($desconto); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($frete_gratis): ?>
                                <span class="shipping">Frete grátis</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="find-actions">
                            <?php if ($link_afiliado): ?>
                                <a href="<?php echo esc_url($link_afiliado); ?>" class="btn" target="_blank" rel="nofollow sponsored">Ver Oferta</a>
                            <?php else: ?>
                                <a href="<?php the_permalink(); ?>" class="btn">Ver Detalhes</a>
                            <?php endif; ?>
                            
                            <button class="wishlist-btn" title="Adicionar à lista de desejos" data-post-id="<?php the_ID(); ?>">❤️</button>
                        </div>
                    </div>
                </div>
                
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            
            <div class="finds-cta">
                <p>💡 <strong>Dica:</strong> Estes produtos são selecionados para melhorar seu dia a dia na cozinha!</p>
                <a href="<?php echo get_post_type_archive_link('achadinhos'); ?>" class="btn btn-secondary">Ver Todos os Achadinhos</a>
            </div>
        </div>
    </section>
    
    <?php
}

// Função auxiliar para gerar estrelas de avaliação
function gerar_estrelas_avaliacao($nota) {
    $estrelas_cheias = floor($nota);
    $estrela_meia = ($nota - $estrelas_cheias) >= 0.5;
    $estrelas_vazias = 5 - $estrelas_cheias - ($estrela_meia ? 1 : 0);
    
    $html = '';
    
    // Estrelas cheias
    for ($i = 0; $i < $estrelas_cheias; $i++) {
        $html .= '⭐';
    }
    
    // Meia estrela
    if ($estrela_meia) {
        $html .= '⭐';
    }
    
    // Estrelas vazias
    for ($i = 0; $i < $estrelas_vazias; $i++) {
        $html .= '☆';
    }
    
    return $html;
}
?>