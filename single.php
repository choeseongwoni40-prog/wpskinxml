<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        
        <h1><?php the_title(); ?></h1>
        
        <div class="post-meta">
            <span>작성일: <?php echo get_the_date(); ?></span>
            <span> | </span>
            <span>카테고리: <?php the_category(', '); ?></span>
        </div>
        
        <!-- 상단 광고 -->
        <?php 
        $display_code = get_option('rm_display_code', '');
        if (!empty($display_code)): 
        ?>
        <div class="ad-container">
            <div class="ad-label">Advertisement</div>
            <?php echo $display_code; ?>
        </div>
        <?php endif; ?>
        
        <div class="post-body">
            <?php the_content(); ?>
        </div>
        
        <!-- 하단 광고 -->
        <?php if (!empty($display_code)): ?>
        <div class="ad-container">
            <div class="ad-label">Advertisement</div>
            <?php echo $display_code; ?>
        </div>
        <?php endif; ?>
        
        <!-- 추가 네이티브 광고 -->
        <?php 
        $native_code = get_option('rm_native_code', '');
        if (!empty($native_code)): 
        ?>
        <div class="native-ad-container">
            <div class="ad-label">추천 콘텐츠</div>
            <?php echo $native_code; ?>
        </div>
        <?php endif; ?>
        
        <!-- 관련 글 섹션 -->
        <?php
        $categories = get_the_category();
        if ($categories) {
            $category_ids = array();
            foreach($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            $related_posts = get_posts(array(
                'category__in' => $category_ids,
                'post__not_in' => array(get_the_ID()),
                'posts_per_page' => 3,
                'orderby' => 'rand'
            ));
            
            if ($related_posts):
        ?>
        <div class="related-posts" style="margin-top: 60px;">
            <h2 style="margin-bottom: 30px; color: #2c3e50;">관련 글</h2>
            <div class="content-grid">
                <?php foreach($related_posts as $post): setup_postdata($post); ?>
                    <article class="post-card">
                        <div class="post-thumbnail">
                            <?php 
                            // 관련 글도 썸네일 위치에 광고
                            if (!empty($display_code)) {
                                echo '<div class="ad-container" style="height: 100%; margin: 0;">';
                                echo '<div class="ad-label">Sponsored</div>';
                                echo $display_code;
                                echo '</div>';
                            } elseif (has_post_thumbnail()) {
                                the_post_thumbnail('medium');
                            }
                            ?>
                        </div>
                        <div class="post-content">
                            <h3 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <a href="<?php the_permalink(); ?>" class="read-more">읽어보기</a>
                        </div>
                    </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
            endif;
        }
        ?>
        
        <!-- CTA 섹션 -->
        <div style="background: #f0f8ff; padding: 30px; border-radius: 10px; margin-top: 40px; text-align: center;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">더 많은 정보가 필요하신가요?</h3>
            <p style="margin-bottom: 20px; color: #666;">최신 글을 놓치지 마세요!</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="read-more">홈으로 돌아가기</a>
        </div>
        
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
