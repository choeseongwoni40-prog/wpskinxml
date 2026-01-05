<?php get_header(); ?>

<!-- 상단 광고 -->
<?php 
$display_code = get_option('rm_display_code', '');
if (!empty($display_code)): 
?>
<div class="ad-container" style="margin-bottom: 30px;">
    <div class="ad-label">Advertisement</div>
    <?php echo $display_code; ?>
</div>
<?php endif; ?>

<?php if (have_posts()): ?>
    <div class="content-grid">
        <?php while (have_posts()): the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                
                <!-- 썸네일은 광고로 교체 -->
                <?php if (has_post_thumbnail()): ?>
                    <div class="post-thumbnail">
                        <?php 
                        // 여기에 광고 표시
                        if (!empty($display_code)) {
                            echo '<div class="ad-container" style="height: 100%; margin: 0;">';
                            echo '<div class="ad-label">Sponsored</div>';
                            echo $display_code;
                            echo '</div>';
                        } else {
                            the_post_thumbnail('medium');
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="post-thumbnail">
                        <?php 
                        // 썸네일이 없어도 광고 표시
                        if (!empty($display_code)) {
                            echo '<div class="ad-container" style="height: 100%; margin: 0;">';
                            echo '<div class="ad-label">Sponsored</div>';
                            echo $display_code;
                            echo '</div>';
                        } else {
                            echo '<div class="ad-container" style="height: 100%; margin: 0; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">';
                            echo '<span style="color: #999;">광고 위치</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="post-content">
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <div class="post-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more">자세히 보기</a>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
    
    <!-- 중간 광고 -->
    <?php if (!empty($display_code)): ?>
    <div class="ad-container" style="margin: 40px 0;">
        <div class="ad-label">Advertisement</div>
        <?php echo $display_code; ?>
    </div>
    <?php endif; ?>
    
    <!-- 페이지네이션 -->
    <div class="pagination">
        <?php
        global $wp_query;
        
        $big = 999999999;
        
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'prev_text' => '&laquo; 이전',
            'next_text' => '다음 &raquo;',
            'type' => 'list',
            'end_size' => 3,
            'mid_size' => 2
        ));
        ?>
    </div>
    
<?php else: ?>
    <div class="no-posts">
        <h2>게시글이 없습니다</h2>
        <p>아직 작성된 글이 없습니다. 첫 번째 글을 작성해보세요!</p>
    </div>
<?php endif; ?>

<!-- 하단 광고 -->
<?php if (!empty($display_code)): ?>
<div class="ad-container" style="margin-top: 40px;">
    <div class="ad-label">Advertisement</div>
    <?php echo $display_code; ?>
</div>
<?php endif; ?>

<?php get_footer(); ?>
