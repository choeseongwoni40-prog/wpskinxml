/**
 * Revenue Master Theme Custom JavaScript
 * 수익 극대화를 위한 광고 관리 및 CTR 최적화
 */

(function($) {
    'use strict';

    // 전면 광고 관리
    const InterstitialAdManager = {
        lastShown: 0,
        minInterval: 60000, // 1분 (60초)
        overlay: null,
        enabled: typeof revenueInterstitial !== 'undefined' && revenueInterstitial.enabled,
        
        init: function() {
            if (!this.enabled) return;
            
            this.overlay = document.getElementById('interstitial-overlay');
            if (!this.overlay) return;
            
            // 마지막 표시 시간 로드
            const lastShownStr = localStorage.getItem('revenue_last_interstitial');
            if (lastShownStr) {
                this.lastShown = parseInt(lastShownStr);
            }
            
            // 페이지 전환 감지 (링크 클릭)
            this.attachLinkHandlers();
            
            // 페이지 로드 시 첫 광고 (조건 충족 시)
            this.checkAndShow();
        },
        
        attachLinkHandlers: function() {
            const self = this;
            
            // 내부 링크에만 적용
            $('a[href^="' + window.location.origin + '"]').on('click', function(e) {
                const href = $(this).attr('href');
                
                // 앵커 링크나 외부 링크 제외
                if (href.indexOf('#') === 0 || $(this).attr('target') === '_blank') {
                    return;
                }
                
                // 광고 표시 가능 여부 확인
                if (self.canShow()) {
                    e.preventDefault();
                    self.show(href);
                }
            });
        },
        
        canShow: function() {
            const now = Date.now();
            const timeSinceLastShown = now - this.lastShown;
            return timeSinceLastShown >= this.minInterval;
        },
        
        checkAndShow: function() {
            // 첫 페이지 로드 시에는 표시하지 않음 (UX 고려)
            // 세션에서 방문 횟수 확인
            const visitCount = parseInt(sessionStorage.getItem('revenue_visit_count') || '0');
            sessionStorage.setItem('revenue_visit_count', (visitCount + 1).toString());
            
            if (visitCount >= 2 && this.canShow()) {
                // 3번째 페이지부터 표시
                setTimeout(() => this.show(null), 1000);
            }
        },
        
        show: function(redirectUrl) {
            if (!this.overlay) return;
            
            // 광고 코드 삽입
            const adSlot = document.getElementById('interstitial-ad-slot');
            if (adSlot && revenueInterstitial.code) {
                adSlot.innerHTML = revenueInterstitial.code;
            }
            
            // 오버레이 표시
            this.overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // 마지막 표시 시간 저장
            this.lastShown = Date.now();
            localStorage.setItem('revenue_last_interstitial', this.lastShown.toString());
            
            // 리다이렉트 URL 저장
            if (redirectUrl) {
                this.overlay.setAttribute('data-redirect', redirectUrl);
            }
            
            // 자동 닫기 (5초 후)
            setTimeout(() => {
                if (this.overlay.style.display === 'flex') {
                    this.close();
                }
            }, 5000);
        },
        
        close: function() {
            if (!this.overlay) return;
            
            this.overlay.style.display = 'none';
            document.body.style.overflow = '';
            
            // 리다이렉트 처리
            const redirectUrl = this.overlay.getAttribute('data-redirect');
            if (redirectUrl) {
                this.overlay.removeAttribute('data-redirect');
                window.location.href = redirectUrl;
            }
        }
    };
    
    // 전역 닫기 함수
    window.closeInterstitial = function() {
        InterstitialAdManager.close();
    };
    
    // 타뷸라 스타일 광고 CTR 최적화 (썸네일 제거 버전)
    const TaboolaStyleOptimizer = {
        init: function() {
            this.addHoverEffects();
            this.trackClicks();
        },
        
        addHoverEffects: function() {
            // 호버 시 더욱 눈에 띄는 효과
            $('.taboola-ad-item, .recommended-item, .sidebar-ad-item').hover(
                function() {
                    $(this).css({
                        'box-shadow': '0 8px 25px rgba(52, 152, 219, 0.4)',
                        'border': '2px solid #3498db'
                    });
                },
                function() {
                    $(this).css({
                        'box-shadow': '',
                        'border': ''
                    });
                }
            );
        },
        
        trackClicks: function() {
            // 광고 클릭 추적
            $('.taboola-ad-item, .recommended-item, .sidebar-ad-item').on('click', function(e) {
                const hasAdCode = $(this).closest('.native-ad-container, .recommended-content').find('ins.adsbygoogle').length > 0;
                
                if (hasAdCode && typeof gtag !== 'undefined') {
                    gtag('event', 'ad_click', {
                        'event_category': 'advertising',
                        'event_label': 'taboola_style_ad'
                    });
                }
            });
        }
    };
    
    // CTR 최적화: 광고 가시성 추적
    const AdVisibilityTracker = {
        init: function() {
            this.trackNativeAds();
            this.addViewabilityTracking();
        },
        
        trackNativeAds: function() {
            const nativeAds = $('.native-ad-container, .recommended-content');
            
            if (!nativeAds.length) return;
            
            // Intersection Observer로 가시성 추적
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // 광고가 화면에 보임
                            $(entry.target).addClass('ad-visible');
                            
                            // 애널리틱스 이벤트
                            this.trackAdView(entry.target);
                        }
                    });
                }, {
                    threshold: 0.5 // 50% 이상 보일 때
                });
                
                nativeAds.each(function() {
                    observer.observe(this);
                });
            }
        },
        
        addViewabilityTracking: function() {
            // 광고가 3초 이상 화면에 보이면 추적
            const viewedAds = new Set();
            
            $('.native-ad-container, .recommended-content').each(function() {
                const adElement = this;
                let viewTimer = null;
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            viewTimer = setTimeout(() => {
                                if (!viewedAds.has(adElement)) {
                                    viewedAds.add(adElement);
                                    
                                    if (typeof gtag !== 'undefined') {
                                        gtag('event', 'ad_viewable', {
                                            'event_category': 'advertising',
                                            'event_label': 'taboola_style_viewable'
                                        });
                                    }
                                }
                            }, 3000);
                        } else {
                            clearTimeout(viewTimer);
                        }
                    });
                }, { threshold: 0.7 });
                
                observer.observe(adElement);
            });
        },
        
        trackAdView: function(adElement) {
            // 광고 노출 추적
            if (typeof gtag !== 'undefined') {
                gtag('event', 'ad_impression', {
                    'event_category': 'advertising',
                    'event_label': 'taboola_style_impression'
                });
            }
        }
    };
    
    // 스크롤 최적화: 부드러운 스크롤
    const SmoothScroll = {
        init: function() {
            $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(e) {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') 
                    && location.hostname === this.hostname) {
                    
                    const target = $(this.hash);
                    const targetElement = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    
                    if (targetElement.length) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: targetElement.offset().top - 80
                        }, 800);
                    }
                }
            });
        }
    };
    
    // 이미지 지연 로딩 (성능 최적화)
    const LazyLoad = {
        init: function() {
            if ('loading' in HTMLImageElement.prototype) {
                // 네이티브 lazy loading 지원
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                });
            } else {
                // Intersection Observer 폴백
                this.observerFallback();
            }
        },
        
        observerFallback: function() {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    };
    
    // 읽기 진행률 표시 (사용자 참여도 향상)
    const ReadingProgress = {
        init: function() {
            if (!$('body').hasClass('single')) return;
            
            // 진행률 바 추가
            $('body').prepend('<div id="reading-progress" style="position:fixed;top:0;left:0;width:0%;height:3px;background:#3498db;z-index:10000;transition:width 0.1s;"></div>');
            
            const progressBar = $('#reading-progress');
            
            $(window).on('scroll', function() {
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                const scrollTop = $(window).scrollTop();
                
                const progress = (scrollTop / (documentHeight - windowHeight)) * 100;
                progressBar.css('width', progress + '%');
            });
        }
    };
    
    // 초기화
    $(document).ready(function() {
        InterstitialAdManager.init();
        TaboolaStyleOptimizer.init();
        AdVisibilityTracker.init();
        SmoothScroll.init();
        LazyLoad.init();
        ReadingProgress.init();
        
        // 모바일 메뉴 토글 (필요시)
        $('.menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('active');
        });
        
        console.log('✅ Revenue Master Theme Loaded - 타뷸라 스타일 CTR 극대화 모드');
    });
    
})(jQuery);
