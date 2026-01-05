<?php
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside class="sidebar">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <?php
    // 네이티브 광고 삽입 (사이드바용)
    $native_ad = get_option('revenue_native_ad', '');
    if (!empty($native_ad) && is_single()) :
    ?>
        <div class="widget">
            <div class="native-ad-container">
                <div class="native-ad-label">Sponsored</div>
                <?php echo $native_ad; ?>
            </div>
        </div>
    <?php endif; ?>
</aside>
