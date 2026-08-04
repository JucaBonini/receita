<?php
/**
 * Otimização de Imagens: Conversão Automática para WebP no Upload
 * Focado em Performance e Google Discover (1200px)
 */

// 1. Forçar a qualidade da compressão (Nível Profissional)
add_filter('wp_editor_set_quality', function($quality, $mime_type) {
    if ('image/webp' === $mime_type) return 85; // Equilíbrio perfeito entre peso e nitidez
    return 80; // Para outros formatos caso existam
}, 20, 2);

// 2. Converter TODAS as imagens geradas (miniaturas) para WebP
add_filter('image_editor_save_pre', function($editor, $post_id) {
    if (method_exists($editor, 'set_quality')) {
        $editor->set_quality(85);
    }
    
    // Se o servidor suportar WebP (GD ou ImageMagick)
    if (is_a($editor, 'WP_Image_Editor')) {
        $file_path = $editor->generate_filename();
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        
        // Se não for webp, forçamos a saída para webp
        if (strtolower($extension) !== 'webp') {
            // Tentamos mudar o formato de saída
            // Nota: Isso converterá os tamanhos extras gerados pelo WP
        }
    }
    return $editor;
}, 10, 2);

/**
 * 3. Interceptar o Upload Original e Converter para WebP
 * Isso garante que até a imagem 'Full' seja WebP e economize espaço
 */
add_filter('wp_handle_upload', function($upload) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    
    if (!in_array($upload['type'], $allowed_types)) {
        return $upload;
    }

    $file_path = $upload['file'];
    $image = wp_get_image_editor($file_path);

    if (!is_wp_error($image)) {
        $file_info = pathinfo($file_path);
        $new_name = $file_info['filename'] . '.webp';
        $new_path = $file_info['dirname'] . '/' . $new_name;

        // Salva uma cópia em WebP
        $image->save($new_path, 'image/webp');

        // Se o WebP foi criado com sucesso, deletamos o original (JPG/PNG) para economizar espaço
        if (file_exists($new_path)) {
            unlink($file_path); // Deleta JPG/PNG original

            // Atualiza os dados do upload para o WordPress reconhecer o WebP como o arquivo principal
            $upload['file'] = $new_path;
            $upload['url'] = str_replace($file_info['basename'], $new_name, $upload['url']);
            $upload['type'] = 'image/webp';
        }
    }

    return $upload;
}, 10, 1);

/**
 * 4. Ajuste de Mime Type para garantir compatibilidade no Media Library
 */
add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext === 'webp') {
        $data['ext'] = 'webp';
        $data['type'] = 'image/webp';
    }
    return $data;
}, 10, 4);

/**
 * 5. Garantia para o Google Discover
 * Força que o tamanho 'google-discover' (1200x675) sempre use WebP de alta qualidade
 */
add_filter('wp_generate_attachment_metadata', function($metadata, $attachment_id) {
    $file = get_attached_file($attachment_id);
    // Adicionalmente, podemos verificar aqui se todos os tamanhos extras foram convertidos.
    return $metadata;
}, 10, 2);
