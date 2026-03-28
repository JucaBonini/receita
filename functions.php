<?php
/**
 * Funções do tema Descomplicando Receitas
 */

// Definir constantes do tema NO TOPO
define('THEME_PATH', get_template_directory());
define('THEME_URI', get_template_directory_uri());
define('THEME_VERSION', '1.0.1'); // Incrementado para limpar cache

// Setup do tema
function descomplicando_receitas_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links'); // Suporte nativo a feeds RSS
    
    // Tamanhos de Imagem Personalizados (Padrão Google Discover 16:9)
    add_image_size('google-discover', 1200, 675, true);
    
    add_theme_support('html5', array(
        'search-form', 
        'comment-list', 
        'comment-form', 
        'gallery', 
        'caption',
        'style',
        'script'
    ));
    
    // Registrar menus
    register_nav_menus(array(
        'main-menu' => 'Menu Principal',
        'footer-menu' => 'Menu Rodapé'
    ));
    
    // Tamanhos de imagem personalizados
    add_image_size('recipe-card', 300, 200, true);
    add_image_size('blog-featured', 800, 400, true);
    add_image_size('achadinho-thumb', 400, 300, true);
    add_image_size('discover-large', 1200, 0, false); // Tamanho ideal para Google Discover (>=1200px)
}
add_action('after_setup_theme', 'descomplicando_receitas_setup');

// Adicionar preconnect para Google Fonts
function add_google_fonts_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'add_google_fonts_preconnect', 1);

// Carregar estilos e scripts
function descomplicando_receitas_scripts() {
    // Carregar Tailwind CSS Compilado
    wp_enqueue_style('main-css', THEME_URI . '/assets/css/main.min.css', array(), THEME_VERSION, 'all');
    
    // Novas fontes (Public Sans) gerenciadas no header.php
    
    // JS - Carregamento com defer (true no último parâmetro)
    wp_enqueue_script('jquery'); // Garantir que jQuery está carregado
    wp_enqueue_script('main-js', THEME_URI . '/assets/js/main.js', array('jquery'), THEME_VERSION, true);
    wp_enqueue_script('achadinhos-js', THEME_URI . '/assets/js/achadinhos.js', array('jquery'), THEME_VERSION, true);
    
    // Localize script para AJAX (se necessário)
    wp_localize_script('main-js', 'theme_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('theme_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'descomplicando_receitas_scripts');

/**
 * Ajuste de Lazy Loading para Core Web Vitals
 * Remove lazy loading de imagens que marcamos como 'eager' ou 'high' priority
 */
add_filter('wp_get_loading_optimization_attributes', function($attrs, $tag_name, $context) {
    if (isset($attrs['loading']) && $attrs['loading'] === 'eager') {
        unset($attrs['loading']); // Remove o atributo 'loading' para forçar carregamento imediato
    }
    return $attrs;
}, 10, 3);

// Adicionar atributos async/defer ao JS para otimizar o INP
function add_async_defer_attributes($tag, $handle, $src) {
    if (is_admin()) return $tag;
    if (strpos($tag, ' defer') !== false || strpos($tag, ' async') !== false) return $tag;
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'add_async_defer_attributes', 10, 3);

// Adicionar atributos para carregamento assíncrono do CSS (Critical CSS Strategy)
function dr_optimize_css_loading($html, $handle, $href, $media) {
    // Apenas para o CSS principal, para evitar render-blocking
    if ($handle === 'main-css') {
        return '<link rel="preload" href="' . $href . '" as="style" id="main-css-preload" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n" .
               '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>' . "\n";
    }
    return $html;
}
add_filter('style_loader_tag', 'dr_optimize_css_loading', 10, 4);

// Injetar CSS Crítico inline no <head>
function dr_inline_critical_css() {
    $critical_file = THEME_PATH . '/assets/css/critical.css';
    if (file_exists($critical_file)) {
        echo '<style id="critical-css-inline">' . file_get_contents($critical_file) . '</style>' . "\n";
    }
}
add_action('wp_head', 'dr_inline_critical_css', 2);

// Incluir arquivos personalizados (VERIFICAR SE EXISTEM)
$includes_files = array(
    '/includes/categoria-home.php',
    '/includes/destaque-home.php', 
    '/includes/achadinhos-home.php',
    '/includes/receitas-cpt.php',
    '/includes/sidebar-receita.php',
    '/includes/breadcrumb.php'
);

foreach ($includes_files as $file) {
    if (file_exists(THEME_PATH . $file)) {
        require_once THEME_PATH . $file;
    }
}

// Carregar arquivos de CPTs (VERIFICAR SE EXISTEM)
$cpt_files = array(
    '/includes/cpt/cpt-achadinhos.php',
    '/includes/cpt/cpt-artigos.php', 
    '/includes/cpt/cpt-faqs.php',
    '/includes/cpt/cpt-reviews.php',
    '/includes/cpt/custom-taxonomies.php',
    '/includes/cpt/custom-taxonomies.php',
    '/includes/cpt/meta-tags.-cabecalho.php',
	'/includes/cpt/avaliacao.php'
);

foreach ($cpt_files as $file) {
    if (file_exists(THEME_PATH . $file)) {
        require_once THEME_PATH . $file;
    }
}

/**
 * Otimização de Imagens para Google Discover & WebP
 * Garante que as imagens de 1200px tenham compressão ideal
 */
add_filter('wp_editor_set_quality', function($quality, $mime_type) {
    if ('image/webp' === $mime_type) return 85; 
    return 82; 
}, 10, 2);

// Forçar redimensionamento proporcional para o Discover
add_filter('image_size_names_choose', function($sizes) {
    return array_merge($sizes, array(
        'discover-large' => 'Google Discover (1200px)',
    ));
});

// Limpeza do Cabeçalho (SEO & Performance)
function dr_limpeza_head_completa() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'dr_limpeza_head_completa');

// Remover versão do WordPress por segurança
add_filter('the_generator', '__return_empty_string');

// Função para contar visualizações de posts
function set_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '1');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

// Função para obter visualizações
function get_post_views($post_id) {
    $count = get_post_meta($post_id, 'post_views_count', true);
    return $count ? $count : '0';
}

// Adicionar campo de Expertise/Credenciais ao perfil do usuário (E-E-A-T)
function add_user_expertise_field($user) {
    ?>
    <h3><?php _e("Informações de Expertise (E-E-A-T)", "text-domain"); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="expertise"><?php _e("Expertise/Credenciais", "text-domain"); ?></label></th>
            <td>
                <input type="text" name="expertise" id="expertise" value="<?php echo esc_attr(get_the_author_meta('expertise', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Ex: Chef de Cozinha, Sommelier, Nutricionista"); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="certifications"><?php _e("Certificações", "text-domain"); ?></label></th>
            <td>
                <input type="text" name="certifications" id="certifications" value="<?php echo esc_attr(get_the_author_meta('certifications', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Cursos, diplomados ou prêmios relevantes."); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="education"><?php _e("Educação", "text-domain"); ?></label></th>
            <td>
                <input type="text" name="education" id="education" value="<?php echo esc_attr(get_the_author_meta('education', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Bacharelado em Gastronomia, Pós em Nutrição, etc."); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="job_title"><?php _e("Cargo/Título Profissional", "text-domain"); ?></label></th>
            <td>
                <input type="text" name="job_title" id="job_title" value="<?php echo esc_attr(get_the_author_meta('job_title', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Título que aparecerá no Schema (ex: Chef e Escritora)."); ?></span>
            </td>
        </tr>
    </table>
<?php
}
add_action('show_user_profile', 'add_user_expertise_field');
add_action('edit_user_profile', 'add_user_expertise_field');

// Salvar o campo de Expertise/Credenciais
function save_user_expertise_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    if (isset($_POST['expertise'])) {
        update_user_meta($user_id, 'expertise', sanitize_text_field($_POST['expertise']));
    }
    if (isset($_POST['certifications'])) {
        update_user_meta($user_id, 'certifications', sanitize_text_field($_POST['certifications']));
    }
    if (isset($_POST['education'])) {
        update_user_meta($user_id, 'education', sanitize_text_field($_POST['education']));
    }
    if (isset($_POST['job_title'])) {
        update_user_meta($user_id, 'job_title', sanitize_text_field($_POST['job_title']));
    }
}
add_action('personal_options_update', 'save_user_expertise_field');
add_action('edit_user_profile_update', 'save_user_expertise_field');

// Track post views
function track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    set_post_views($post_id);
}
add_action('wp_head', 'track_post_views');

// Linhas no functions.php (aproximadamente 268 e 269)
$rating_value = get_post_meta(get_the_ID(), 'review_rating', true); // Mude 'review_rating' para o nome do seu campo
$review_author = get_post_meta(get_the_ID(), 'review_author', true); // Mude 'review_author' para o nome do seu campo
// AJAX: Buscar detalhes das receitas favoritas (para o dropdown)
function sts_get_favorites_details() {
    $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
    
    if (empty($ids)) {
        wp_send_json_error('No IDs provided');
    }

    $query = new WP_Query(array(
        'post_type' => 'any',
        'post__in' => $ids,
        'posts_per_page' => 10,
        'orderby' => 'post__in'
    ));

    $results = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://via.placeholder.com/80?text=Logo'
            ];
        }
    }
    wp_reset_postdata();
    wp_send_json_success($results);
}
add_action('wp_ajax_get_fav_details', 'sts_get_favorites_details');
add_action('wp_ajax_nopriv_get_fav_details', 'sts_get_favorites_details');
// AJAX: Submissão de Receita pelo Usuário (via Dashboard)
function sts_handle_recipe_submission() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Acesso negado.');
    }

    $title = isset($_POST['recipe_title']) ? sanitize_text_field($_POST['recipe_title']) : '';
    $cat = isset($_POST['recipe_category']) ? intval($_POST['recipe_category']) : 0;
    $ingredients = isset($_POST['recipe_ingredients']) ? sanitize_textarea_field($_POST['recipe_ingredients']) : '';
    $steps = isset($_POST['recipe_steps']) ? sanitize_textarea_field($_POST['recipe_steps']) : '';

    if (empty($title)) {
        wp_send_json_error('O título da receita é obrigatório.');
    }

    // Cria o post no WordPress com status "pending" (esperando aprovação)
    $new_post = array(
        'post_title'    => $title,
        'post_content'  => "<!-- Ingredientes -->\n" . $ingredients . "\n\n<!-- Modo de Preparo -->\n" . $steps,
        'post_status'   => 'pending',
        'post_author'   => get_current_user_id(),
        'post_type'     => 'post',
        'post_category' => array($cat)
    );

    $post_id = wp_insert_post($new_post);

    if ($post_id && !is_wp_error($post_id)) {
        // Upload da Imagem de Destaque
        if (!empty($_FILES['recipe_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $file_type = wp_check_filetype($_FILES['recipe_image']['name']);
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp');

            if (in_array(strtolower($file_type['ext']), $allowed_exts)) {
                $attachment_id = media_handle_upload('recipe_image', $post_id);
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }
        }

        // Salva metadados personalizados (opcional)
        update_post_meta($post_id, '_ingredientes_user', $ingredients);
        update_post_meta($post_id, '_preparo_user', $steps);
        wp_send_json_success('Receita enviada para moderação!');
    } else {
        wp_send_json_error('Não foi possível salvar a receita no momento.');
    }
}
add_action('wp_ajax_sts_submit_recipe', 'sts_handle_recipe_submission');
// AJAX: Ações de Moderação (Aprovar/Excluir) - Apenas Admins
function sts_handle_admin_actions() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permissão insuficiente.');
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $action_type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (!$post_id) {
        wp_send_json_error('ID do post inválido.');
    }

    if ($action_type === 'approve') {
        $updated = wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'publish'
        ));
        if ($updated) wp_send_json_success('Receita aprovada com sucesso!');
    } elseif ($action_type === 'delete') {
        $deleted = wp_delete_post($post_id, true);
        if ($deleted) wp_send_json_success('Receita excluída permanentemente.');
    }

    wp_send_json_error('Falha ao processar ação.');
}
add_action('wp_ajax_sts_admin_action', 'sts_handle_admin_actions');

// AJAX: Login Personalizado
function sts_ajax_login_handler() {
    $info = array();
    $info['user_login'] = isset($_POST['log']) ? sanitize_user($_POST['log']) : '';
    $info['user_password'] = isset($_POST['pwd']) ? $_POST['pwd'] : '';
    $info['remember'] = isset($_POST['rememberme']) ? true : false;
    
    $user_signon = wp_signon($info, is_ssl());
    
    if (is_wp_error($user_signon)) {
        wp_send_json_error('Dados de acesso incorretos. Tente novamente.');
    } else {
        wp_send_json_success(array(
            'message' => 'Login efetuado com sucesso!',
            'redirect' => home_url('/meu-painel')
        ));
    }
}
add_action('wp_ajax_sts_ajax_login', 'sts_ajax_login_handler');
add_action('wp_ajax_nopriv_sts_ajax_login', 'sts_ajax_login_handler');

// AJAX: Cadastro Personalizado
function sts_ajax_register_handler() {
    $name = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';
    $pass = isset($_POST['user_pass']) ? $_POST['user_pass'] : '';
    
    // Validações Básicas
    if (empty($name) || empty($email) || empty($pass)) {
        wp_send_json_error('Por favor, preencha todos os campos obrigatórios.');
    }
    
    if (!is_email($email)) {
        wp_send_json_error('O endereço de e-mail fornecido não é válido.');
    }
    
    if (email_exists($email)) {
        wp_send_json_error('Este e-mail já está cadastrado em nosso site.');
    }
    
    // Tenta criar o usuário (usuário = email para facilitar)
    $user_id = wp_create_user($email, $pass, $email);
    
    if (is_wp_error($user_id)) {
        wp_send_json_error('Erro ao criar conta: ' . $user_id->get_error_message());
    } else {
        // Define o Display Name e Nome
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $name,
            'first_name' => $name
        ));
        
        // Login Automático após o cadastro
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_send_json_success(array(
            'message' => 'Conta criada com sucesso! Bem-vindo(a).',
            'redirect' => home_url('/meu-painel')
        ));
    }
}
add_action('wp_ajax_sts_ajax_register', 'sts_ajax_register_handler');
add_action('wp_ajax_nopriv_sts_ajax_register', 'sts_ajax_register_handler');

// AJAX: Atualizar Perfil Completo (Nova Página)
function sts_handle_full_profile_update() {
    if (!is_user_logged_in()) wp_send_json_error('Acesso negado.');

    $user_id = get_current_user_id();
    $update_data = array('ID' => $user_id);
    
    // 1. Gênero, Nome e Sobrenome
    if (isset($_POST['sts_gender'])) update_user_meta($user_id, 'sts_gender', sanitize_text_field($_POST['sts_gender']));
    if (isset($_POST['first_name'])) update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    if (isset($_POST['last_name'])) update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    if (isset($_POST['description'])) update_user_meta($user_id, 'description', sanitize_textarea_field($_POST['description']));

    // 2. Senha com Requisitos Rigorosos
    if (!empty($_POST['new_password'])) {
        $pass1 = $_POST['new_password'];
        $pass2 = $_POST['confirm_password'];

        if ($pass1 !== $pass2) {
            wp_send_json_error('As senhas não coincidem.');
        }

        // Validação de força (8 caracteres, 1 número, 1 min, 1 maiusc, 1 especial)
        $has_number = preg_match('/[0-9]/', $pass1);
        $has_lower = preg_match('/[a-z]/', $pass1);
        $has_upper = preg_match('/[A-Z]/', $pass1);
        $has_special = preg_match('/[^A-Za-z0-9]/', $pass1);

        if (strlen($pass1) < 8 || !$has_number || !$has_lower || !$has_upper || !$has_special) {
            wp_send_json_error('A senha não atende aos requisitos mínimos de segurança.');
        }

        $update_data['user_pass'] = $pass1;
    }

    // Persistir alterações de senha
    if (isset($update_data['user_pass'])) {
        $result = wp_update_user($update_data);
        if (is_wp_error($result)) {
            wp_send_json_error('Erro ao atualizar senha: ' . $result->get_error_message());
        }
    }

    // 3. Processar Novo Avatar
    if (!empty($_FILES['user_avatar']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $file_type = wp_check_filetype($_FILES['user_avatar']['name']);
        $allowed = array('jpg', 'jpeg'); // Apenas JPG/JPEG conforme modelo

        if (in_array(strtolower($file_type['ext']), $allowed)) {
            // Validar tamanho (inferior a 10MB)
            if ($_FILES['user_avatar']['size'] > (10 * 1024 * 1024)) {
                wp_send_json_error('O arquivo é muito grande. Máximo 10MB.');
            }

            $attachment_id = media_handle_upload('user_avatar', 0);
            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, 'sts_avatar_id', $attachment_id);
            } else {
                wp_send_json_error('Erro no upload: ' . $attachment_id->get_error_message());
            }
        } else {
            wp_send_json_error('Formato inválido. Use apenas JPG ou JPEG.');
        }
    }

    wp_send_json_success(array('message' => 'Perfil completo atualizado com sucesso!'));
}
add_action('wp_ajax_sts_update_full_profile', 'sts_handle_full_profile_update');

// Automação: Criar Páginas Essenciais Automaticamente
function sts_auto_create_pages() {
    $pages = array(
        'meu-perfil' => array(
            'title'    => 'Meu Perfil de Chef',
            'template' => 'template-profile.php'
        ),
        'meu-painel' => array(
            'title'    => 'Meu Painel',
            'template' => 'template-dashboard.php'
        ),
        'entrar' => array(
            'title'    => 'Entrar no Site',
            'template' => 'template-login.php'
        ),
        'cadastrar' => array(
            'title'    => 'Cadastrar na Rede',
            'template' => 'template-register.php'
        )
    );

    foreach ($pages as $slug => $data) {
        $check_page = get_page_by_path($slug);
        if (!$check_page) {
            $page_id = wp_insert_post(array(
                'post_title'    => $data['title'],
                'post_name'     => $slug,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'page',
            ));
            
            if ($page_id && !empty($data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
        }
    }
}
add_action('after_setup_theme', 'sts_auto_create_pages');

// Função auxiliar para retornar URL do avatar customizado ou padrão
function sts_get_user_avatar_url($user_id, $size = 96) {
    $avatar_id = get_user_meta($user_id, 'sts_avatar_id', true);
    if ($avatar_id) {
        return wp_get_attachment_image_url($avatar_id, 'thumbnail');
    }
    return get_avatar_url($user_id, array('size' => $size));
}

// AJAX: Avaliação de Receitas
function sts_ajax_recipe_rating_handler() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $rating  = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    
    if (!$post_id || $rating < 1 || $rating > 5) {
        wp_send_json_error('Avaliação inválida.');
    }

    // Prevenção básica de votos duplicados (sessão ou cookie)
    $cookie_name = 'sts_rated_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error('Você já avaliou esta receita.');
    }

    $total = (int) get_post_meta($post_id, '_rating_total', true);
    $count = (int) get_post_meta($post_id, '_rating_count', true);

    $total += $rating;
    $count += 1;

    update_post_meta($post_id, '_rating_total', $total);
    update_post_meta($post_id, '_rating_count', $count);
    
    $avg = round($total / $count, 1);
    update_post_meta($post_id, '_rating_avg', $avg);

    // Salva cookie por 30 dias (Simplificado para localhost)
    setcookie($cookie_name, '1', time() + (30 * DAY_IN_SECONDS), '/');

    wp_send_json_success(array(
        'average' => $avg,
        'count'   => $count,
        'message' => 'Obrigado por avaliar!'
    ));
}
add_action('wp_ajax_sts_recipe_rating', 'sts_ajax_recipe_rating_handler');
add_action('wp_ajax_nopriv_sts_recipe_rating', 'sts_ajax_recipe_rating_handler');

// Ocultar a barra de administração para todos exceto Administradores (Melhoria de UX para Assinantes)
function sts_hide_admin_bar_for_subscribers() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'sts_hide_admin_bar_for_subscribers');

// Sistema Profissional de Gerenciamento de Anúncios (CPT)
require_once get_template_directory() . '/includes/cpt/anuncios.php';
require_once get_template_directory() . '/includes/cpt/cardapios.php';

/**
 * Injeção Cirúrgica de Anúncios (Estilo Ad Inserter)
 * Injeta anúncios antes/depois de P, H2 e H3 com base no índice configurado.
 */
function sts_surgical_ad_engine($content) {
    if (!is_singular('post') || is_singular('sts_cardapio') || is_admin()) return $content;

    $ads = get_posts(array(
        'post_type'  => 'sts_anuncios',
        'meta_query' => array(
            'relation' => 'AND',
            array('key' => '_sts_ad_position', 'value' => 'in_content'),
            array('key' => '_sts_ad_status',   'value' => 'active')
        ),
        'posts_per_page' => -1
    ));

    if (empty($ads)) return $content;

    // ... (restante da lógica mantida igual)

    // Organizar anúncios por Posição, Tag e Índice
    $map = array();
    foreach ($ads as $ad) {
        $tag   = get_post_meta($ad->ID, '_sts_ad_target_tag', true)   ?: 'p';
        $logic = get_post_meta($ad->ID, '_sts_ad_target_logic', true) ?: 'after';
        $index = get_post_meta($ad->ID, '_sts_ad_target_index', true) ?: '1';
        $code  = get_post_meta($ad->ID, '_sts_ad_code', true);

        $key = "{$tag}_{$index}_{$logic}";
        $map[$key][] = $code;
    }

    // Parser DOM para contar tags
    // Divide o conteúdo mantendo as tags delimitadoras
    $parts = preg_split('/(<\/p>|<h2[^>]*>|<\/h2>|<h3[^>]*>|<\/h3>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    $counters = array('p' => 0, 'h2' => 0, 'h3' => 0);
    $output = '';

    foreach ($parts as $part) {
        $tag_start = '';
        if (preg_match('/^<h(2|3)[^>]*>/i', $part, $matches)) {
            $tag_start = 'h' . $matches[1];
        }

        // Lógica BEFORE (H2, H3)
        if ($tag_start) {
            $counters[$tag_start]++;
            $key_before = "{$tag_start}_{$counters[$tag_start]}_before";
            if (isset($map[$key_before])) {
                foreach ($map[$key_before] as $ad) $output .= '<div class="sts-ad-injected py-4">' . $ad . '</div>';
            }
        }

        $output .= $part;

        // Lógica AFTER (P, H2, H3)
        $tag_end = '';
        if (stripos($part, '</p>') !== false) $tag_end = 'p';
        elseif (stripos($part, '</h2>') !== false) $tag_end = 'h2';
        elseif (stripos($part, '</h3>') !== false) $tag_end = 'h3';

        if ($tag_end) {
            if ($tag_end === 'p') $counters['p']++;
            $key_after = "{$tag_end}_{$counters[$tag_end]}_after";
            if (isset($map[$key_after])) {
                foreach ($map[$key_after] as $ad) $output .= '<div class="sts-ad-injected py-4">' . $ad . '</div>';
            }
        }
    }

    // Injeção de Início e Fim do Artigo
    ob_start();
    sts_display_ad('before_content');
    $before = ob_get_clean();

    ob_start();
    sts_display_ad('after_content');
    $after = ob_get_clean();

    return $before . $output . $after;
}
add_filter('the_content', 'sts_surgical_ad_engine');

/**
 * AJAX: Compilar Lista de Compras do Cardápio
 */
function sts_get_cardapio_ingredients() {
    $ids_str = isset($_GET['ids']) ? sanitize_text_field($_GET['ids']) : '';
    $ids = !empty($ids_str) ? explode(',', $ids_str) : array();
    $compiled = array();

    if (!empty($ids)) {
        foreach ($ids as $id) {
            $ing_raw = get_post_meta($id, '_ingredientes_raw', true);
            if (is_array($ing_raw)) {
                foreach ($ing_raw as $grupo) {
                    if (empty($grupo)) continue;
                    $itens = explode("\n", $grupo);
                    foreach ($itens as $item) {
                        $item = trim($item);
                        if (!empty($item)) $compiled[] = $item;
                    }
                }
            }
        }
    }

    $compiled = array_unique($compiled);
    wp_send_json_success(array_values($compiled));
}
add_action('wp_ajax_get_cardapio_ingredients', 'sts_get_cardapio_ingredients');
add_action('wp_ajax_nopriv_get_cardapio_ingredients', 'sts_get_cardapio_ingredients');
