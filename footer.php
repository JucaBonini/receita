<footer class="bg-slate-900 text-slate-300 py-16 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            <!-- Logo & Bio -->
            <div class="col-span-1 md:col-span-2">
            <div class="mb-6">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                    <?php 
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id , 'full');
                    if ($logo) : ?>
                        <img src="<?php echo esc_url($logo[0]); ?>" class="h-10 md:h-12 w-auto object-contain brightness-0 invert opacity-90" alt="<?php bloginfo('name'); ?>">
                    <?php else : ?>
                        <span class="text-xl font-black text-white"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
            </div>
                <p class="max-w-md text-slate-400 leading-relaxed">
                    <?php bloginfo('description'); ?>. Transformamos ingredientes comuns em refeições extraordinárias para o seu dia a dia.
                </p>
                
                <!-- Social Links Dinâmicos -->
                <div class="flex gap-4 mt-8">
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="https://www.instagram.com/descomplicandoreceitasofic" target="_blank" rel="noopener">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="https://www.facebook.com/descomplicandoreceitasofic" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="https://www.youtube.com/@descomplicandoreceitas" target="_blank" rel="noopener">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="https://www.tiktok.com/@desc_receitas_ofic" target="_blank" rel="noopener">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- Navegação 1 -->
            <div>
                <h3 class="text-white font-bold mb-6">Navegação</h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container' => false,
                    'menu_class' => 'space-y-4 text-sm',
                    'fallback_cb' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'add_li_class'  => 'hover:text-primary transition-colors'
                ));
                ?>
            </div>

            <!-- Navegação 2 (Categorias) -->
            <div>
                <h3 class="text-white font-bold mb-6">Categorias Principais</h3>
                <ul class="space-y-4 text-sm">
                    <?php
                    $categories = get_categories(array('number' => 4, 'orderby' => 'count', 'order' => 'DESC'));
                    foreach($categories as $category) {
                        echo '<li><a class="hover:text-primary transition-colors" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 mt-8">
            <!-- Breadcrumb Simples (Opcional - Pode usar o do plugin de SEO se preferir) -->
            <nav aria-label="Breadcrumb" class="flex text-xs text-slate-500 gap-2 mb-8 uppercase tracking-widest">
                <a class="hover:text-slate-300" href="<?php echo home_url(); ?>">Home</a>
                <span>/</span>
                <span class="text-slate-400"><?php if (is_single()) the_title(); else echo "Menu"; ?></span>
            </nav>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.</p>
                <div class="flex gap-6 text-sm">
                    <a class="hover:text-white transition-colors" href="#">Privacidade</a>
                    <a class="hover:text-white transition-colors" href="#">Cookies</a>
                </div>
            </div>
        </div>
        
    </div>
</footer>


<?php get_template_part('template-parts/lgpd-banner'); ?>
<?php get_template_part('template-parts/whatsapp-banner'); ?>
<?php get_template_part('template-parts/pwa-install-banner'); ?>

<?php wp_footer(); ?>
</body>
</html>