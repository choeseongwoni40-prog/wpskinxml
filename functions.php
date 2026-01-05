<?php
/**
 * Revenue Maximizer Pro Functions
 */

// 테마 설정
function revenue_maximizer_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => '메인 메뉴'
    ));
}
add_action('after_setup_theme', 'revenue_maximizer_setup');

// 스크립트 및 스타일 로드
function revenue_maximizer_scripts() {
    wp_enqueue_style('revenue-maximizer-style', get_stylesheet_uri());
    wp_enqueue_script('revenue-maximizer-custom', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0', true);
    
    // 로컬라이즈 스크립트
    wp_localize_script('revenue-maximizer-custom', 'adSettings', array(
        'interstitialCode' => get_option('rm_interstitial_code', ''),
        'anchorCode' => get_option('rm_anchor_code', ''),
        'nativeCode' => get_option('rm_native_code', ''),
        'displayCode' => get_option('rm_display_code', '')
    ));
}
add_action('wp_enqueue_scripts', 'revenue_maximizer_scripts');

// 관리자 메뉴 추가
function revenue_maximizer_admin_menu() {
    add_menu_page(
        '광고 설정',
        '광고 설정',
        'manage_options',
        'rm-ad-settings',
        'revenue_maximizer_ad_settings_page',
        'dashicons-money-alt',
        30
    );
    
    add_menu_page(
        'AI 글쓰기',
        'AI 글쓰기',
        'manage_options',
        'rm-ai-writer',
        'revenue_maximizer_ai_writer_page',
        'dashicons-edit',
        31
    );
    
    add_menu_page(
        '썸네일 생성',
        '썸네일 생성',
        'manage_options',
        'rm-thumbnail',
        'revenue_maximizer_thumbnail_page',
        'dashicons-format-image',
        32
    );
}
add_action('admin_menu', 'revenue_maximizer_admin_menu');

// 광고 설정 페이지
function revenue_maximizer_ad_settings_page() {
    if (isset($_POST['rm_save_ads'])) {
        update_option('rm_interstitial_code', sanitize_textarea_field($_POST['interstitial_code']));
        update_option('rm_anchor_code', sanitize_textarea_field($_POST['anchor_code']));
        update_option('rm_native_code', sanitize_textarea_field($_POST['native_code']));
        update_option('rm_display_code', sanitize_textarea_field($_POST['display_code']));
        echo '<div class="updated"><p>광고 설정이 저장되었습니다!</p></div>';
    }
    
    $interstitial = get_option('rm_interstitial_code', '');
    $anchor = get_option('rm_anchor_code', '');
    $native = get_option('rm_native_code', '');
    $display = get_option('rm_display_code', '');
    
    ?>
    <div class="wrap">
        <h1>광고 설정</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="interstitial_code">전면 광고 코드</label></th>
                    <td>
                        <textarea name="interstitial_code" id="interstitial_code" rows="5" cols="50" class="large-text"><?php echo esc_textarea($interstitial); ?></textarea>
                        <p class="description">페이지 전환 시 표시되는 전면 광고 코드를 입력하세요. (1분 간격)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="anchor_code">앵커 광고 코드</label></th>
                    <td>
                        <textarea name="anchor_code" id="anchor_code" rows="5" cols="50" class="large-text"><?php echo esc_textarea($anchor); ?></textarea>
                        <p class="description">화면 하단 고정 앵커 광고 코드를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="native_code">네이티브 광고 코드</label></th>
                    <td>
                        <textarea name="native_code" id="native_code" rows="5" cols="50" class="large-text"><?php echo esc_textarea($native); ?></textarea>
                        <p class="description">콘텐츠 내 네이티브 광고 코드를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="display_code">디스플레이 광고 코드</label></th>
                    <td>
                        <textarea name="display_code" id="display_code" rows="5" cols="50" class="large-text"><?php echo esc_textarea($display); ?></textarea>
                        <p class="description">일반 디스플레이 광고 코드를 입력하세요. (썸네일 위치)</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('광고 설정 저장', 'primary', 'rm_save_ads'); ?>
        </form>
        
        <hr>
        <h2>광고 최적화 팁</h2>
        <ul>
            <li>전면 광고는 사용자 경험을 해치지 않도록 1분 간격으로 설정되어 있습니다.</li>
            <li>네이티브 광고는 콘텐츠와 자연스럽게 어울리도록 배치됩니다.</li>
            <li>썸네일 위치의 광고는 클릭률이 높은 영역입니다.</li>
            <li>모든 광고는 반응형으로 설계되어 모바일에서도 최적화됩니다.</li>
        </ul>
    </div>
    <?php
}

// AI 글쓰기 페이지 (파소나 법칙)
function revenue_maximizer_ai_writer_page() {
    if (isset($_POST['rm_generate_content'])) {
        $topic = sanitize_text_field($_POST['content_topic']);
        $keywords = sanitize_text_field($_POST['content_keywords']);
        
        // 파소나 법칙 기반 콘텐츠 구조
        $pasona_content = revenue_maximizer_generate_pasona_content($topic, $keywords);
        
        if (isset($_POST['create_post']) && !empty($pasona_content)) {
            $post_data = array(
                'post_title' => $topic,
                'post_content' => $pasona_content,
                'post_status' => 'draft',
                'post_type' => 'post'
            );
            
            $post_id = wp_insert_post($post_data);
            
            if ($post_id) {
                echo '<div class="updated"><p>글이 생성되었습니다! <a href="' . get_edit_post_link($post_id) . '">편집하기</a></p></div>';
            }
        }
    }
    
    ?>
    <div class="wrap">
        <h1>AI 글쓰기 (파소나 법칙)</h1>
        <p>파소나 법칙(Problem, Affinity, Solution, Offer, Narrowing, Action)을 활용한 수익형 블로그 글을 생성합니다.</p>
        
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="content_topic">글 주제</label></th>
                    <td>
                        <input type="text" name="content_topic" id="content_topic" class="regular-text" required>
                        <p class="description">작성할 글의 주제를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="content_keywords">키워드</label></th>
                    <td>
                        <input type="text" name="content_keywords" id="content_keywords" class="regular-text">
                        <p class="description">쉼표로 구분하여 키워드를 입력하세요.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('콘텐츠 생성 및 초안 작성', 'primary', 'rm_generate_content'); ?>
            <input type="hidden" name="create_post" value="1">
        </form>
        
        <hr>
        <h2>파소나 법칙이란?</h2>
        <ol>
            <li><strong>Problem (문제)</strong>: 독자의 문제점을 명확히 제시</li>
            <li><strong>Affinity (친근감)</strong>: 공감대 형성</li>
            <li><strong>Solution (해결책)</strong>: 구체적인 해결 방법 제시</li>
            <li><strong>Offer (제안)</strong>: 상품/서비스 제안</li>
            <li><strong>Narrowing (한정)</strong>: 긴급성/희소성 강조</li>
            <li><strong>Action (행동)</strong>: 명확한 행동 유도</li>
        </ol>
    </div>
    <?php
}

// 파소나 법칙 콘텐츠 생성
function revenue_maximizer_generate_pasona_content($topic, $keywords) {
    $keywords_array = !empty($keywords) ? array_map('trim', explode(',', $keywords)) : array();
    
    $content = "<h2>😟 이런 고민 있으신가요?</h2>\n\n";
    $content .= "<p>" . esc_html($topic) . "에 대해 많은 분들이 고민하고 계십니다. 정보가 너무 많아서 어디서부터 시작해야 할지 막막하셨죠?</p>\n\n";
    
    $content .= "[NATIVE_AD_1]\n\n";
    
    $content .= "<h2>💡 저도 같은 고민을 했습니다</h2>\n\n";
    $content .= "<p>사실 저도 " . esc_html($topic) . " 관련해서 수없이 많은 시행착오를 겪었습니다. 여러분의 마음을 충분히 이해합니다.</p>\n\n";
    
    $content .= "<h2>✨ 해결책을 찾았습니다</h2>\n\n";
    $content .= "<p>하지만 이제는 확실한 방법을 알고 있습니다. " . esc_html($topic) . "를 효과적으로 해결할 수 있는 방법을 공유드리겠습니다.</p>\n\n";
    
    if (!empty($keywords_array)) {
        $content .= "<h3>핵심 포인트</h3>\n<ul>\n";
        foreach ($keywords_array as $keyword) {
            $content .= "<li>" . esc_html($keyword) . "에 대한 이해</li>\n";
        }
        $content .= "</ul>\n\n";
    }
    
    $content .= "[NATIVE_AD_2]\n\n";
    
    $content .= "<h2>🎁 특별한 제안</h2>\n\n";
    $content .= "<p>지금 바로 시작하시면 더 빠른 결과를 얻으실 수 있습니다. 아래 추천 방법들을 확인해보세요.</p>\n\n";
    
    $content .= "<h2>⏰ 지금이 최적의 타이밍입니다</h2>\n\n";
    $content .= "<p>더 이상 미루지 마세요. 많은 분들이 이미 시작하셨습니다.</p>\n\n";
    
    $content .= "[NATIVE_AD_3]\n\n";
    
    $content .= "<h2>🚀 지금 바로 시작하세요</h2>\n\n";
    $content .= "<p>오늘 소개해드린 방법들을 실천해보세요. 분명 좋은 결과가 있을 것입니다!</p>\n\n";
    
    $content .= "<div class='cta-section'>\n";
    $content .= "<p><strong>더 많은 정보가 필요하신가요? 아래 추천 리소스를 확인해보세요!</strong></p>\n";
    $content .= "</div>";
    
    return $content;
}

// 썸네일 생성 페이지
function revenue_maximizer_thumbnail_page() {
    if (isset($_POST['rm_generate_thumbnail'])) {
        $post_id = intval($_POST['post_id']);
        $text = sanitize_text_field($_POST['thumbnail_text']);
        $bg_color = sanitize_hex_color($_POST['bg_color']);
        
        $thumbnail_id = revenue_maximizer_create_thumbnail($text, $bg_color);
        
        if ($thumbnail_id && $post_id) {
            set_post_thumbnail($post_id, $thumbnail_id);
            echo '<div class="updated"><p>썸네일이 생성되고 적용되었습니다!</p></div>';
        }
    }
    
    $posts = get_posts(array('numberposts' => 20, 'post_status' => 'any'));
    
    ?>
    <div class="wrap">
        <h1>썸네일 생성기</h1>
        <p>글에 사용할 썸네일을 자동으로 생성합니다. (실제로는 광고가 표시됩니다)</p>
        
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="post_id">글 선택</label></th>
                    <td>
                        <select name="post_id" id="post_id" class="regular-text">
                            <option value="">-- 글 선택 --</option>
                            <?php foreach ($posts as $post): ?>
                                <option value="<?php echo $post->ID; ?>"><?php echo esc_html($post->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="thumbnail_text">썸네일 텍스트</label></th>
                    <td>
                        <input type="text" name="thumbnail_text" id="thumbnail_text" class="regular-text" placeholder="예: 필독!">
                        <p class="description">썸네일에 표시할 텍스트를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="bg_color">배경색</label></th>
                    <td>
                        <input type="color" name="bg_color" id="bg_color" value="#3498db">
                    </td>
                </tr>
            </table>
            <?php submit_button('썸네일 생성 및 적용', 'primary', 'rm_generate_thumbnail'); ?>
        </form>
        
        <hr>
        <p><strong>참고:</strong> 실제 프론트엔드에서는 썸네일 위치에 광고가 표시됩니다.</p>
    </div>
    <?php
}

// 썸네일 생성 함수 (단순 이미지 생성)
function revenue_maximizer_create_thumbnail($text, $bg_color) {
    $upload_dir = wp_upload_dir();
    $image_width = 600;
    $image_height = 400;
    
    $image = imagecreatetruecolor($image_width, $image_height);
    
    list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x");
    $bg_color_id = imagecolorallocate($image, $r, $g, $b);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    
    imagefill($image, 0, 0, $bg_color_id);
    
    $font_size = 5;
    $text_width = imagefontwidth($font_size) * strlen($text);
    $text_height = imagefontheight($font_size);
    $x = ($image_width - $text_width) / 2;
    $y = ($image_height - $text_height) / 2;
    
    imagestring($image, $font_size, $x, $y, $text, $text_color);
    
    $filename = 'thumbnail-' . time() . '.png';
    $filepath = $upload_dir['path'] . '/' . $filename;
    
    imagepng($image, $filepath);
    imagedestroy($image);
    
    $attachment = array(
        'post_mime_type' => 'image/png',
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment($attachment, $filepath);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $filepath);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    return $attach_id;
}

// 커스텀 사이트 URL 옵션
function revenue_maximizer_custom_url_setting() {
    add_settings_section(
        'rm_custom_url_section',
        '사이트 링크 설정',
        null,
        'general'
    );
    
    add_settings_field(
        'rm_custom_home_url',
        '사이트 이름 링크 URL',
        'revenue_maximizer_custom_url_callback',
        'general',
        'rm_custom_url_section'
    );
    
    register_setting('general', 'rm_custom_home_url');
}
add_action('admin_init', 'revenue_maximizer_custom_url_setting');

function revenue_maximizer_custom_url_callback() {
    $value = get_option('rm_custom_home_url', home_url('/'));
    echo '<input type="text" name="rm_custom_home_url" value="' . esc_attr($value) . '" class="regular-text">';
    echo '<p class="description">사이트 이름을 클릭할 때 이동할 URL을 설정하세요. 비워두면 홈페이지로 연결됩니다.</p>';
}

// 네이티브 광고 숏코드 처리
function revenue_maximizer_native_ad_shortcode($atts) {
    $atts = shortcode_atts(array('id' => '1'), $atts);
    $native_code = get_option('rm_native_code', '');
    
    if (empty($native_code)) {
        return '<div class="native-ad-container"><div class="ad-label">Advertisement</div><p>광고 코드를 설정해주세요.</p></div>';
    }
    
    return '<div class="native-ad-container"><div class="ad-label">Advertisement</div>' . $native_code . '</div>';
}
add_shortcode('native_ad', 'revenue_maximizer_native_ad_shortcode');

// 콘텐츠에 네이티브 광고 자동 삽입
function revenue_maximizer_insert_ads_in_content($content) {
    if (!is_single()) {
        return $content;
    }
    
    $native_code = get_option('rm_native_code', '');
    
    if (empty($native_code)) {
        return $content;
    }
    
    // [NATIVE_AD_X] 패턴을 실제 광고 코드로 교체
    $content = preg_replace_callback(
        '/\[NATIVE_AD_\d+\]/',
        function($matches) use ($native_code) {
            return '<div class="native-ad-container"><div class="ad-label">Sponsored</div>' . $native_code . '</div>';
        },
        $content
    );
    
    // 자동 삽입: 문단 개수 확인 후 삽입
    $paragraphs = explode('</p>', $content);
    $paragraph_count = count($paragraphs);
    
    if ($paragraph_count > 3) {
        $insert_after = floor($paragraph_count / 3);
        
        $ad_html = '<div class="native-ad-container"><div class="ad-label">Advertisement</div>' . $native_code . '</div>';
        
        $paragraphs[$insert_after] .= $ad_html;
        $content = implode('</p>', $paragraphs);
    }
    
    return $content;
}
add_filter('the_content', 'revenue_maximizer_insert_ads_in_content');

// 썸네일을 광고로 교체
function revenue_maximizer_thumbnail_ad($html, $post_id, $post_thumbnail_id) {
    if (!is_single($post_id)) {
        $display_code = get_option('rm_display_code', '');
        
        if (!empty($display_code)) {
            return '<div class="post-thumbnail ad-container"><div class="ad-label">Sponsored</div>' . $display_code . '</div>';
        }
    }
    
    return $html;
}
add_filter('post_thumbnail_html', 'revenue_maximizer_thumbnail_ad', 10, 3);

// 발췌문 길이 조정
function revenue_maximizer_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'revenue_maximizer_excerpt_length');

// 발췌문 더보기 텍스트
function revenue_maximizer_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'revenue_maximizer_excerpt_more');

// 댓글 기능 비활성화
function revenue_maximizer_disable_comments() {
    return false;
}
add_filter('comments_open', 'revenue_maximizer_disable_comments', 10, 2);
add_filter('pings_open', 'revenue_maximizer_disable_comments', 10, 2);

// 기존 댓글 숨기기
function revenue_maximizer_hide_existing_comments($comments) {
    return array();
}
add_filter('comments_array', 'revenue_maximizer_hide_existing_comments', 10, 2);

// 관리자 메뉴에서 댓글 제거
function revenue_maximizer_remove_comment_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'revenue_maximizer_remove_comment_menu');

// 관리자바에서 댓글 제거
function revenue_maximizer_remove_comment_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'revenue_maximizer_remove_comment_admin_bar');
?>
