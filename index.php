<?php get_header(); ?>

<div class="posts-grid">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                <?php
                // 썸네일 광고 표시
                echo revenue_pro_thumbnail_ad();
                ?>
                
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
        
        // 페이지네이션
        ?>
        <div class="pagination" style="grid-column: 1 / -1; text-align: center; margin-top: 20px;">
            <?php
            echo paginate_links(array(
                'prev_text' => '← 이전',
                'next_text' => '다음 →',
                'type' => 'plain',
                'before_page_number' => '<span class="screen-reader-text">Page </span>'
            ));
            ?>
        </div>
        <?php
    else :
        ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p>아직 게시글이 없습니다.</p>
        </div>
        <?php
    endif;
    ?>
</div>

<?php get_footer(); ?>
