<footer class="bg-slate-900 text-slate-300 py-16 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            <!-- Logo & Bio -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <div class="bg-primary text-white p-1 rounded-lg">
                        <span class="material-symbols-outlined block text-xl">restaurant_menu</span>
                    </div>
                    <span class="text-xl font-black text-white"><?php bloginfo('name'); ?></span>
                </div>
                <p class="max-w-md text-slate-400 leading-relaxed">
                    <?php bloginfo('description'); ?>. Transformamos ingredientes comuns em refeições extraordinárias para o seu dia a dia.
                </p>
                
                <!-- Social Links Dinâmicos (ou estáticos do tema) -->
                <div class="flex gap-4 mt-8">
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="#">
                        <span class="material-symbols-outlined">public</span>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-all" href="#">
                        <span class="material-symbols-outlined">share</span>
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

<?php wp_footer(); ?>
</body>
</html>