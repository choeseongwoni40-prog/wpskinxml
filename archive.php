<?php get_header(); ?>

<header class="archive-header" style="background: #fff; padding: 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h1 class="archive-title">
        <?php
        if (is_category()) {
            single_cat_title('카테고리: ');
        } elseif (is_tag()) {
            single_tag_title('태그: ');
        } elseif (is_author()) {
            echo '작성자: ' . get_the_author();
        } elseif (is_date()) {
            echo '날짜별 글: ';
            if (is_year()) {
                echo get_the_date('Y년');
            } elseif (is_month()) {
                echo get_the_date('Y년 F');
            } else {
                echo get_the_date();
            }
        } else {
            echo '아카이브';
        }
        ?>
    </h1>
    
    <?php if (category_description() || tag_description()) : ?>
        <div class="archive-description" style="margin-top: 15px; color: #666;">
            <?php echo category_description() . tag_description(); ?>
        </div>
    <?php endif; ?>
</header>

<div class="posts-grid">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                <?php echo revenue_pro_thumbnail_ad(); ?>
                
                <div class="post-content-area">
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <div class="post-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more-btn">
                        자세히 읽기 →
                    </a>
                </div>
            </article>
            <?php
        endwhile;
        ?>
        <div class="pagination" style="grid-column: 1 / -1; text-align: center; margin-top: 20px;">
            <?php
            echo paginate_links(array(
                'prev_text' => '← 이전',
                'next_text' => '다음 →',
                'type' => 'plain'
            ));
            ?>
        </div>
        <?php
    else :
        ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p>게시글이 없습니다.</p>
        </div>
        <?php
    endif;
    ?>
</div>

<?php get_footer(); ?>
