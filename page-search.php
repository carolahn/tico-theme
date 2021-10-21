<?php
    get_header();

    while(have_posts()) {
		the_post(); 
		pageBanner(array(
			'title' => 'Hello there',
			'subtitle' => 'The subtitle'
		));
	?>

    <div class="container container--narrow page-section">
		<?php 
			$theParent = wp_get_post_parent_id(get_the_ID());
			if ($theParent) { ?>
				<div class="metabox metabox--position-up metabox--with-home-link">
					<p>
						<a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php echo the_title(); ?></span>
					</p>
				</div>
			<?php }
		?>
	
		<?php 
			// check if it has children
			$testArray = get_pages(array(
				'child_of' => get_the_ID()
			));

			// show min-list only if its a parent or child page
			// single page wont have min-list
			if ($theParent or $testArray) { ?>
			<div class="page-links">
				<h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
				<ul class="min-list">
					<?php
						if ($theParent) {
							$findChildrenOf = $theParent;
						} else {
							// if its not a child
							$findChildrenOf = get_the_ID();
						}

						wp_list_pages(array(
							'title_li' => NULL,
							'child_of' => $findChildrenOf,
							'sort_column' => 'menu_order'
						));
					?>
				</ul>
			</div>
		<?php } ?>

		<div class="generic-content">
			<?php get_search_form(); ?>
		</div>
    </div>

<?php }
    get_footer();
?>