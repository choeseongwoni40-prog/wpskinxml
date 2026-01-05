<?php get_header(); ?>

<div class="error-404" style="text-align: center; padding: 80px 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h1 style="font-size: 120px; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">404</h1>
    
    <h2 style="font-size: 32px; margin: 20px 0;">페이지를 찾을 수 없습니다</h2>
    
    <p style="color: #666; font-size: 18px; margin: 20px 0;">
        요청하신 페이지가 존재하지 않거나 이동되었습니다.
    </p>
    
    <div style="margin: 40px 0;">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="read-more-btn" style="margin: 0 10px;">
            홈으로 돌아가기
        </a>
    </div>
    
    <div style="margin-top: 60px; padding-top: 40px; border-top: 2px solid #eee;">
        <h3 style="margin-bottom: 20px;">검색해보시겠어요?</h3>
        <?php get_search_form(); ?>
    </div>
    
    <?php
    // 최근 글 표시
    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish'
    ));
    
    if ($recent_posts) :
        ?>
        <div style="margin-top: 60px; padding-top: 40px; border-top: 2px solid #eee;">
            <h3 style="margin-bottom: 30px;">최근 게시글을 확인해보세요</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; text-align: left;">
                <?php foreach ($recent_posts as $post) : ?>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px 0;">
                            <a href="<?php echo get_permalink($post['ID']); ?>" style="color: #2c3e50; text-decoration: none;">
                                <?php echo esc_html($post['post_title']); ?>
                            </a>
                        </h4>
                        <p style="color: #666; font-size: 14px; margin: 0;">
                            <?php echo wp_trim_words($post['post_content'], 15); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    endif;
    ?>
</div>

<?php get_footer(); ?>
