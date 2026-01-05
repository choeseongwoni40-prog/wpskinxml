</div>
</main>

<footer>
    <div class="container">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
            <p>Powered by <a href="https://wordpress.org" target="_blank" style="color: #3498db;">WordPress</a></p>
            
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container' => false,
                'fallback_cb' => false
            ));
            ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<script>
// 페이지 로드 완료 후 광고 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 앵커 광고가 있으면 body에 padding 추가
    var anchorAd = document.getElementById('anchor-ad');
    if (anchorAd) {
        var anchorHeight = anchorAd.offsetHeight;
        document.body.style.paddingBottom = anchorHeight + 'px';
    }
});
</script>

</body>
</html>
