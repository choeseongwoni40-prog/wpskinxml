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
    
    add_menu_page(
        '승인용 글 생성',
        '승인용 글 생성',
        'manage_options',
        'rm-approval-writer',
        'revenue_maximizer_approval_writer_page',
        'dashicons-yes-alt',
        33
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

// 승인용 글 생성 페이지
function revenue_maximizer_approval_writer_page() {
    if (isset($_POST['rm_generate_approval_posts'])) {
        $post_count = intval($_POST['post_count']);
        $category = sanitize_text_field($_POST['category']);
        $post_type_selected = sanitize_text_field($_POST['post_type']);
        
        $generated_posts = array();
        
        for ($i = 1; $i <= $post_count; $i++) {
            $post_data = revenue_maximizer_generate_approval_post($i, $category, $post_type_selected);
            
            $post_id = wp_insert_post(array(
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array(get_cat_ID($category))
            ));
            
            if ($post_id) {
                // 썸네일 생성 및 추가
                $thumbnail_id = revenue_maximizer_create_approval_thumbnail($post_data['title'], $i);
                if ($thumbnail_id) {
                    set_post_thumbnail($post_id, $thumbnail_id);
                }
                
                $generated_posts[] = $post_id;
            }
        }
        
        if (!empty($generated_posts)) {
            echo '<div class="updated"><p>' . count($generated_posts) . '개의 승인용 글이 생성되었습니다!</p></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1>🎯 승인용 글 자동 생성기</h1>
        <p>Google AdSense, 네이버 애드포스트 등의 광고 플랫폼 승인을 받기 위한 고품질 글을 자동으로 생성합니다.</p>
        
        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>⚠️ 승인용 글 작성 가이드:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>최소 10-20개의 글 필요 (권장: 20-30개)</li>
                <li>각 글은 1000자 이상의 고품질 콘텐츠</li>
                <li>다양한 카테고리와 주제로 작성</li>
                <li>독창적이고 유용한 정보 제공</li>
                <li>저작권 위반 없는 콘텐츠</li>
            </ul>
        </div>
        
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="post_count">생성할 글 개수</label></th>
                    <td>
                        <input type="number" name="post_count" id="post_count" value="20" min="10" max="50" class="small-text" required>
                        <p class="description">승인을 위해 최소 10개 이상 권장합니다. (권장: 20-30개)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="category">카테고리</label></th>
                    <td>
                        <input type="text" name="category" id="category" value="일반" class="regular-text" required>
                        <p class="description">글이 속할 카테고리 이름 (없으면 자동 생성)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="post_type">글 유형</label></th>
                    <td>
                        <select name="post_type" id="post_type" class="regular-text">
                            <option value="general">일반 정보성 글</option>
                            <option value="howto">가이드/튜토리얼</option>
                            <option value="review">리뷰/분석</option>
                            <option value="tips">팁/노하우</option>
                            <option value="news">뉴스/트렌드</option>
                        </select>
                        <p class="description">생성할 글의 유형을 선택하세요.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('승인용 글 자동 생성', 'primary', 'rm_generate_approval_posts', true, array('style' => 'font-size: 16px; padding: 10px 30px;')); ?>
        </form>
        
        <hr style="margin: 40px 0;">
        
        <h2>📋 승인 체크리스트</h2>
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <h3>AdSense 승인 요구사항:</h3>
            <ul style="line-height: 2;">
                <li>✅ 최소 10-20개 이상의 고품질 글</li>
                <li>✅ 각 글 1000자 이상</li>
                <li>✅ 독창적인 콘텐츠 (복사 금지)</li>
                <li>✅ 개인정보처리방침 페이지</li>
                <li>✅ 연락처 페이지</li>
                <li>✅ 사이트맵 생성</li>
                <li>✅ 6개월 이상 된 도메인 (권장)</li>
                <li>✅ 충분한 트래픽 (일 100명 이상 권장)</li>
            </ul>
            
            <h3 style="margin-top: 20px;">네이버 애드포스트 승인 요구사항:</h3>
            <ul style="line-height: 2;">
                <li>✅ 최소 30개 이상의 글</li>
                <li>✅ 블로그 운영 3개월 이상</li>
                <li>✅ 지속적인 포스팅 (주 2-3회)</li>
                <li>✅ 방문자 수 (일 평균 50명 이상)</li>
                <li>✅ 저품질 콘텐츠 없음</li>
            </ul>
        </div>
        
        <hr style="margin: 40px 0;">
        
        <h2>💡 승인 후 할 일</h2>
        <div style="background: #d1ecf1; border: 1px solid #0c5460; padding: 15px; border-radius: 5px;">
            <ol style="line-height: 2;">
                <li><strong>광고 코드 삽입:</strong> 광고 설정 메뉴에서 광고 코드 입력</li>
                <li><strong>수익형 글 작성:</strong> AI 글쓰기 메뉴에서 파소나 법칙 활용</li>
                <li><strong>SEO 최적화:</strong> 키워드 연구 및 메타 태그 설정</li>
                <li><strong>트래픽 증대:</strong> SNS 홍보, 검색엔진 최적화</li>
                <li><strong>콘텐츠 업데이트:</strong> 정기적인 새 글 발행</li>
            </ol>
        </div>
    </div>
    <?php
}

// 승인용 글 생성 함수
function revenue_maximizer_generate_approval_post($index, $category, $type) {
    // 다양한 주제 풀
    $topics = array(
        'general' => array(
            '건강한 생활습관을 위한 5가지 방법',
            '효율적인 시간 관리 전략',
            '스트레스 해소에 도움되는 활동들',
            '독서의 중요성과 효과적인 독서법',
            '올바른 수면 습관 만들기',
            '운동을 시작하는 초보자를 위한 가이드',
            '균형잡힌 식단의 중요성',
            '긍정적인 마인드셋 만들기',
            '새로운 취미 시작하는 방법',
            '효과적인 목표 설정 방법'
        ),
        'howto' => array(
            '초보자를 위한 홈트레이닝 가이드',
            '효율적인 공부 방법 총정리',
            '집에서 할 수 있는 간단한 요리법',
            '디지털 기기 활용 팁',
            '효과적인 프레젠테이션 만들기',
            '사진 잘 찍는 방법',
            '글쓰기 실력 향상시키기',
            '외국어 학습 효율적으로 하기',
            '돈 관리 잘하는 방법',
            '정리정돈 노하우'
        ),
        'review' => array(
            '최근 트렌드 분석 및 전망',
            '생활 필수품 비교 분석',
            '인기 있는 온라인 서비스 리뷰',
            '유용한 모바일 앱 추천',
            '독서 추천 및 서평',
            '학습 도구 비교 및 추천',
            '건강 관리 제품 분석',
            '생산성 향상 도구 리뷰',
            '여가 활동 추천',
            '온라인 강의 플랫폼 비교'
        ),
        'tips' => array(
            '일상생활 속 유용한 팁 모음',
            '돈 절약하는 실용적인 방법',
            '효율성을 높이는 생활 꿀팁',
            '건강 관리 노하우',
            '시간 절약 테크닉',
            '집안일을 쉽게 하는 방법',
            '스마트한 쇼핑 팁',
            '에너지 절약 방법',
            '안전한 생활을 위한 팁',
            '환경 보호 실천 방법'
        ),
        'news' => array(
            '최신 트렌드 소식',
            '주목할 만한 변화와 발전',
            '새로운 라이프스타일 트렌드',
            '건강 관련 최신 정보',
            '교육 분야의 새로운 움직임',
            '기술 발전과 우리 생활',
            '환경 보호 관련 소식',
            '문화 예술 동향',
            '사회적 이슈 분석',
            '미래 전망 및 예측'
        )
    );
    
    $selected_topics = isset($topics[$type]) ? $topics[$type] : $topics['general'];
    $topic_index = ($index - 1) % count($selected_topics);
    $title = $selected_topics[$topic_index] . ' ' . $index;
    
    // 고품질 콘텐츠 생성
    $content = revenue_maximizer_generate_quality_content($title, $type, $index);
    
    return array(
        'title' => $title,
        'content' => $content
    );
}

// 고품질 콘텐츠 생성 함수
function revenue_maximizer_generate_quality_content($title, $type, $index) {
    $intro_templates = array(
        "오늘은 {$title}에 대해 자세히 알아보겠습니다. 많은 분들이 이 주제에 대해 궁금해하시는데요, 체계적으로 정리해드리겠습니다.",
        "안녕하세요! 이번 글에서는 {$title}에 관한 유익한 정보를 공유하려고 합니다. 실생활에 도움이 되는 내용들로 준비했습니다.",
        "{$title}는 많은 분들이 관심을 가지는 주제입니다. 오늘은 이에 대해 깊이 있게 다뤄보도록 하겠습니다.",
        "요즘 {$title}에 대한 관심이 높아지고 있습니다. 이번 포스팅에서 상세하게 설명드리겠습니다.",
    );
    
    $content = "<h2>들어가며</h2>\n\n";
    $content .= "<p>" . $intro_templates[$index % count($intro_templates)] . "</p>\n\n";
    
    $content .= "<h2>주요 내용</h2>\n\n";
    
    // 본문 섹션 1
    $content .= "<h3>1. 기본 개념 이해하기</h3>\n\n";
    $content .= "<p>먼저 기본적인 개념부터 이해하는 것이 중요합니다. 이 주제를 제대로 이해하기 위해서는 몇 가지 핵심 요소들을 파악해야 합니다.</p>\n\n";
    $content .= "<p>첫째, 기초를 탄탄히 하는 것이 가장 중요합니다. 많은 사람들이 기본을 간과하고 넘어가는 경우가 많은데, 이는 나중에 큰 문제가 될 수 있습니다.</p>\n\n";
    $content .= "<p>둘째, 체계적인 접근 방법이 필요합니다. 무작정 시작하기보다는 계획을 세우고 단계별로 진행하는 것이 효과적입니다.</p>\n\n";
    
    // 본문 섹션 2
    $content .= "<h3>2. 실천 방법</h3>\n\n";
    $content .= "<p>이론을 알았다면 이제 실천하는 방법에 대해 알아보겠습니다. 실제로 적용할 수 있는 구체적인 방법들을 소개합니다.</p>\n\n";
    $content .= "<p>가장 먼저 해야 할 일은 현재 상황을 정확히 파악하는 것입니다. 자신의 상태를 객관적으로 평가하고, 개선이 필요한 부분을 찾아내야 합니다.</p>\n\n";
    $content .= "<p>다음으로는 작은 목표부터 시작하는 것을 추천합니다. 너무 큰 목표는 부담이 될 수 있으므로, 달성 가능한 작은 목표들을 세워서 하나씩 실천해나가는 것이 좋습니다.</p>\n\n";
    
    // 본문 섹션 3
    $content .= "<h3>3. 주의사항 및 팁</h3>\n\n";
    $content .= "<p>실천 과정에서 주의해야 할 점들이 있습니다. 이러한 점들을 미리 알고 있으면 시행착오를 줄일 수 있습니다.</p>\n\n";
    $content .= "<p>첫 번째 주의사항은 조급하게 서두르지 않는 것입니다. 모든 것은 시간이 필요하며, 꾸준함이 가장 중요합니다. 단기간에 큰 변화를 기대하기보다는 장기적인 관점에서 접근하는 것이 좋습니다.</p>\n\n";
    $content .= "<p>두 번째로, 자신에게 맞는 방법을 찾는 것이 중요합니다. 다른 사람에게 효과적이었던 방법이 반드시 나에게도 맞는 것은 아닙니다. 여러 방법을 시도해보면서 자신에게 가장 잘 맞는 방법을 찾아야 합니다.</p>\n\n";
    
    // 본문 섹션 4
    $content .= "<h3>4. 기대 효과</h3>\n\n";
    $content .= "<p>이러한 방법들을 꾸준히 실천한다면 다양한 긍정적인 효과를 경험할 수 있습니다.</p>\n\n";
    $content .= "<p>단기적으로는 작은 변화들을 느낄 수 있습니다. 이러한 작은 성취감들이 모여서 지속적인 동기부여가 됩니다. 처음에는 미미하게 느껴질 수 있지만, 시간이 지날수록 그 효과는 점점 커집니다.</p>\n\n";
    $content .= "<p>장기적으로는 더욱 의미 있는 변화를 경험하게 됩니다. 삶의 질이 향상되고, 전반적인 만족도가 높아지는 것을 느낄 수 있을 것입니다.</p>\n\n";
    
    // 본문 섹션 5
    $content .= "<h3>5. 추가 정보 및 리소스</h3>\n\n";
    $content .= "<p>더 깊이 있는 정보를 얻고 싶으시다면 관련 서적이나 온라인 자료들을 찾아보는 것을 추천합니다. 신뢰할 수 있는 출처의 정보를 참고하는 것이 중요합니다.</p>\n\n";
    $content .= "<p>또한, 비슷한 관심사를 가진 사람들과 교류하는 것도 큰 도움이 됩니다. 온라인 커뮤니티나 오프라인 모임을 통해 경험을 공유하고 서로 격려할 수 있습니다.</p>\n\n";
    
    // 결론
    $content .= "<h2>마치며</h2>\n\n";
    $content .= "<p>지금까지 {$title}에 대해 알아보았습니다. 이 글이 여러분께 유익한 정보가 되었기를 바랍니다.</p>\n\n";
    $content .= "<p>가장 중요한 것은 오늘 배운 내용을 실제로 실천에 옮기는 것입니다. 작은 것부터 시작해서 꾸준히 노력한다면 분명 좋은 결과가 있을 것입니다.</p>\n\n";
    $content .= "<p>여러분의 성공을 응원합니다. 궁금한 점이나 추가로 알고 싶은 내용이 있다면 언제든지 댓글로 남겨주세요. 감사합니다!</p>\n\n";
    
    // 추가 관련 정보
    $content .= "<h2>참고 사항</h2>\n\n";
    $content .= "<p>이 글의 내용은 일반적인 정보 제공을 목적으로 작성되었습니다. 개인의 상황에 따라 적용 방법이 다를 수 있으니, 필요한 경우 전문가의 조언을 구하시는 것을 권장합니다.</p>\n\n";
    $content .= "<p>지속적으로 업데이트되는 최신 정보들을 확인하시고, 자신에게 맞는 최적의 방법을 찾아가시기 바랍니다.</p>\n\n";
    
    return $content;
}

// 승인용 썸네일 생성
function revenue_maximizer_create_approval_thumbnail($text, $index) {
    $upload_dir = wp_upload_dir();
    $image_width = 800;
    $image_height = 500;
    
    $image = imagecreatetruecolor($image_width, $image_height);
    
    // 다양한 색상 사용
    $colors = array(
        array(52, 152, 219),   // 파란색
        array(46, 204, 113),   // 녹색
        array(155, 89, 182),   // 보라색
        array(241, 196, 15),   // 노란색
        array(230, 126, 34),   // 주황색
        array(231, 76, 60),    // 빨간색
        array(149, 165, 166),  // 회색
        array(26, 188, 156),   // 청록색
    );
    
    $color = $colors[$index % count($colors)];
    $bg_color = imagecolorallocate($image, $color[0], $color[1], $color[2]);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    $dark_color = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $bg_color);
    
    // 텍스트 줄바꿈 처리
    $words = explode(' ', $text);
    $lines = array();
    $current_line = '';
    
    foreach ($words as $word) {
        $test_line = $current_line . ' ' . $word;
        if (strlen($test_line) > 30) {
            $lines[] = trim($current_line);
            $current_line = $word;
        } else {
            $current_line = $test_line;
        }
    }
    $lines[] = trim($current_line);
    
    // 텍스트 그리기
    $font_size = 5;
    $y_start = ($image_height - (count($lines) * 20)) / 2;
    
    foreach ($lines as $i => $line) {
        $text_width = imagefontwidth($font_size) * strlen($line);
        $x = ($image_width - $text_width) / 2;
        $y = $y_start + ($i * 20);
        
        imagestring($image, $font_size, $x, $y, $line, $text_color);
    }
    
    // 하단에 번호 추가
    $number_text = '#' . $index;
    imagestring($image, 3, $image_width - 60, $image_height - 30, $number_text, $text_color);
    
    $filename = 'approval-thumb-' . $index . '-' . time() . '.png';
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
?>
