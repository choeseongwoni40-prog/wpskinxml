/**
 * Revenue Pro Theme - Custom JavaScript
 * 수익 극대화를 위한 광고 관리 및 사용자 경험 최적화
 */

(function($) {
    'use strict';
    
    // 전면 광고 관리
    let lastInterstitialTime = 0;
    const INTERSTITIAL_INTERVAL = 60000; // 60초 = 1분
    
    // 페이지 로드 시 초기화
    $(document).ready(function() {
        initializeInterstitialAd();
        optimizeAdVisibility();
        trackAdPerformance();
    });
    
    /**
     * 전면 광고 초기화
     */
    function initializeInterstitialAd() {
        const interstitialOverlay = $('#interstitial-overlay');
        
        if (interstitialOverlay.length === 0) {
            return;
        }
        
        // 링크 클릭 시 전면 광고 표시
        $('a').on('click', function(e) {
            const href = $(this).attr('href');
            
            // 외부 링크나 특정 링크는 제외
            if (!href || href.indexOf('#') === 0 || href.indexOf('javascript:') === 0) {
                return;
            }
            
            // 시간 체크
            const currentTime = Date.now();
            if (currentTime - lastInterstitialTime < INTERSTITIAL_INTERVAL) {
                return; // 아직 1분이 지나지 않음
            }
            
            // 전면 광고 표시
            e.preventDefault();
            showInterstitialAd(href);
            lastInterstitialTime = currentTime;
        });
    }
    
    /**
     * 전면 광고 표시
     */
    function showInterstitialAd(targetUrl) {
        const interstitialOverlay = $('#interstitial-overlay');
        interstitialOverlay.addClass('active');
        
        // 5초 후 자동으로 닫고 페이지 이동
        setTimeout(function() {
            closeInterstitial();
            window.location.href = targetUrl;
        }, 5000);
    }
    
    /**
     * 전면 광고 닫기
     */
    window.closeInterstitial = function() {
        $('#interstitial-overlay').removeClass('active');
    };
    
    /**
     * 광고 가시성 최적화
     * 타뷸라 스타일의 CTR 극대화 전략
     */
    function optimizeAdVisibility() {
        // 네이티브 광고에 호버 효과 추가
        $('.native-ad-container').hover(
            function() {
                $(this).css({
                    'transform': 'scale(1.02)',
                    'box-shadow': '0 8px 25px rgba(0,0,0,0.15)',
                    'transition': 'all 0.3s ease'
                });
            },
            function() {
                $(this).css({
                    'transform': 'scale(1)',
                    'box-shadow': '0 4px 15px rgba(0,0,0,0.08)'
                });
            }
        );
        
        // 썸네일 광고 최적화
        $('.post-thumbnail-ad').each(function() {
            const $ad = $(this);
            
            // 뷰포트에 들어올 때 애니메이션
            observeAdVisibility($ad);
        });
        
        // 앵커 광고 스크롤 시 표시/숨김
        let anchorAdVisible = true;
        let lastScrollTop = 0;
        
        $(window).scroll(function() {
            const scrollTop = $(this).scrollTop();
            const $anchorAd = $('.anchor-ad');
            
            if ($anchorAd.length === 0) {
                return;
            }
            
            // 스크롤 다운 시 표시, 스크롤 업 시 숨김 (사용자 경험 개선)
            if (scrollTop > lastScrollTop && scrollTop > 300) {
                // 스크롤 다운
                if (!anchorAdVisible) {
                    $anchorAd.slideDown(300);
                    anchorAdVisible = true;
                }
            } else {
                // 스크롤 업 또는 상단 근처
                if (scrollTop < 100 && anchorAdVisible) {
                    $anchorAd.slideUp(300);
                    anchorAdVisible = false;
                }
            }
            
            lastScrollTop = scrollTop;
        });
    }
    
    /**
     * Intersection Observer를 사용한 광고 가시성 추적
     */
    function observeAdVisibility(adElement) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        // 뷰포트에 들어옴
                        $(entry.target).css({
                            'opacity': '0',
                            'transform': 'translateY(20px)'
                        }).animate({
                            opacity: 1
                        }, 600).css({
                            'transform': 'translateY(0)',
                            'transition': 'transform 0.6s ease'
                        });
                        
                        // 한 번만 실행
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            
            observer.observe(adElement[0]);
        }
    }
    
    /**
     * 광고 성능 추적
     */
    function trackAdPerformance() {
        // 클릭 추적
        $('.native-ad-container, .post-thumbnail-ad, .anchor-ad').on('click', function() {
            const adType = $(this).attr('class').split(' ')[0];
            
            // 콘솔에 로그 (실제로는 서버나 분석 도구로 전송)
            console.log('Ad Clicked:', {
                type: adType,
                timestamp: new Date().toISOString(),
                page: window.location.href
            });
            
            // Google Analytics가 있다면 이벤트 전송
            if (typeof gtag !== 'undefined') {
                gtag('event', 'ad_click', {
                    'event_category': 'Ads',
                    'event_label': adType,
                    'value': 1
                });
            }
        });
        
        // 뷰 추적
        trackAdViews();
    }
    
    /**
     * 광고 노출 추적
     */
    function trackAdViews() {
        if ('IntersectionObserver' in window) {
            const viewObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const adType = $(entry.target).attr('class').split(' ')[0];
                        
                        console.log('Ad Viewed:', {
                            type: adType,
                            timestamp: new Date().toISOString(),
                            page: window.location.href
                        });
                        
                        // Google Analytics 이벤트
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'ad_impression', {
                                'event_category': 'Ads',
                                'event_label': adType,
                                'value': 1
                            });
                        }
                        
                        // 한 번만 추적
                        viewObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5 // 50% 이상 보일 때
            });
            
            // 모든 광고 요소 관찰
            $('.native-ad-container, .post-thumbnail-ad, .anchor-ad').each(function() {
                viewObserver.observe(this);
            });
        }
    }
    
    /**
     * 부드러운 스크롤
     */
    $('a[href*="#"]').on('click', function(e) {
        const target = $(this.hash);
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    /**
     * 읽기 시간 계산 및 표시
     */
    function calculateReadingTime() {
        const content = $('.entry-content').text();
        const wordCount = content.split(/\s+/).length;
        const readingTime = Math.ceil(wordCount / 200); // 분당 200단어 기준
        
        if (readingTime > 0 && $('.entry-meta').length) {
            $('.entry-meta').append(' • 읽는 시간: 약 ' + readingTime + '분');
        }
    }
    
    // 싱글 포스트에서 읽기 시간 표시
    if ($('body').hasClass('single-post') || $('body').hasClass('single')) {
        calculateReadingTime();
    }
    
    /**
     * 이미지 지연 로딩 (성능 최적화)
     */
    if ('loading' in HTMLImageElement.prototype) {
        // 네이티브 지연 로딩 지원
        $('img').attr('loading', 'lazy');
    } else {
        // Intersection Observer 폴백
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            $('img').each(function() {
                imageObserver.observe(this);
            });
        }
    }
    
    /**
     * CTR 극대화를 위한 A/B 테스트 시뮬레이션
     */
    function runAdOptimization() {
        // 랜덤하게 광고 스타일 변경 (A/B 테스트)
        const adVariant = Math.random() > 0.5 ? 'variant-a' : 'variant-b';
        
        $('.native-ad-container').addClass(adVariant);
        
        // Variant A: 기본 스타일
        // Variant B: 더 강조된 스타일
        if (adVariant === 'variant-b') {
            $('.native-ad-container').css({
                'border': '2px solid #667eea',
                'background': 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)'
            });
        }
    }
    
    // 광고 최적화 실행
    runAdOptimization();
    
    /**
     * 모바일 메뉴 토글 (필요시)
     */
    $('.menu-toggle').on('click', function() {
        $('.main-navigation').slideToggle(300);
    });
    
    /**
     * 스크롤 진행 표시기
     */
    if ($('body').hasClass('single-post') || $('body').hasClass('single')) {
        $('body').prepend('<div class="reading-progress"><div class="progress-bar"></div></div>');
        
        $(window).scroll(function() {
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            const scrollTop = $(window).scrollTop();
            const progress = (scrollTop / (documentHeight - windowHeight)) * 100;
            
            $('.progress-bar').css('width', progress + '%');
        });
        
        // 진행 표시기 스타일
        $('<style>')
            .text('.reading-progress{position:fixed;top:0;left:0;width:100%;height:4px;background:#e0e0e0;z-index:9999;}.progress-bar{height:100%;background:linear-gradient(90deg,#667eea,#764ba2);transition:width 0.1s;}')
            .appendTo('head');
    }
    
    /**
     * 공유 버튼 기능 (소셜 공유)
     */
    window.shareContent = function(platform) {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent(document.title);
        let shareUrl = '';
        
        switch(platform) {
            case 'facebook':
                shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
                break;
            case 'twitter':
                shareUrl = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title;
                break;
            case 'linkedin':
                shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + url;
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    };
    
})(jQuery);
