</div><!-- .container -->
    </div><!-- .site-content -->

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                <p>Powered by <a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a></p>
            </div>
        </div>
    </footer>

    <!-- 전면 광고 오버레이 -->
    <div id="interstitial-overlay" class="interstitial-overlay">
        <div class="interstitial-content">
            <button class="interstitial-close" onclick="closeInterstitial()">×</button>
            <div id="interstitial-ad-slot"></div>
        </div>
    </div>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
