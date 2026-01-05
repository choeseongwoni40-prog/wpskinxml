<?php get_header(); ?>

<header class="search-header" style="background: #fff; padding: 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h1 class="search-title">
        검색 결과: <span style="color: #667eea;"><?php echo get_search_query(); ?></span>
    </h1>
    
    <?php if (have_posts()) : ?>
        <p style="color: #666; margin-top: 10px;">
            <?php
            global $wp_query;
            echo $wp_query->found_posts;
            ?>개의 결과를 찾았습니다.
        </p>
    <?php endif; ?>
    
    <div style="margin-top: 20px;">
        <?php get_search_form(); ?>
    </div>
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
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 12px;">
            <h2>검색 결과가 없습니다</h2>
            <p style="color: #666; margin: 20px 0;">다른 검색어로 다시 시도해보세요.</p>
            <?php get_search_form(); ?>
        </div>
        <?php
    endif;
    ?>
</div>

<?php get_footer(); ?>
