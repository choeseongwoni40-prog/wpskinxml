<?php
/**
 * Revenue Pro Theme Functions
 * 수익화 극대화 워드프레스 테마
 */

// 테마 설정
function revenue_pro_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => '메인 메뉴',
    ));
}
add_action('after_setup_theme', 'revenue_pro_setup');

// 스크립트 및 스타일 로드
function revenue_pro_scripts() {
    wp_enqueue_style('revenue-pro-style', get_stylesheet_uri());
    wp_enqueue_script('revenue-pro-custom', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0', true);
    
    // 로컬라이제이션
    wp_localize_script('revenue-pro-custom', 'revenueProData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('revenue_pro_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'revenue_pro_scripts');

// 관리자 메뉴 추가
function revenue_pro_admin_menu() {
    add_menu_page(
        'Revenue Pro 설정',
        'Revenue Pro',
        'manage_options',
        'revenue-pro-settings',
        'revenue_pro_settings_page',
        'dashicons-chart-line',
        30
    );
    
    add_submenu_page(
        'revenue-pro-settings',
        'AI 콘텐츠 생성',
        'AI 콘텐츠 생성',
        'manage_options',
        'revenue-pro-ai-content',
        'revenue_pro_ai_content_page'
    );
    
    add_submenu_page(
        'revenue-pro-settings',
        '광고 관리',
        '광고 관리',
        'manage_options',
        'revenue-pro-ads',
        'revenue_pro_ads_page'
    );
}
add_action('admin_menu', 'revenue_pro_admin_menu');

// 설정 페이지
function revenue_pro_settings_page() {
    if (isset($_POST['revenue_pro_save_settings'])) {
        check_admin_referer('revenue_pro_settings');
        
        update_option('revenue_pro_blog_link', sanitize_text_field($_POST['blog_link']));
        update_option('revenue_pro_interstitial_code', wp_kses_post($_POST['interstitial_code']));
        update_option('revenue_pro_anchor_code', wp_kses_post($_POST['anchor_code']));
        update_option('revenue_pro_native_code', wp_kses_post($_POST['native_code']));
        update_option('revenue_pro_thumbnail_code', wp_kses_post($_POST['thumbnail_code']));
        
        echo '<div class="notice notice-success"><p>설정이 저장되었습니다.</p></div>';
    }
    
    $blog_link = get_option('revenue_pro_blog_link', home_url());
    $interstitial_code = get_option('revenue_pro_interstitial_code', '');
    $anchor_code = get_option('revenue_pro_anchor_code', '');
    $native_code = get_option('revenue_pro_native_code', '');
    $thumbnail_code = get_option('revenue_pro_thumbnail_code', '');
    ?>
    <div class="wrap">
        <h1>Revenue Pro 설정</h1>
        <form method="post" action="">
            <?php wp_nonce_field('revenue_pro_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="blog_link">블로그 로고 링크 URL</label></th>
                    <td>
                        <input type="url" name="blog_link" id="blog_link" value="<?php echo esc_attr($blog_link); ?>" class="regular-text">
                        <p class="description">헤더 블로그 이름을 클릭했을 때 이동할 URL을 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="interstitial_code">전면 광고 코드</label></th>
                    <td>
                        <textarea name="interstitial_code" id="interstitial_code" rows="5" class="large-text"><?php echo esc_textarea($interstitial_code); ?></textarea>
                        <p class="description">페이지 전환 시 표시될 전면 광고 코드 (Google AdSense 등)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="anchor_code">앵커 광고 코드</label></th>
                    <td>
                        <textarea name="anchor_code" id="anchor_code" rows="5" class="large-text"><?php echo esc_textarea($anchor_code); ?></textarea>
                        <p class="description">하단 고정 앵커 광고 코드</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="native_code">네이티브 광고 코드</label></th>
                    <td>
                        <textarea name="native_code" id="native_code" rows="5" class="large-text"><?php echo esc_textarea($native_code); ?></textarea>
                        <p class="description">본문 내 삽입될 네이티브 광고 코드</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="thumbnail_code">썸네일 광고 코드</label></th>
                    <td>
                        <textarea name="thumbnail_code" id="thumbnail_code" rows="5" class="large-text"><?php echo esc_textarea($thumbnail_code); ?></textarea>
                        <p class="description">포스트 썸네일 위치에 표시될 광고 코드</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="revenue_pro_save_settings" class="button button-primary" value="설정 저장">
            </p>
        </form>
    </div>
    <?php
}

// 광고 관리 페이지
function revenue_pro_ads_page() {
    ?>
    <div class="wrap">
        <h1>광고 관리</h1>
        <div class="card">
            <h2>광고 최적화 팁</h2>
            <ul>
                <li><strong>전면 광고:</strong> 페이지 전환 시 자동으로 표시됩니다 (60초 간격)</li>
                <li><strong>앵커 광고:</strong> 화면 하단에 고정되어 항상 보입니다</li>
                <li><strong>네이티브 광고:</strong> 본문 내용에 자연스럽게 통합됩니다</li>
                <li><strong>썸네일 광고:</strong> 모든 포스트 썸네일 위치에 광고가 표시됩니다</li>
            </ul>
        </div>
        
        <div class="card">
            <h2>수익 극대화 전략</h2>
            <ol>
                <li>고품질 콘텐츠를 지속적으로 발행하세요</li>
                <li>타겟 키워드를 활용한 SEO 최적화</li>
                <li>광고 단위를 적절히 배치하여 사용자 경험 유지</li>
                <li>모바일 최적화 확인</li>
                <li>페이지 로딩 속도 개선</li>
            </ol>
        </div>
    </div>
    <?php
}

// AI 콘텐츠 생성 페이지 (파소나 법칙 기반)
function revenue_pro_ai_content_page() {
    if (isset($_POST['generate_content'])) {
        check_admin_referer('revenue_pro_ai_content');
        
        $topic = sanitize_text_field($_POST['topic']);
        $keyword = sanitize_text_field($_POST['keyword']);
        
        $content = revenue_pro_generate_pasona_content($topic, $keyword);
        
        $post_data = array(
            'post_title' => $topic,
            'post_content' => $content,
            'post_status' => 'draft',
            'post_type' => 'post'
        );
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id) {
            echo '<div class="notice notice-success"><p>콘텐츠가 생성되었습니다! <a href="' . get_edit_post_link($post_id) . '">글 수정하기</a></p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>AI 콘텐츠 생성 (파소나 법칙)</h1>
        <p>파소나(PASONA) 법칙을 활용한 수익형 블로그 콘텐츠를 자동 생성합니다.</p>
        
        <div class="card">
            <h2>파소나 법칙이란?</h2>
            <p><strong>P</strong>roblem (문제) → <strong>A</strong>ffinity (공감) → <strong>S</strong>olution (해결책) → <strong>O</strong>ffer (제안) → <strong>N</strong>arrowing (한정) → <strong>A</strong>ction (행동)</p>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('revenue_pro_ai_content'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="topic">주제</label></th>
                    <td>
                        <input type="text" name="topic" id="topic" class="regular-text" required>
                        <p class="description">예: 재택근무 생산성 향상 방법</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="keyword">핵심 키워드</label></th>
                    <td>
                        <input type="text" name="keyword" id="keyword" class="regular-text" required>
                        <p class="description">예: 재택근무, 생산성, 홈오피스</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="generate_content" class="button button-primary" value="콘텐츠 생성">
            </p>
        </form>
    </div>
    <?php
}

// 파소나 법칙 기반 콘텐츠 생성 함수
function revenue_pro_generate_pasona_content($topic, $keyword) {
    $content = '';
    
    // Problem (문제 제기)
    $content .= "<h2>😟 이런 문제로 고민하고 계신가요?</h2>\n\n";
    $content .= "<p>많은 분들이 <strong>{$keyword}</strong>와 관련하여 다음과 같은 어려움을 겪고 있습니다:</p>\n\n";
    $content .= "<ul>\n";
    $content .= "<li>효과적인 방법을 찾기 어렵다</li>\n";
    $content .= "<li>시간과 비용이 많이 든다</li>\n";
    $content .= "<li>정보가 너무 많아 혼란스럽다</li>\n";
    $content .= "<li>실질적인 결과를 얻기 힘들다</li>\n";
    $content .= "</ul>\n\n";
    
    // 네이티브 광고 삽입
    $content .= "[native_ad]\n\n";
    
    // Affinity (공감)
    $content .= "<h2>💭 저도 같은 고민을 했습니다</h2>\n\n";
    $content .= "<p>저 역시 <strong>{$keyword}</strong>에 대해 많은 시행착오를 겪었습니다. 수많은 방법을 시도해보았지만, 대부분 기대에 미치지 못했죠. 하지만 포기하지 않고 계속 연구한 결과, 드디어 효과적인 해결책을 찾아냈습니다.</p>\n\n";
    
    // Solution (해결책 제시)
    $content .= "<h2>✨ 검증된 해결책을 소개합니다</h2>\n\n";
    $content .= "<p><strong>{$topic}</strong>에 대한 완벽한 가이드를 준비했습니다. 이 방법은 다음과 같은 특징이 있습니다:</p>\n\n";
    $content .= "<div class='ai-generated-section'>\n";
    $content .= "<h3>핵심 포인트</h3>\n";
    $content .= "<ol>\n";
    $content .= "<li><strong>실용적:</strong> 즉시 적용 가능한 구체적인 방법</li>\n";
    $content .= "<li><strong>검증됨:</strong> 실제 사용자들의 성공 사례 기반</li>\n";
    $content .= "<li><strong>단계별:</strong> 초보자도 쉽게 따라할 수 있는 체계적인 가이드</li>\n";
    $content .= "<li><strong>효과적:</strong> 단기간에 눈에 띄는 결과</li>\n";
    $content .= "</ol>\n";
    $content .= "</div>\n\n";
    
    // 네이티브 광고 삽입
    $content .= "[native_ad]\n\n";
    
    // Offer (제안)
    $content .= "<h2>🎁 지금 바로 시작하세요</h2>\n\n";
    $content .= "<p>이 방법을 통해 다음과 같은 혜택을 얻으실 수 있습니다:</p>\n\n";
    $content .= "<ul>\n";
    $content .= "<li>✅ 시간과 비용 절약</li>\n";
    $content .= "<li>✅ 스트레스 감소</li>\n";
    $content .= "<li>✅ 확실한 결과 보장</li>\n";
    $content .= "<li>✅ 지속 가능한 솔루션</li>\n";
    $content .= "</ul>\n\n";
    
    // Narrowing (한정)
    $content .= "<div class='cta-section'>\n";
    $content .= "<h3>⏰ 놓치지 마세요!</h3>\n";
    $content .= "<p>지금 이 정보는 <strong>무료</strong>로 제공됩니다. 하지만 언제까지 무료로 유지될지는 모릅니다. 이 기회를 놓치지 마세요!</p>\n";
    
    // Action (행동 유도)
    $content .= "<a href='#' class='cta-button'>자세히 알아보기 →</a>\n";
    $content .= "</div>\n\n";
    
    // 추가 정보
    $content .= "<h2>📌 추가 팁</h2>\n\n";
    $content .= "<p><strong>{$keyword}</strong>를 최대한 활용하기 위한 추가 팁을 공유합니다:</p>\n\n";
    $content .= "<ol>\n";
    $content .= "<li>꾸준히 실천하는 것이 가장 중요합니다</li>\n";
    $content .= "<li>작은 변화부터 시작하세요</li>\n";
    $content .= "<li>결과를 기록하고 분석하세요</li>\n";
    $content .= "<li>필요시 전문가의 조언을 구하세요</li>\n";
    $content .= "</ol>\n\n";
    
    // 네이티브 광고 삽입
    $content .= "[native_ad]\n\n";
    
    // 결론
    $content .= "<h2>🎯 결론</h2>\n\n";
    $content .= "<p><strong>{$topic}</strong>는 올바른 방법만 알면 누구나 성공할 수 있습니다. 이 가이드가 여러분의 여정에 도움이 되기를 바랍니다. 궁금한 점이 있다면 언제든지 문의해주세요!</p>\n\n";
    
    return $content;
}

// 네이티브 광고 쇼트코드
function revenue_pro_native_ad_shortcode() {
    $native_code = get_option('revenue_pro_native_code', '');
    if (empty($native_code)) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="native-ad-container">
        <span class="ad-label">Sponsored</span>
        <?php echo $native_code; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('native_ad', 'revenue_pro_native_ad_shortcode');

// 본문에 자동으로 네이티브 광고 삽입
function revenue_pro_insert_ads_in_content($content) {
    if (!is_single()) {
        return $content;
    }
    
    // 네이티브 광고 쇼트코드를 실제 광고로 변환
    $content = str_replace('[native_ad]', do_shortcode('[native_ad]'), $content);
    
    return $content;
}
add_filter('the_content', 'revenue_pro_insert_ads_in_content');

// 썸네일 광고 함수
function revenue_pro_thumbnail_ad() {
    $thumbnail_code = get_option('revenue_pro_thumbnail_code', '');
    if (empty($thumbnail_code)) {
        return '<div class="post-thumbnail-ad"><span class="ad-label">Ad</span><p style="color:#999;">광고 코드를 설정하세요</p></div>';
    }
    
    return '<div class="post-thumbnail-ad"><span class="ad-label">Ad</span>' . $thumbnail_code . '</div>';
}

// 앵커 광고 출력
function revenue_pro_anchor_ad() {
    $anchor_code = get_option('revenue_pro_anchor_code', '');
    if (!empty($anchor_code)) {
        echo '<div class="anchor-ad">' . $anchor_code . '</div>';
    }
}
add_action('wp_footer', 'revenue_pro_anchor_ad');

// 전면 광고 출력
function revenue_pro_interstitial_ad() {
    $interstitial_code = get_option('revenue_pro_interstitial_code', '');
    if (!empty($interstitial_code)) {
        ?>
        <div id="interstitial-overlay" class="interstitial-overlay">
            <div class="interstitial-content">
                <button class="interstitial-close" onclick="closeInterstitial()">×</button>
                <?php echo $interstitial_code; ?>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'revenue_pro_interstitial_ad');

// 글 발췌문 길이 조정
function revenue_pro_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'revenue_pro_excerpt_length');

// 글 발췌문 더보기 텍스트
function revenue_pro_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'revenue_pro_excerpt_more');

// 댓글 기능 완전히 비활성화
function revenue_pro_disable_comments() {
    // 모든 포스트 타입에서 댓글 지원 제거
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'revenue_pro_disable_comments');

// 기존 댓글 닫기
function revenue_pro_close_comments() {
    return false;
}
add_filter('comments_open', 'revenue_pro_close_comments', 20, 2);
add_filter('pings_open', 'revenue_pro_close_comments', 20, 2);

// 댓글 카운트 숨기기
function revenue_pro_hide_comment_count($count) {
    return 0;
}
add_filter('get_comments_number', 'revenue_pro_hide_comment_count', 10, 2);

// 관리자 메뉴에서 댓글 제거
function revenue_pro_remove_comments_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'revenue_pro_remove_comments_menu');

// 관리자 바에서 댓글 제거
function revenue_pro_remove_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'revenue_pro_remove_comments_admin_bar');

// 썸네일 자동 생성 (Placeholder 이미지)
function revenue_pro_auto_thumbnail($post_id) {
    // 이미 썸네일이 있으면 스킵
    if (has_post_thumbnail($post_id)) {
        return;
    }
    
    // 기본 썸네일 URL (Placeholder)
    $default_thumbnail_url = 'https://via.placeholder.com/800x450/667eea/ffffff?text=Article+Image';
    
    // 외부 이미지를 미디어 라이브러리로 다운로드
    $image_id = revenue_pro_upload_image_from_url($default_thumbnail_url, $post_id);
    
    if ($image_id) {
        set_post_thumbnail($post_id, $image_id);
    }
}
add_action('publish_post', 'revenue_pro_auto_thumbnail');

// URL에서 이미지 업로드
function revenue_pro_upload_image_from_url($image_url, $post_id = 0) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($image_url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = array(
        'name' => basename($image_url),
        'tmp_name' => $tmp
    );
    
    $id = media_handle_sideload($file_array, $post_id);
    
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }
    
    return $id;
}

// SEO 메타 태그 추가
function revenue_pro_add_meta_tags() {
    if (is_single()) {
        global $post;
        $description = wp_trim_words(strip_tags($post->post_content), 30);
        ?>
        <meta name="description" content="<?php echo esc_attr($description); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr($description); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:type" content="article">
        <?php
    }
}
add_action('wp_head', 'revenue_pro_add_meta_tags');
?>
