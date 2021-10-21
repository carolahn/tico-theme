<?php
get_header(); 
pageBanner(array(
  'title' => 'All Products',
  'subtitle' => 'Beautiful amigurumis to play and decorate'
)); 
?>

<div class="container container--narrow page-section">
  <?php
    while(have_posts()) {
      the_post(); ?>

      <!-- classes for css -->
      <div class="post-item" style="padding-top: 2rem">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

        <div class="metabox">
          <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('j/n/y'); ?> in <?php echo get_the_category_list(', '); ?></p>
        </div>

        <div class="generic-content">
          <?php 
            if(has_excerpt()) {
              the_excerpt();
            } else {
              echo "<p>" . wp_trim_words(get_field('main_body_content'), 18) . "</p>";
            }
          ?>
          <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
        </div>
      </div>
      <?php
    }
    echo paginate_links();
  ?>
</div>

<?php get_footer();
?>