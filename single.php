<?php get_header(); ?>

<div class="content-wrapper">
    <main class="main-content">
        <?php
        while (have_posts()) : the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                <header class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    <div class="post-meta">
                        <span class="post-date">📅 <?php echo get_the_date(); ?></span>
                        <span class="post-author"> • ✍️ <?php the_author(); ?></span>
                        <span class="post-comments"> • 💬 <?php comments_number('0 댓글', '1 댓글', '% 댓글'); ?></span>
                    </div>
                </header>

                <?php 
                // 썸네일 대신 광고 표시
                $native_ad = get_option('revenue_native_ad', '');
                if (!empty($native_ad)) : 
                ?>
                    <div class="post-ad-header">
                        <?php echo $native_ad; ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <footer class="post-footer">
                    <?php
                    the_tags('<div class="post-tags">🏷️ ', ', ', '</div>');
                    ?>
                </footer>

                <?php
                // 이전/다음 포스트 네비게이션
                the_post_navigation(array(
                    'prev_text' => '← %title',
                    'next_text' => '%title →',
                ));
                ?>

                <?php
                // 댓글
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </article>
        <?php endwhile; ?>
    </main>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
