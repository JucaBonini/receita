<?php
// Função para gerar breadcrumb dinâmico com Schema JSON-LD
function custom_breadcrumb() {
    global $post;
    
    $breadcrumb_html = '<div class="breadcrumb container flex items-center gap-2 text-xs font-bold text-slate-500 mb-6">';
    $items = array();
    
    // Início
    $items[] = array('name' => 'Início', 'url' => home_url());
    $breadcrumb_html .= '<a href="' . home_url() . '" class="hover:text-primary transition-colors">Início</a>';
    
    if (is_single()) {
        $breadcrumb_html .= ' <span class="material-symbols-outlined text-[10px] opacity-30">chevron_right</span> ';
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            $items[] = array('name' => $category->name, 'url' => get_category_link($category->term_id));
            $breadcrumb_html .= '<a href="' . get_category_link($category->term_id) . '" class="hover:text-primary transition-colors">' . $category->name . '</a>';
            $breadcrumb_html .= ' <span class="material-symbols-outlined text-[10px] opacity-30">chevron_right</span> ';
        }
        $breadcrumb_html .= '<span class="text-slate-400 font-medium line-clamp-1">' . get_the_title() . '</span>';
        $items[] = array('name' => get_the_title(), 'url' => get_permalink());
    }
    elseif (is_category()) {
        $breadcrumb_html .= ' <span class="material-symbols-outlined text-[10px] opacity-30">chevron_right</span> ';
        $category = get_queried_object();
        $breadcrumb_html .= '<span class="text-slate-400 font-medium">' . $category->name . '</span>';
        $items[] = array('name' => $category->name, 'url' => get_category_link($category->term_id));
    }
    elseif (is_page()) {
        $breadcrumb_html .= ' <span class="material-symbols-outlined text-[10px] opacity-30">chevron_right</span> ';
        $breadcrumb_html .= '<span class="text-slate-400 font-medium">' . get_the_title() . '</span>';
        $items[] = array('name' => get_the_title(), 'url' => get_permalink());
    }
    
    $breadcrumb_html .= '</div>';
    
    // Injetar JSON-LD
    dr_add_breadcrumb_schema($items);
    
    return $breadcrumb_html;
}

// Função para injetar Schema de Breadcrumb no <head>
function dr_add_breadcrumb_schema($items) {
    if (empty($items)) return;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array()
    );
    
    $i = 1;
    foreach ($items as $item) {
        $list_item = array(
            '@type' => 'ListItem',
            'position' => $i,
            'name' => $item['name']
        );
        if (!empty($item['url'])) {
            $list_item['item'] = $item['url'];
        }
        $schema['itemListElement'][] = $list_item;
        $i++;
    }
    
    echo '<script type="application/ld+json" id="breadcrumb-schema">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

// Shortcode
function breadcrumb_shortcode() {
    return custom_breadcrumb();
}
add_shortcode('breadcrumb', 'breadcrumb_shortcode');
?>