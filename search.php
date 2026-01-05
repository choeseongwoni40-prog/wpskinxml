<?php get_header(); ?>

<div class="search-header" style="background: #fff; padding: 30px; border-radius: 10px; margin-bottom: 40px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
    <h1 style="margin: 0 0 15px 0; color: #2c3e50;">
        검색 결과: "<?php echo get_search_query(); ?>"
    </h1>
    
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" style="display: flex; gap: 10px;">
        <input type="search" name="s" placeholder="다시 검색..." value="<?php echo get_search_query(); ?>" style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
        <button type="submit" style="padding: 12px 30px; background: #3498db; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">검색</button>
    </form>
</div>

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
    <p style="color: #666; margin-bottom: 30px;">
        <?php echo $wp_query->found_posts; ?>개의 결과를 찾았습니다.
    </p>
    
    <div class="content-grid">
        <?php while (have_posts()): the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                
                <div class="post-thumbnail">
                    <?php 
                    if (!empty($display_code)) {
                        echo '<div class="ad-container" style="height: 100%; margin: 0;">';
                        echo '<div class="ad-label">Sponsored</div>';
                        echo $display_code;
                        echo '</div>';
                    } elseif (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                    } else {
                        echo '<div class="ad-container" style="height: 100%; margin: 0; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">';
                        echo '<span style="color: #999;">광고 위치</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
                
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
    
    <?php if (!empty($display_code)): ?>
    <div class="ad-container" style="margin: 40px 0;">
        <div class="ad-label">Advertisement</div>
        <?php echo $display_code; ?>
    </div>
    <?php endif; ?>
    
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
    <div class="no-posts" style="background: #fff; padding: 60px; border-radius: 10px; text-align: center;">
        <h2>검색 결과가 없습니다</h2>
        <p style="color: #666; margin: 20px 0;">"<?php echo get_search_query(); ?>"에 대한 검색 결과를 찾을 수 없습니다.</p>
        <p style="color: #666; margin-bottom: 30px;">다른 키워드로 검색해보세요.</p>
        
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" style="max-width: 500px; margin: 0 auto; display: flex; gap: 10px;">
            <input type="search" name="s" placeholder="검색어 입력..." style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
            <button type="submit" style="padding: 12px 30px; background: #3498db; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">검색</button>
        </form>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
