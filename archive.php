<?php get_header(); ?>

<div class="archive-header" style="background: #fff; padding: 30px; border-radius: 10px; margin-bottom: 40px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
    <h1 style="margin: 0; color: #2c3e50;">
        <?php
        if (is_category()) {
            single_cat_title();
        } elseif (is_tag()) {
            single_tag_title();
        } elseif (is_author()) {
            the_author();
        } elseif (is_day()) {
            echo get_the_date();
        } elseif (is_month()) {
            echo get_the_date('F Y');
        } elseif (is_year()) {
            echo get_the_date('Y');
        } else {
            echo '아카이브';
        }
        ?>
    </h1>
    
    <?php if (is_category() && category_description()): ?>
        <div style="margin-top: 15px; color: #666;">
            <?php echo category_description(); ?>
        </div>
    <?php endif; ?>
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
        <h2>게시글이 없습니다</h2>
        <p style="color: #666; margin: 20px 0;">이 카테고리에는 아직 글이 없습니다.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="read-more">홈으로 돌아가기</a>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
