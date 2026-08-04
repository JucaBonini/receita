<aside class="sidebar">
    <!-- Widget Categorias -->
    <div class="sidebar-widget">
        <h3 class="widget-title">Categorias</h3>
        <ul class="categories-list">
            <?php
            $categories = get_categories(array(
                'hide_empty' => true,
                'exclude' => $category_id
            ));

            foreach ($categories as $cat) : ?>
                <li>
                    <a href="<?php echo get_category_link($cat->term_id); ?>">
                        <?php echo esc_html($cat->name); ?>
                        <span class="count">(<?php echo $cat->count; ?>)</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Widget Receitas Populares -->
    <div class="sidebar-widget">
        <h3 class="widget-title">Receitas Populares</h3>
        <div class="popular-recipes">
            <?php
            $popular_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'category__not_in' => array($category_id)
            ));

            if ($popular_posts->have_posts()) :
                while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                    <article class="popular-recipe">
                        <a href="<?php the_permalink(); ?>" class="popular-recipe-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="popular-recipe-img">
                                    <?php the_post_thumbnail('thumbnail', array('loading' => 'lazy')); ?>
                                </div>
                            <?php endif; ?>
                            <div class="popular-recipe-content">
                                <h4><?php the_title(); ?></h4>
                                <span class="popular-recipe-meta">
                                    ⏱️ <?php echo get_post_meta(get_the_ID(), 'tempo_preparo', true) ?: '--'; ?> min
                                </span>
                            </div>
                        </a>
                    </article>
            <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <!-- Widget Ads -->
    <div class="sidebar-widget">
        <div class="ad-sidebar">
            <span>Anúncio</span>
            <p>Espaço para publicidade</p>
        </div>
    </div>
</aside>