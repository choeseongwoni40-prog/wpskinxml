<?php get_header(); ?>

<div class="content-wrapper">
    <main class="main-content">
        <header class="search-header">
            <h1 class="search-title">
                "<?php echo get_search_query(); ?>" 검색 결과
            </h1>
        </header>

        <div class="posts-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    <?php
                    // 썸네일 대신 광고 표시
                    $native_ad = get_option('revenue_native_ad', '');
                    if (!empty($native_ad)) :
                    ?>
                        <div class="post-ad-placeholder">
                            <?php echo $native_ad; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content-wrapper">
                        <header class="post-header">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
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
                
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← 이전',
                    'next_text' => '다음 →',
                ));
            else :
            ?>
                <div class="no-results">
                    <h2>검색 결과가 없습니다</h2>
                    <p>"<?php echo get_search_query(); ?>"에 대한 검색 결과를 찾을 수 없습니다.</p>
                    <p>다른 키워드로 다시 검색해보세요.</p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
