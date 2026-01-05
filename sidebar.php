<?php
/**
 * Sidebar Template
 * 이 테마는 사이드바를 사용하지 않지만, 호환성을 위해 파일을 포함합니다.
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-top: 40px;">
    
    <?php 
    // 광고 위젯
    $display_code = get_option('rm_display_code', '');
    if (!empty($display_code)): 
    ?>
    <div class="widget widget-ad">
        <div class="ad-container">
            <div class="ad-label">Advertisement</div>
            <?php echo $display_code; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <?php 
    // 추가 광고
    $native_code = get_option('rm_native_code', '');
    if (!empty($native_code)): 
    ?>
    <div class="widget widget-ad">
        <div class="native-ad-container">
            <div class="ad-label">Sponsored Content</div>
            <?php echo $native_code; ?>
        </div>
    </div>
    <?php endif; ?>
    
</aside>
