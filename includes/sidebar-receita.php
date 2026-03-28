<?php
// Registrar sidebars para anúncios
function registrar_sidebars_receita() {
    register_sidebar(array(
        'name' => 'Receita - Topo',
        'id' => 'receita-top-ad',
        'description' => 'Área para anúncios no topo da receita',
        'before_widget' => '<div class="ad-banner">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'Receita - Rodapé',
        'id' => 'receita-bottom-ad',
        'description' => 'Área para anúncios no rodapé da receita',
        'before_widget' => '<div class="ad-banner">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'Receita - Sidebar Pequeno',
        'id' => 'receita-sidebar-ad',
        'description' => 'Área para anúncios pequenos na sidebar',
        'before_widget' => '<div class="ad-sidebar">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'Receita - Sidebar Grande',
        'id' => 'receita-sidebar-large-ad',
        'description' => 'Área para anúncios grandes na sidebar',
        'before_widget' => '<div class="ad-sidebar large">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'registrar_sidebars_receita');
?>