<?php
/**
 * The template for displaying archive pages
 */
get_header(); ?>

<main class="archive-page">
    <!-- Hero Section do Archive -->
    <section class="archive-hero">
        <div class="container">
            <div class="archive-header">
                <?php
                $archive_title = get_the_archive_title();
                $archive_description = get_the_archive_description();
                ?>

                <h1 class="archive-title"><?php echo $archive_title; ?></h1>

                <?php if ($archive_description) : ?>
                <div class="archive-description">
                    <?php echo $archive_description; ?>
                </div>
                <?php endif; ?>

                <div class="archive-stats">
                    <span class="stat-item">
                        <strong><?php echo $wp_query->found_posts; ?></strong>
                        <?php echo $wp_query->found_posts == 1 ? 'item encontrado' : 'itens encontrados'; ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a>
                <span class="separator">/</span>
                <span class="current"><?php echo get_the_archive_title(); ?></span>
            </nav>
        </div>
    </section>

    <div class="container">
        <div class="archive-content">
            <!-- Conteúdo Principal -->
            <div class="main-content">
                <!-- Filtros e Ordenação -->
                <div class="archive-filters">
                    <div class="filters-header">
                        <h3>Filtrar Conteúdo</h3>
                        <button class="filter-toggle btn btn-secondary">Filtros</button>
                    </div>

                    <div class="filters-content">
                        <?php if (is_category()) : ?>
                        <div class="filter-group">
                            <label for="subcategory-filter">Subcategorias:</label>
                            <?php
                            $current_cat = get_queried_object();
                            $subcategories = get_categories(array(
                                'child_of' => $current_cat->term_id,
                                'hide_empty' => true
                            ));
                            
                            if ($subcategories) : ?>
                            <select id="subcategory-filter" class="filter-select">
                                <option value="">Todas as subcategorias</option>
                                <?php foreach ($subcategories as $subcat) : ?>
                                <option value="<?php echo $subcat->slug; ?>"><?php echo $subcat->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="filter-group">
                            <label for="sort-by">Ordenar por:</label>
                            <select id="sort-by" class="filter-select">
                                <option value="date-desc">Mais recentes</option>
                                <option value="date-asc">Mais antigos</option>
                                <option value="title-asc">A-Z</option>
                                <option value="title-desc">Z-A</option>
                                <option value="popular">Mais populares</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grid de Conteúdo -->
                <section class="archive-grid-section">
                    <?php if (have_posts()) : ?>
                    <div class="archive-grid" id="archive-container">
                        <?php while (have_posts()) : the_post(); ?>
                        <article class="archive-card" data-date="<?php echo get_the_date('Y-m-d'); ?>"
                            data-title="<?php the_title(); ?>">
                            <div class="archive-card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                                    <?php else : ?>
                                    <div class="archive-image-placeholder">
                                        <?php if (is_post_type_archive('receitas') || is_tax('categoria-receita')) : ?>
                                        <span>🍳</span>
                                        <?php elseif (is_post_type_archive('news')) : ?>
                                        <span>📰</span>
                                        <?php else : ?>
                                        <span>📄</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </a>

                                <?php
                                        // Badge para posts em destaque
                                        if (get_post_meta(get_the_ID(), 'destaque', true)) :
                                        ?>
                                <div class="featured-badge">
                                    <i class="fas fa-star"></i>
                                    Destaque
                                </div>
                                <?php endif; ?>

                                <?php
                                        // Categoria/Taxonomia
                                        $terms = get_the_terms(get_the_ID(), get_post_type() === 'receitas' ? 'categoria-receita' : 'category');
                                        if ($terms && !is_wp_error($terms)) :
                                            $term = $terms[0];
                                        ?>
                                <span class="archive-category">
                                    <?php echo $term->name; ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="archive-card-content">
                                <h3 class="archive-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="archive-card-meta">
                                    <span class="archive-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </span>

                                    <?php if (get_post_type() === 'receitas') : ?>
                                    <?php
                                                $tempo_preparo = get_post_meta(get_the_ID(), 'tempo_preparo', true);
                                                if ($tempo_preparo) :
                                                ?>
                                    <span class="archive-time">
                                        <i class="far fa-clock"></i>
                                        <?php echo esc_html($tempo_preparo); ?> min
                                    </span>
                                    <?php endif; ?>

                                    <?php
                                                $dificuldade = get_post_meta(get_the_ID(), 'dificuldade', true);
                                                if ($dificuldade) :
                                                    $dificuldade_class = 'difficulty-' . strtolower($dificuldade);
                                                ?>
                                    <span class="archive-difficulty <?php echo esc_attr($dificuldade_class); ?>">
                                        <?php echo esc_html($dificuldade); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php else : ?>
                                    <span class="archive-author">
                                        <i class="far fa-user"></i>
                                        <?php the_author(); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="archive-card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </div>

                                <div class="archive-card-footer">
                                    <a href="<?php the_permalink(); ?>" class="btn">
                                        <?php echo get_post_type() === 'receitas' ? 'Ver Receita' : 'Ler Mais'; ?>
                                    </a>

                                    <?php if (get_post_type() !== 'receitas') : ?>
                                    <div class="archive-views">
                                        <i class="far fa-eye"></i>
                                        <?php echo get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0'; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    </div>
                      <!-- Paginação -->
                       <?php include(get_template_directory() . '/template-parts/paginacao.php'); ?>                 

                    <?php else : ?>
                    <div class="no-results">
                        <div class="no-results-content">
                            <div class="no-results-icon">
                                <?php if (is_post_type_archive('receitas')) : ?>
                                <span>🍳</span>
                                <?php elseif (is_post_type_archive('news')) : ?>
                                <span>📰</span>
                                <?php else : ?>
                                <span>🔍</span>
                                <?php endif; ?>
                            </div>
                            <h3>Nenhum conteúdo encontrado</h3>
                            <p>Não encontramos <?php echo is_post_type_archive('receitas') ? 'receitas' : 'conteúdo'; ?>
                                nesta categoria no momento.</p>
                            <div class="no-results-actions">
                                <a href="<?php echo home_url(); ?>" class="btn">Voltar para Home</a>
                                <a href="<?php echo get_post_type_archive_link('receitas'); ?>"
                                    class="btn btn-secondary">Ver Todas as Receitas</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="archive-sidebar">
                <!-- Widget Taxonomias Relacionadas -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <?php
                        if (is_category()) {
                            echo 'Categorias Relacionadas';
                        } elseif (is_tag()) {
                            echo 'Tags Relacionadas';
                        } elseif (is_tax()) {
                            echo 'Termos Relacionados';
                        } else {
                            echo 'Categorias';
                        }
                        ?>
                    </h3>

                    <?php if (is_category()) : ?>
                    <ul class="taxonomy-list">
                        <?php
                        $current_cat = get_queried_object();
                        $sibling_cats = get_categories(array(
                            'parent' => $current_cat->parent,
                            'hide_empty' => true,
                            'exclude' => $current_cat->term_id
                        ));
                        
                        foreach ($sibling_cats as $cat) : ?>
                        <li>
                            <a href="<?php echo get_category_link($cat->term_id); ?>">
                                <?php echo $cat->name; ?>
                                <span class="taxonomy-count">(<?php echo $cat->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php elseif (is_tag()) : ?>
                    <ul class="taxonomy-list">
                        <?php
                        $current_tag = get_queried_object();
                        $popular_tags = get_tags(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 10,
                            'exclude' => $current_tag->term_id
                        ));
                        
                        foreach ($popular_tags as $tag) : ?>
                        <li>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>">
                                <?php echo $tag->name; ?>
                                <span class="taxonomy-count">(<?php echo $tag->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <ul class="taxonomy-list">
                        <?php
                        $categories = get_categories(array(
                            'hide_empty' => true,
                            'number' => 10
                        ));
                        
                        foreach ($categories as $cat) : ?>
                        <li>
                            <a href="<?php echo get_category_link($cat->term_id); ?>">
                                <?php echo $cat->name; ?>
                                <span class="taxonomy-count">(<?php echo $cat->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Widget Conteúdo em Destaque -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Em Destaque</h3>
                    <div class="featured-sidebar">
                        <?php
                        $featured_args = array(
                            'post_type' => get_post_type() ?: 'post',
                            'posts_per_page' => 3,
                            'meta_key' => 'destaque',
                            'meta_value' => '1',
                            'post__not_in' => array(get_the_ID())
                        );
                        
                        $featured_posts = new WP_Query($featured_args);
                        
                        if ($featured_posts->have_posts()) :
                            while ($featured_posts->have_posts()) : $featured_posts->the_post(); ?>
                        <article class="featured-sidebar-item">
                            <a href="<?php the_permalink(); ?>" class="featured-sidebar-link">
                                <?php if (has_post_thumbnail()) : ?>
                                <div class="featured-sidebar-image">
                                    <?php the_post_thumbnail('thumbnail', array('loading' => 'lazy')); ?>
                                </div>
                                <?php endif; ?>
                                <div class="featured-sidebar-content">
                                    <h4><?php the_title(); ?></h4>
                                    <span class="featured-sidebar-meta">
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </span>
                                </div>
                            </a>
                        </article>
                        <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>Nenhum conteúdo em destaque.</p>';
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Widget Newsletter -->
                <section class="whatsapp-simple">
                    <div class="container">
                        <h2>📱 Receba Receitas no WhatsApp!</h2>
                        <p>Junte-se ao nosso canal e receba conteúdo exclusivo diretamente no seu celular.</p>

                        <div class="whatsapp-simple-action">
                            <a href="https://wa.me/5511999999999?text=Quero%20participar%20do%20canal%20de%20receitas!"
                                class="whatsapp-simple-btn" target="_blank" rel="noopener noreferrer">
                                <span>💚</span>
                                Entrar no Canal WhatsApp
                            </a>
                        </div>

                        <p class="whatsapp-simple-note">
                            ✅ Grátis • ✅ Sem spam • ✅ Conteúdo exclusivo
                        </p>
                    </div>
                </section>

                <!-- Widget Ads -->
                <div class="sidebar-widget">
                    <div class="ad-sidebar">
                        <span>Anúncio</span>
                        <p>Espaço para publicidade</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
<?php get_footer(); ?>