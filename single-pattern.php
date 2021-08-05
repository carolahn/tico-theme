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
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('pattern'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Patterns Home</a> <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j/n/y'); ?> in <?php echo get_the_category_list(', '); ?></span></p>
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
                $relatedProducts = get_field('related_products');
                // print_r($relatedProducts);
                if ($relatedProducts) { ?>
                    <?php
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Shop now:</h2>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedProducts as $product) { ?>
                        <li><a href="<?php echo get_the_permalink($product); ?>"><?php echo get_the_title($product); ?></a></li>
                    <?php }
                    echo '</ul>';
                }
               
            ?>
        </div>
<?php }
    get_footer();
?>