<?php get_header(); ?>

<div class="content-wrapper">
    <main class="main-content">
        <div class="posts-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('post-thumbnail', array('class' => 'post-thumbnail')); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="post-content-wrapper">
                        <header class="post-header">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <span class="post-author"> • <?php the_author(); ?></span>
                            </div>
                        </header>
                        
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            자세히 보기 →
                        </a>
                    </div>
                </article>
            <?php
                endwhile;
                
                // 페이지네이션
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← 이전',
                    'next_text' => '다음 →',
                ));
            else :
            ?>
                <p>게시물이 없습니다.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
