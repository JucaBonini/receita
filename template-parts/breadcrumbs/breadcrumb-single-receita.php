<!-- Breadcrumb -->
<div class="breadcrumb container">
    <a href="<?php echo esc_url(home_url()); ?>">Início</a> <span>></span>
    <?php
    $categories = get_the_category();
    if (!empty($categories)) {
        $category = $categories[0];
        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a> <span>></span>';
    }
    ?>
    <span><?php the_title(); ?></span>
</div>