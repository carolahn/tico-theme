<?php
    get_header();

    while(have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('product'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Products</a> <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j/n/y'); ?> in <?php echo get_the_category_list(', '); ?></span></p>
            </div>
            <div class="generic-content" style="margin-top: 70px"><?php 
                the_field('main_body_content');
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
                wp_reset_postdata();
                $relatedStores = get_field('related_store'); 
            
                
                if ($relatedStores) {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Stores:</h2>';
                    echo '<ul class="min-list link-list">';
                    
                    foreach($relatedStores as $store) {
                        ?> <li><a href="<?php echo get_the_permalink($store); ?>"><?php echo get_the_title($store); ?></a></li><?php
                    }
                    echo '</ul>';
                }
            ?>

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
                        $homepagePatterns->the_post();
                        get_template_part('template-parts/content', 'event');
                    }
                }
            ?>
        </div>
<?php }
    get_footer();
?>