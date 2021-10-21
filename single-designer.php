<?php
    get_header();

    while(have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        
        <div class="container container--narrow page-section">
            <div class="generic-content" style="margin-top: 70px">
                <div class="row group">
                    <div class="one-third"><?php the_post_thumbnail('designerPortrait'); ?></div>
                    <div class="two-thirds"><?php the_field('main_body_content'); ?></div>
                </div>
            </div>
           
            <?php
                $relatedPatterns = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_type' => 'pattern',
                    'meta_key' => 'related_designer',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'meta_query' => array(
                        'key' => 'related_designer',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                ));
                if ($relatedPatterns->have_posts()) {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Receitas disponíveis:</h2>';
                
                    while ($relatedPatterns->have_posts()) {
                        $relatedPatterns->the_post();?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php }
                }
            ?>
        </div>
<?php }
    get_footer();
?>