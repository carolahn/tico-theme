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
                    <div class="two-thirds">
                        <?php 
                            $likeCount = new WP_Query(array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_designer_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            ));

                            $existStatus = 'no';
                            if (is_user_logged_in()) {
                                $existQuery = new WP_Query(array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_designer_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                ));
                                if ($existQuery->found_posts) {
                                    $existStatus = 'yes';
                                }
                            }
                        ?>
                        <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID; ?>" data-exists="<?php echo $existStatus; ?>" data-designer="<?php the_ID(); ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
                        </span>
                        <?php the_field('main_body_content'); ?>
                    </div>
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