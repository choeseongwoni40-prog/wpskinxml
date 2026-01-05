<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        
        <h1><?php the_title(); ?></h1>
        
        <?php 
        $display_code = get_option('rm_display_code', '');
        if (!empty($display_code)): 
        ?>
        <div class="ad-container">
            <div class="ad-label">Advertisement</div>
            <?php echo $display_code; ?>
        </div>
        <?php endif; ?>
        
        <div class="post-body">
            <?php the_content(); ?>
        </div>
        
        <?php if (!empty($display_code)): ?>
        <div class="ad-container">
            <div class="ad-label">Advertisement</div>
            <?php echo $display_code; ?>
        </div>
        <?php endif; ?>
        
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
