<?php
// Shortcode para exibir categorias
function display_categories_shortcode($atts) {
    // Configurações padrão
    $atts = shortcode_atts(array(
        'number' => 4,
        'orderby' => 'count',
        'order' => 'DESC',
        'parent' => 0
    ), $atts);
    
    // Pega as categorias
    $categories = get_categories(array(
        'number' => $atts['number'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'parent' => $atts['parent'],
        'hide_empty' => true
    ));
    
    // Array com imagens padrão para categorias (Unsplash)
    $default_images = array(
        'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
        'https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
        'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
        'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
    );
    
    // Inicia o output
    $output = '
    <!-- Categorias -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Categorias Populares</h2>
            
            <div class="categories-grid">';
    
    // Loop pelas categorias
    $image_index = 0;
    foreach ($categories as $category) {
        // Pega a imagem da categoria (se existir)
        $category_image = get_term_meta($category->term_id, 'category_image', true);
        
        // Se não tiver imagem customizada, usa uma padrão
        if (empty($category_image)) {
            $category_image = $default_images[$image_index % count($default_images)];
            $image_index++;
        }
        
        // Descrição da categoria
        $description = $category->description;
        if (empty($description)) {
            $description = 'Descubra nossas melhores receitas em ' . $category->name;
        }
        
        $output .= '
                <div class="category-card">
                    <div class="category-img" style="background-image: url(\'' . esc_url($category_image) . '\');"></div>
                    <div class="category-content">
                        <h3>' . esc_html($category->name) . '</h3>
                       
                    </div>
                </div>';
    }
    
    $output .= '
            </div>
        </div>
    </section>';
    
    return $output;
}
add_shortcode('categorias_populares', 'display_categories_shortcode');

// Função para adicionar campo de imagem personalizada às categorias
function add_category_image_field() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#addtag').on('click', '#submit', function() {
            setTimeout(function() {
                $('.form-field:has(.category-image-url)').remove();
            }, 100);
        });
    });
    </script>
    <?php
}
add_action('category_add_form_fields', 'add_category_image_field');

// Adiciona campo de imagem na edição da categoria
function edit_category_image_field($term) {
    $image_url = get_term_meta($term->term_id, 'category_image', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="category_image">Imagem da Categoria</label>
        </th>
        <td>
            <input type="url" name="category_image" id="category-image-url" value="<?php echo esc_url($image_url); ?>" />
            <p class="description">URL da imagem para esta categoria</p>
        </td>
    </tr>
    <?php
}
add_action('category_edit_form_fields', 'edit_category_image_field', 10, 2);

// Salva o campo de imagem da categoria
function save_category_image($term_id) {
    if (isset($_POST['category_image'])) {
        update_term_meta($term_id, 'category_image', esc_url_raw($_POST['category_image']));
    }
}
add_action('created_category', 'save_category_image');
add_action('edited_category', 'save_category_image');
?>