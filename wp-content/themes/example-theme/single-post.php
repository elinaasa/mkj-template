<?php
get_header();
?>

<main class="full-width">
    <section class="products">
        <article class="single">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();

                    if (has_post_thumbnail() && !post_password_required()) {
                        echo '<div class="featured-image">';
                        the_title('<h1>', '</h1>');
                        the_post_thumbnail('large');
                        echo do_shortcode('[like_button]');
                        echo '</div>';
                    }

                    ob_start();

                    the_content();

                    $content = ob_get_clean();
                    $content_without_image = preg_replace('/<img[^>]+>/', '', $content);

                    // Output the content without the featured image
                    echo '<div class="entry-content">';
                    echo $content_without_image;
                    echo '</div>';

                endwhile;
            else :
                _e('Sorry, no posts matched your criteria.', 'esimerkki');
            endif;
            ?>
        </article>
    </section>
</main>

<?php
get_footer();
?>
