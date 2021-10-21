<?php
    get_header();

    while(have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('store'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Stores</a> <span class="metabox__main"><?php the_title(); ?></span></p>
            </div>

            <div class="generic-content"><?php the_field('main_body_content'); ?></div>
            <?php $mapLocation = get_field('map_location'); ?>
            <div class="acf-map">
                <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                    <h3><?php the_title(); ?></h3>
                    <?php echo $mapLocation['address']; ?>
                </div>
            </div>
            
            <?php
                $relatedStores = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_type' => 'product',
                    'meta_key' => 'related_store',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'meta_query' => array(
                        'key' => 'related_store',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                ));
                
                if ($relatedStores->have_posts()) {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Products Available At ' . get_the_title() . ' Store:</h2>';
                
                    echo '<ul class="min-list link-list">';
                    while ($relatedStores->have_posts()) {
                        $relatedStores->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php }
                    echo '</ul>';
                }
            ?>
        </div>
<?php }
    get_footer();
?>