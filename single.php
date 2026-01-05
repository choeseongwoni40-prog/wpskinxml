<?php get_header(); ?>

<?php
while (have_posts()) : the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-meta" style="color: #999; margin-bottom: 20px; font-size: 14px;">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </div>
        </header>
        
        <?php
        // 썸네일 광고 표시
        echo revenue_pro_thumbnail_ad();
        ?>
        
        <div class="entry-content" style="margin-top: 30px;">
            <?php the_content(); ?>
        </div>
        
        <footer class="entry-footer" style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #eee;">
            <div style="text-align: center;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="read-more-btn">
                    ← 홈으로 돌아가기
                </a>
            </div>
        </footer>
    </article>
    <?php
endwhile;
?>

<?php get_footer(); ?>
