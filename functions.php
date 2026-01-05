<?php
/**
 * Revenue Master Theme Functions
 */

// 테마 설정
function revenue_master_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => '메인 메뉴',
    ));
    
    set_post_thumbnail_size(800, 450, true);
}
add_action('after_setup_theme', 'revenue_master_setup');

// 사이드바 등록
function revenue_master_widgets_init() {
    register_sidebar(array(
        'name' => '사이드바',
        'id' => 'sidebar-1',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'revenue_master_widgets_init');

// 스크립트 & 스타일 로드
function revenue_master_scripts() {
    wp_enqueue_style('revenue-master-style', get_stylesheet_uri());
    wp_enqueue_script('revenue-master-custom', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0', true);
    
    wp_localize_script('revenue-master-custom', 'revenueData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('revenue_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'revenue_master_scripts');

// 관리자 메뉴 추가
function revenue_master_admin_menu() {
    add_menu_page(
        '수익화 설정',
        '수익화 설정',
        'manage_options',
        'revenue-settings',
        'revenue_master_settings_page',
        'dashicons-money-alt',
        30
    );
    
    add_submenu_page(
        'revenue-settings',
        'AI 글쓰기',
        'AI 글쓰기',
        'manage_options',
        'revenue-ai-writer',
        'revenue_master_ai_writer_page'
    );
}
add_action('admin_menu', 'revenue_master_admin_menu');

// 광고 설정 페이지
function revenue_master_settings_page() {
    if (isset($_POST['revenue_save_settings'])) {
        check_admin_referer('revenue_settings_nonce');
        
        update_option('revenue_anchor_ad', sanitize_textarea_field($_POST['anchor_ad_code']));
        update_option('revenue_native_ad', sanitize_textarea_field($_POST['native_ad_code']));
        update_option('revenue_interstitial_ad', sanitize_textarea_field($_POST['interstitial_ad_code']));
        update_option('revenue_enable_anchor', isset($_POST['enable_anchor']) ? '1' : '0');
        update_option('revenue_enable_interstitial', isset($_POST['enable_interstitial']) ? '1' : '0');
        
        echo '<div class="updated"><p>설정이 저장되었습니다!</p></div>';
    }
    
    $anchor_ad = get_option('revenue_anchor_ad', '');
    $native_ad = get_option('revenue_native_ad', '');
    $interstitial_ad = get_option('revenue_interstitial_ad', '');
    $enable_anchor = get_option('revenue_enable_anchor', '1');
    $enable_interstitial = get_option('revenue_enable_interstitial', '1');
    ?>
    <div class="wrap">
        <h1>🎯 수익화 광고 설정</h1>
        <form method="post">
            <?php wp_nonce_field('revenue_settings_nonce'); ?>
            
            <h2>앵커 광고</h2>
            <p><label><input type="checkbox" name="enable_anchor" value="1" <?php checked($enable_anchor, '1'); ?>> 앵커 광고 활성화</label></p>
            <textarea name="anchor_ad_code" rows="5" style="width:100%;"><?php echo esc_textarea($anchor_ad); ?></textarea>
            <p class="description">구글 애드센스 앵커 광고 코드를 입력하세요.</p>
            
            <h2>네이티브(수동) 광고</h2>
            <textarea name="native_ad_code" rows="5" style="width:100%;"><?php echo esc_textarea($native_ad); ?></textarea>
            <p class="description">본문에 자동 삽입될 네이티브 광고 코드를 입력하세요.</p>
            
            <h2>전면 광고</h2>
            <p><label><input type="checkbox" name="enable_interstitial" value="1" <?php checked($enable_interstitial, '1'); ?>> 전면 광고 활성화 (페이지 전환 시, 1분 간격)</label></p>
            <textarea name="interstitial_ad_code" rows="5" style="width:100%;"><?php echo esc_textarea($interstitial_ad); ?></textarea>
            <p class="description">전면 광고 코드를 입력하세요.</p>
            
            <p><input type="submit" name="revenue_save_settings" class="button button-primary" value="설정 저장"></p>
        </form>
    </div>
    <?php
}

// AI 글쓰기 페이지 (파소나 법칙)
function revenue_master_ai_writer_page() {
    ?>
    <div class="wrap">
        <h1>✍️ 파소나 법칙 AI 글쓰기</h1>
        <p>파소나(PASONA) 법칙을 활용한 수익형 블로그 글을 작성합니다.</p>
        
        <form id="ai-writer-form">
            <?php wp_nonce_field('revenue_ai_writer', 'ai_writer_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="post_topic">글 주제</label></th>
                    <td><input type="text" id="post_topic" name="post_topic" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="post_keyword">주요 키워드</label></th>
                    <td><input type="text" id="post_keyword" name="post_keyword" class="regular-text" required>
                    <p class="description">SEO를 위한 주요 키워드를 입력하세요.</p></td>
                </tr>
                <tr>
                    <th><label for="target_audience">타겟 독자</label></th>
                    <td><input type="text" id="target_audience" name="target_audience" class="regular-text" placeholder="예: 30대 직장인"></td>
                </tr>
            </table>
            
            <p><button type="button" id="generate-pasona" class="button button-primary">파소나 법칙으로 글 생성</button></p>
        </form>
        
        <div id="pasona-result" style="display:none; margin-top:30px;">
            <h2>생성된 글</h2>
            <div id="generated-content" style="background:#fff; padding:20px; border:1px solid #ddd;"></div>
            <p><button type="button" id="create-post" class="button button-primary">블로그 포스트로 저장</button></p>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#generate-pasona').click(function() {
                var topic = $('#post_topic').val();
                var keyword = $('#post_keyword').val();
                var audience = $('#target_audience').val();
                
                if (!topic || !keyword) {
                    alert('주제와 키워드를 입력해주세요.');
                    return;
                }
                
                // 파소나 법칙 템플릿 생성
                var content = generatePasonaContent(topic, keyword, audience);
                $('#generated-content').html(content);
                $('#pasona-result').show();
            });
            
            function generatePasonaContent(topic, keyword, audience) {
                var html = '<h1>' + topic + '</h1>\n\n';
                
                // Problem (문제)
                html += '<h2>🚨 이런 고민 있으신가요?</h2>\n';
                html += '<p>' + (audience || '많은 분들이') + ' ' + keyword + '와 관련하여 어려움을 겪고 계십니다. ';
                html += '구체적인 정보를 찾기 어렵고, 어디서부터 시작해야 할지 막막하신가요?</p>\n\n';
                
                // Affinity (공감)
                html += '<h2>💭 저도 같은 고민을 했습니다</h2>\n';
                html += '<p>저 역시 ' + keyword + '에 대해 알아보면서 많은 시행착오를 겪었습니다. ';
                html += '수많은 정보 속에서 정말 필요한 것을 찾기란 쉽지 않았죠.</p>\n\n';
                
                // Solution (해결책)
                html += '<h2>✅ 해결 방법을 찾았습니다</h2>\n';
                html += '<p>하지만 체계적으로 접근하면 ' + keyword + '는 생각보다 어렵지 않습니다. ';
                html += '제가 직접 경험하고 검증한 방법을 공유드리겠습니다.</p>\n\n';
                
                // Offer (제안)
                html += '<h2>🎁 구체적인 방법은 다음과 같습니다</h2>\n';
                html += '<h3>1단계: 기초 이해하기</h3>\n';
                html += '<p>' + keyword + '의 기본 개념과 원리를 이해하는 것이 첫 번째 단계입니다.</p>\n\n';
                html += '<h3>2단계: 실전 적용하기</h3>\n';
                html += '<p>이론을 바탕으로 실제로 적용해보는 단계입니다.</p>\n\n';
                html += '<h3>3단계: 최적화하기</h3>\n';
                html += '<p>경험을 바탕으로 자신만의 방법으로 발전시키는 단계입니다.</p>\n\n';
                
                // Narrowing (한정)
                html += '<h2>⏰ 지금 바로 시작하세요</h2>\n';
                html += '<p>' + keyword + '는 빠르게 변화하는 분야입니다. ';
                html += '지금 시작하지 않으면 더 많은 시간과 비용이 들 수 있습니다.</p>\n\n';
                
                // Action (행동)
                html += '<h2>🚀 다음 단계로 나아가세요</h2>\n';
                html += '<p>이 글이 도움이 되셨다면, 지금 바로 첫 번째 단계부터 시작해보세요. ';
                html += '작은 실천이 큰 변화를 만들어냅니다.</p>\n\n';
                
                html += '<p><strong>💡 추가 정보가 필요하신가요?</strong> 댓글로 질문을 남겨주시면 성심껏 답변드리겠습니다!</p>';
                
                return html;
            }
            
            $('#create-post').click(function() {
                var content = $('#generated-content').html();
                var topic = $('#post_topic').val();
                
                $.post(ajaxurl, {
                    action: 'create_pasona_post',
                    nonce: $('#ai_writer_nonce').val(),
                    title: topic,
                    content: content
                }, function(response) {
                    if (response.success) {
                        alert('포스트가 생성되었습니다!');
                        window.location.href = 'post.php?post=' + response.data.post_id + '&action=edit';
                    }
                });
            });
        });
        </script>
    </div>
    <?php
}

// 파소나 포스트 생성 AJAX
add_action('wp_ajax_create_pasona_post', 'revenue_master_create_pasona_post');
function revenue_master_create_pasona_post() {
    check_ajax_referer('revenue_ai_writer', 'nonce');
    
    if (!current_user_can('publish_posts')) {
        wp_send_json_error('권한이 없습니다.');
    }
    
    $title = sanitize_text_field($_POST['title']);
    $content = wp_kses_post($_POST['content']);
    
    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'draft',
        'post_type' => 'post'
    ));
    
    if ($post_id) {
        // AI로 썸네일 생성 (텍스트 기반)
        $thumbnail_id = revenue_master_generate_thumbnail($post_id, $title);
        if ($thumbnail_id) {
            set_post_thumbnail($post_id, $thumbnail_id);
        }
        
        wp_send_json_success(array('post_id' => $post_id));
    } else {
        wp_send_json_error('포스트 생성에 실패했습니다.');
    }
}

// 썸네일 자동 생성 (텍스트 기반 이미지)
function revenue_master_generate_thumbnail($post_id, $title) {
    // 800x450 이미지 생성
    $width = 800;
    $height = 450;
    $image = imagecreatetruecolor($width, $height);
    
    // 그라데이션 배경 (파랑-보라)
    $colors = array();
    for ($i = 0; $i < $height; $i++) {
        $r = 52 + ($i / $height) * (142 - 52);
        $g = 152 + ($i / $height) * (68 - 152);
        $b = 219 + ($i / $height) * (173 - 219);
        $colors[$i] = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, 0, $i, $width, $i + 1, $colors[$i]);
    }
    
    // 텍스트 색상
    $white = imagecolorallocate($image, 255, 255, 255);
    
    // 제목 추가 (최대 50자)
    $short_title = mb_substr($title, 0, 50, 'UTF-8');
    $font_size = 5;
    
    // 텍스트를 여러 줄로 나누기
    $words = explode(' ', $short_title);
    $lines = array();
    $current_line = '';
    
    foreach ($words as $word) {
        $test_line = $current_line . ($current_line ? ' ' : '') . $word;
        if (strlen($test_line) > 40) {
            $lines[] = $current_line;
            $current_line = $word;
        } else {
            $current_line = $test_line;
        }
    }
    if ($current_line) $lines[] = $current_line;
    
    // 텍스트 중앙 정렬
    $y_start = ($height - (count($lines) * 20)) / 2;
    foreach ($lines as $idx => $line) {
        $text_width = imagefontwidth($font_size) * strlen($line);
        $x = ($width - $text_width) / 2;
        $y = $y_start + ($idx * 20);
        imagestring($image, $font_size, $x, $y, $line, $white);
    }
    
    // 임시 파일로 저장
    $upload_dir = wp_upload_dir();
    $filename = 'thumbnail-' . $post_id . '-' . time() . '.jpg';
    $filepath = $upload_dir['path'] . '/' . $filename;
    
    imagejpeg($image, $filepath, 90);
    imagedestroy($image);
    
    // 미디어 라이브러리에 추가
    $attachment = array(
        'post_mime_type' => 'image/jpeg',
        'post_title' => $title,
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment($attachment, $filepath, $post_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $filepath);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    return $attach_id;
}

// 타뷸라 스타일 광고 HTML 생성 (썸네일 제거, 광고만)
function revenue_master_generate_taboola_ad($ad_code, $position = 'content') {
    if ($position == 'sidebar') {
        // 사이드바용
        return '
        <div class="native-ad-container">
            <div class="native-ad-label">Sponsored Content</div>
            <div class="sidebar-ad-items">
                <div class="sidebar-ad-item">
                    ' . $ad_code . '
                </div>
            </div>
        </div>';
    }
    
    // 본문용 (광고만 표시)
    return '
    <div class="recommended-content">
        <h3 class="recommended-header">🔥 추천 콘텐츠</h3>
        <div class="recommended-grid">
            <div class="recommended-item">
                ' . $ad_code . '
            </div>
        </div>
    </div>';
}

// 본문에 광고 자동 삽입 (썸네일 없이 광고만)
function revenue_master_insert_native_ads($content) {
    if (!is_single()) return $content;
    
    $native_ad = get_option('revenue_native_ad', '');
    if (empty($native_ad)) return $content;
    
    // 단락 분리
    $paragraphs = explode('</p>', $content);
    $total = count($paragraphs);
    
    if ($total > 3) {
        // 첫 번째 광고: 2번째 단락 후
        $ad_html_1 = revenue_master_generate_taboola_ad($native_ad, 'content');
        array_splice($paragraphs, 2, 0, $ad_html_1);
        
        // 두 번째 광고: 중간 지점
        if ($total > 6) {
            $middle = floor($total / 2) + 1;
            $ad_html_2 = revenue_master_generate_taboola_ad($native_ad, 'content');
            array_splice($paragraphs, $middle, 0, $ad_html_2);
        }
        
        // 세 번째 광고: 글 끝
        if ($total > 9) {
            $ad_html_3 = '
            <div class="recommended-content">
                <h3 class="recommended-header">📚 함께 읽으면 좋은 글</h3>
                <div class="taboola-style-ads">
                    <div class="taboola-ad-item">
                        ' . $native_ad . '
                    </div>
                </div>
            </div>';
            $paragraphs[] = $ad_html_3;
        }
    }
    
    return implode('</p>', $paragraphs);
}
add_filter('the_content', 'revenue_master_insert_native_ads');

// 앵커 광고 삽입
function revenue_master_anchor_ad() {
    if (!get_option('revenue_enable_anchor', '1')) return;
    
    $anchor_ad = get_option('revenue_anchor_ad', '');
    if (!empty($anchor_ad)) {
        echo $anchor_ad;
    }
}
add_action('wp_footer', 'revenue_master_anchor_ad');

// 전면 광고용 데이터 전달
function revenue_master_interstitial_data() {
    if (!get_option('revenue_enable_interstitial', '1')) return;
    
    $interstitial_ad = get_option('revenue_interstitial_ad', '');
    ?>
    <script>
    var revenueInterstitial = {
        enabled: <?php echo !empty($interstitial_ad) ? 'true' : 'false'; ?>,
        code: <?php echo json_encode($interstitial_ad); ?>
    };
    </script>
    <?php
}
add_action('wp_head', 'revenue_master_interstitial_data');

// 발췌문 길이 조정
function revenue_master_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'revenue_master_excerpt_length');

// 발췌문 더보기 텍스트
function revenue_master_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'revenue_master_excerpt_more');
?>
