<?php
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside class="sidebar">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <?php
    // 타뷸라 스타일 사이드바 광고
    $native_ad = get_option('revenue_native_ad', '');
    if (!empty($native_ad) && is_single()) :
        echo revenue_master_generate_taboola_ad($native_ad, 'sidebar');
    endif;
    ?>
</aside>
