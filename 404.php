<?php get_header(); ?>

<div class="content-wrapper">
    <main class="main-content">
        <article class="error-404">
            <header class="page-header">
                <h1 class="page-title">404 - 페이지를 찾을 수 없습니다</h1>
            </header>

            <div class="page-content">
                <p>죄송합니다. 요청하신 페이지를 찾을 수 없습니다.</p>
                <p>페이지가 삭제되었거나 주소가 변경되었을 수 있습니다.</p>
                
                <h2>다음을 시도해보세요:</h2>
                <ul>
                    <li><a href="<?php echo home_url('/'); ?>">홈으로 돌아가기</a></li>
                    <li>아래 검색 기능을 사용하기</li>
                </ul>

                <?php get_search_form(); ?>

                <h3>최근 게시물</h3>
                <ul>
                    <?php
                    $recent_posts = wp_get_recent_posts(array(
                        'numberposts' => 5,
                        'post_status' => 'publish'
                    ));
                    foreach ($recent_posts as $post) :
                    ?>
                        <li>
                            <a href="<?php echo get_permalink($post['ID']); ?>">
                                <?php echo $post['post_title']; ?>
                            </a>
                        </li>
                    <?php endforeach; wp_reset_query(); ?>
                </ul>
            </div>
        </article>
    </main>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
