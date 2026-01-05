<?php
/**
 * 사이드바 템플릿
 * 필요시 사용할 수 있는 사이드바
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside class="widget-area" style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <!-- 인기 글 -->
    <div class="widget" style="margin-bottom: 30px;">
        <h3 style="font-size: 20px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #667eea;">인기 게시글</h3>
        
        <?php
        $popular_posts = new WP_Query(array(
            'posts_per_page' => 5,
            'orderby' => 'comment_count',
            'order' => 'DESC'
        ));
        
        if ($popular_posts->have_posts()) :
            echo '<ul style="list-style: none; padding: 0;">';
            while ($popular_posts->have_posts()) : $popular_posts->the_post();
                ?>
                <li style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <a href="<?php the_permalink(); ?>" style="color: #2c3e50; text-decoration: none; display: block; transition: color 0.3s;">
                        <?php the_title(); ?>
                    </a>
                    <span style="color: #999; font-size: 12px;">
                        <?php echo get_the_date(); ?>
                    </span>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        ?>
    </div>
    
    <!-- 카테고리 -->
    <div class="widget" style="margin-bottom: 30px;">
        <h3 style="font-size: 20px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #667eea;">카테고리</h3>
        
        <?php
        $categories = get_categories(array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 10
        ));
        
        if ($categories) :
            echo '<ul style="list-style: none; padding: 0;">';
            foreach ($categories as $category) :
                ?>
                <li style="margin-bottom: 10px;">
                    <a href="<?php echo get_category_link($category->term_id); ?>" style="color: #2c3e50; text-decoration: none; display: flex; justify-content: space-between; padding: 8px 12px; background: #f8f9fa; border-radius: 5px; transition: all 0.3s;">
                        <span><?php echo esc_html($category->name); ?></span>
                        <span style="color: #667eea; font-weight: bold;"><?php echo $category->count; ?></span>
                    </a>
                </li>
                <?php
            endforeach;
            echo '</ul>';
        endif;
        ?>
    </div>
</aside>
