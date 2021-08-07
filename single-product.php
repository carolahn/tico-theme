<?php
    get_header();

    while(have_posts()) {
        the_post(); ?>

        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title(); ?></h1>
                <div class="page-banner__intro">
                <p>DONT'T FORGET TO REPLACE ME LATER</p>
                </div>
            </div>
        </div>
        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('product'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Products</a> <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j/n/y'); ?> in <?php echo get_the_category_list(', '); ?></span></p>
            </div>
            <div class="generic-content" style="margin-top: 70px"><?php 
                the_content(); 
                $image = get_field('image');
                if( !empty( $image ) ): ?>
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                <?php endif; ?>
            </div>
            <div class="generic-content" style="margin-top: 30px">
                <p>Skill level: <?php the_field('skill_level'); ?></p>
                <p>Suggested price: R$<?php echo number_format(get_field('suggested_price')/100,2,",","."); ?></p>
                <div><?php the_field('instructions'); ?></div>
            </div>
            <?php
                $homepagePatterns = new WP_Query(array(
                    'posts_per_page' => 10,
                    'post_type' => 'pattern',
                    'meta_key' => 'related_products',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'meta_query' => array(
                        'key' => 'related_products',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                ));
                if ($homepagePatterns->have_posts()) {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Faça você mesmo:</h2>';
                
                    while ($homepagePatterns->have_posts()) {
                        $homepagePatterns->the_post();?>
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                                <span class="event-summary__month"><?php the_time('M'); ?></span>
                                <span class="event-summary__day"><?php the_time('d'); ?></span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <p>
                                    <?php if(has_excerpt()) {
                                        echo get_the_excerpt();
                                    } else {
                                        echo wp_trim_words(get_the_content(), 18);
                                    } ?>
                                    <a href="<?php the_permalink(); ?>" class="nu gray"> Go to pattern</a>
                                </p>
                            </div>
                        </div>
                    <?php }
                }
            ?>
        </div>
<?php }
    get_footer();
?>