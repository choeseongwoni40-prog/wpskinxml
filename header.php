<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- 앵커 광고 -->
<?php 
$anchor_code = get_option('rm_anchor_code', '');
if (!empty($anchor_code)): 
?>
<div id="anchor-ad" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 9998; background: #fff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
    <div style="text-align: center; padding: 5px;">
        <div style="font-size: 9px; color: #999; margin-bottom: 5px;">Advertisement</div>
        <?php echo $anchor_code; ?>
    </div>
</div>
<?php endif; ?>

<!-- 전면 광고 오버레이 -->
<div id="interstitial-ad" class="interstitial-ad">
    <div class="interstitial-content">
        <button class="close-ad" onclick="closeInterstitial()">×</button>
        <div id="interstitial-ad-content"></div>
    </div>
</div>

<header>
    <div class="container">
        <div class="site-header">
            <h1 class="site-title">
                <a href="<?php echo esc_url(get_option('rm_custom_home_url', home_url('/'))); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            
            <?php if (has_custom_logo()): ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>
            
            <nav>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'fallback_cb' => 'revenue_maximizer_default_menu'
                ));
                ?>
            </nav>
        </div>
    </div>
</header>

<main>
    <div class="container">

<?php
// 기본 메뉴 폴백
function revenue_maximizer_default_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">홈</a></li>';
    
    $pages = get_pages(array('number' => 5));
    foreach ($pages as $page) {
        echo '<li><a href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a></li>';
    }
    
    echo '</ul>';
}
?>
