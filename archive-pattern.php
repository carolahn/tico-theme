<?php
get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)"></div>
  <div class="page-banner__content container container--narrow">
    <!-- <h1 class="page-banner__title"><?php 
        if(is_category()) { single_cat_title(); } 
        if(is_author()) { echo 'Posts by '; the_author(); } 
    ?></h1> -->
    <h1 class="page-banner__title">All Patterns</h1>
    <div class="page-banner__intro">
      <p>Get awesome patterns from Tico Crochê</p>
    </div>
  </div>
</div>

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
          <!-- all post content --> 
          <!-- <?php the_content(); ?> -->

          <!-- just a post resume --> 
          <?php the_excerpt(); ?>
          <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
        </div>
      </div>
      <?php
    }
    echo paginate_links();
  ?>

  <hr class="section-break">
  <p>Do you want to start crocheting amigurumis? <a href="<?php echo site_url('/for-beginners'); ?>">Check out our patterns for beginners.</a></p>
</div>

<?php get_footer();
?>