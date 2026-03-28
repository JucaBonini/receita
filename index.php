<?php

/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Descomplicando_Receitas
 */

get_header();
?>
<!-- Hero Section do Blog -->
<section class="blog-hero">
    <div class="container">
        <h1><?php echo get_the_title(); ?></h1>
        <p><?php echo get_the_excerpt(); ?></p>
        <a href="#posts" class="btn">Explorar Artigos</a>
    </div>
</section>

<!-- Adsense -->
<div class="container ads-container">
    <div class="ad-banner">
        <?php echo get_option('adsense_header'); ?>
    </div>
</div>

<!-- Blog Content -->
<main class="container blog-content" id="posts">
    <!-- Posts -->
    <div class="posts-container">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $blog_args = array(
            'post_type' => 'post',
            'posts_per_page' => 6,
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key' => 'featured_post',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );

        $blog_query = new WP_Query($blog_args);

        if ($blog_query->have_posts()) :
            $first_post = true;
            while ($blog_query->have_posts()) : $blog_query->the_post();

                if ($first_post) :
                    $first_post = false;
        ?>
                    <!-- Featured Post -->
                    <div class="featured-post">
                        <article class="post-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-image">
                                    <?php the_post_thumbnail('large'); ?>
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories) :
                                        $category = $categories[0];
                                    ?>
                                        <span class="post-category"><?php echo $category->name; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="post-content">
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date('d \d\e F, Y'); ?></span>
                                    <span class="post-author">Por <?php the_author(); ?></span>
                                </div>
                                <h2 class="post-title"><?php the_title(); ?></h2>
                                <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Ler Artigo Completo
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Regular Posts -->
                    <div class="posts-grid">
                    <?php else : ?>
                        <article class="post-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories) :
                                        $category = $categories[0];
                                    ?>
                                        <span class="post-category"><?php echo $category->name; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="post-content">
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date('d \d\e F, Y'); ?></span>
                                    <span class="post-author">Por <?php the_author(); ?></span>
                                </div>
                                <h2 class="post-title"><?php the_title(); ?></h2>
                                <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Ler Artigo
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endif; ?>
                <?php endwhile; ?>
                    </div>

                    <!-- Adsense -->
                    <div class="ads-container">
                        <div class="ad-banner">
                            <?php echo get_option('adsense_middle'); ?>
                        </div>
                    </div>

                    <!-- More Posts -->
                    <?php
                    // Query para posts não destacados
                    $regular_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 6,
                        'paged' => $paged,
                        'meta_query' => array(
                            array(
                                'key' => 'featured_post',
                                'compare' => 'NOT EXISTS'
                            )
                        )
                    );

                    $regular_query = new WP_Query($regular_args);

                    if ($regular_query->have_posts()) :
                    ?>
                        <div class="posts-grid">
                            <?php while ($regular_query->have_posts()) : $regular_query->the_post(); ?>
                                <article class="post-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-image">
                                            <?php the_post_thumbnail('medium'); ?>
                                            <?php
                                            $categories = get_the_category();
                                            if ($categories) :
                                                $category = $categories[0];
                                            ?>
                                                <span class="post-category"><?php echo $category->name; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span class="post-date"><?php echo get_the_date('d \d\e F, Y'); ?></span>
                                            <span class="post-author">Por <?php the_author(); ?></span>
                                        </div>
                                        <h2 class="post-title"><?php the_title(); ?></h2>
                                        <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="read-more">
                                            Ler Artigo
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
                                            </svg>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <div class="pagination">
                        <?php
                        echo paginate_links(array(
                            'total' => $blog_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '‹',
                            'next_text' => '›',
                            'type' => 'plain'
                        ));
                        ?>
                    </div>

                <?php
                wp_reset_postdata();
            else :
                echo '<p>Nenhum artigo encontrado.</p>';
            endif;
                ?>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- About Widget -->
        <div class="sidebar-widget about-widget">
            <?php
            $author_id = 1; // ID do autor principal
            $author_avatar = get_avatar_url($author_id, array('size' => 100));
            ?>
            <img src="<?php echo $author_avatar; ?>" alt="<?php the_author_meta('display_name', $author_id); ?>" class="about-avatar">
            <h3 class="widget-title">Sobre o Blog</h3>
            <p class="about-text"><?php echo get_the_excerpt(); ?></p>
            <a href="<?php echo get_permalink(get_page_by_path('sobre')); ?>" class="btn">Conheça Nossa História</a>
        </div>

        <!-- Adsense -->
        <div class="ad-sidebar">
            <?php echo get_option('adsense_sidebar_1'); ?>
        </div>

        <!-- Categories Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Categorias</h3>
            <ul class="categories-list">
                <?php
                $categories = get_categories(array(
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));

                foreach ($categories as $category) : ?>
                    <li>
                        <a href="<?php echo get_category_link($category->term_id); ?>">
                            <?php echo $category->name; ?>
                            <span class="category-count">(<?php echo $category->count; ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Popular Posts Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Artigos Populares</h3>
            <div class="popular-posts">
                <?php
                $popular_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'meta_key' => 'post_views_count',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC'
                );

                $popular_posts = new WP_Query($popular_args);

                if ($popular_posts->have_posts()) :
                    while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="popular-post">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                            <?php else : ?>
                                <div style="width: 80px; height: 60px; background: var(--light); display: flex; align-items: center; justify-content: center;">
                                    <span>📰</span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4><?php the_title(); ?></h4>
                                <span class="post-date"><?php echo get_the_date('d \d\e M, Y'); ?></span>
                            </div>
                        </a>
                <?php endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>

        <!-- Newsletter Widget -->
     <?php include(get_template_directory() . '/template-parts/whatsapp.php'); ?>

        <!-- Tags Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Tags Populares</h3>
            <div class="tags-list">
                <?php
                $tags = get_tags(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 8
                ));

                foreach ($tags as $tag) : ?>
                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag"><?php echo $tag->name; ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Adsense -->
        <div class="ad-sidebar">
            <?php echo get_option('adsense_sidebar_2'); ?>
        </div>
    </aside>
</main>
<?php
get_footer();
