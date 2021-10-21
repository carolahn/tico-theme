<li class="professor-card__list-item">
    <a class="professor-card" href="<?php echo get_the_permalink(); ?>">
        <img class="professor-card__image" src="<?php echo get_the_post_thumbnail_url(0, 'designerLandscape'); ?>">
        <span class="professor-card__name"><?php echo get_the_title(); ?></span>
    </a>
</li>