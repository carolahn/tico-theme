<div class="event-summary">
    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
        <span class="event-summary__month"><?php the_time('M'); ?></span>
        <span class="event-summary__day"><?php the_time('d'); ?></span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p>
            <?php if(has_excerpt()) {
                the_excerpt();
            } elseif (get_field('main_body_content')) {
                echo "<span>" . wp_trim_words(get_field('main_body_content'), 18) . "</span>";
            } else {
                echo "<span>" . wp_trim_words(get_the_content(), 18) . "</span>";
            } ?>
            <a href="<?php the_permalink(); ?>" class="nu gray"> Go to pattern</a>
        </p>
    </div>
</div>