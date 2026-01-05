<?php get_header(); ?>

<?php
while (have_posts()) : the_post();
    ?>
    <article id="page-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        
        <div class="entry-content" style="margin-top: 30px;">
            <?php the_content(); ?>
        </div>
    </article>
    <?php
endwhile;
?>

<?php get_footer(); ?>
