/**
 * Revenue Maximizer Pro - Custom JavaScript
 * 광고 CTR 극대화 및 사용자 경험 최적화
 */

(function($) {
    'use strict';
    
    // 전면 광고 관리
    var InterstitialAd = {
        lastShown: 0,
        interval: 60000, // 1분 (60,000ms)
        isShowing: false,
        
        init: function() {
            if (!adSettings.interstitialCode) {
                return;
            }
            
            // 페이지 로드 시
            this.setupListeners();
            
            // 첫 방문이면 30초 후에 표시
            setTimeout(function() {
                InterstitialAd.show();
            }, 30000);
        },
        
        setupListeners: function() {
            var self = this;
            
            // 내부 링크 클릭 시
            $('a').on('click', function(e) {
                var href = $(this).attr('href');
                
                // 외부 링크는 제외
                if (!href || href.indexOf('#') === 0 || href.indexOf('http') === 0 && href.indexOf(window.location.hostname) === -1) {
                    return;
                }
                
                var now = Date.now();
                var timeSinceLastAd = now - self.lastShown;
                
                // 1분이 지났으면 광고 표시
                if (timeSinceLastAd >= self.interval && !self.isShowing) {
                    e.preventDefault();
                    var targetUrl = href;
                    
                    self.show(function() {
                        window.location.href = targetUrl;
                    });
                }
            });
        },
        
        show: function(callback) {
            if (this.isShowing || !adSettings.interstitialCode) {
                if (callback) callback();
                return;
            }
            
            this.isShowing = true;
            this.lastShown = Date.now();
            
            var $overlay = $('#interstitial-ad');
            var $content = $('#interstitial-ad-content');
            
            // 광고 코드 삽입
            $content.html(adSettings.interstitialCode);
            
            // 오버레이 표시
            $overlay.addClass('active');
            
            // 5초 후 자동 닫기
            var self = this;
            setTimeout(function() {
                self.close();
                if (callback) callback();
            }, 5000);
        },
        
        close: function() {
            $('#interstitial-ad').removeClass('active');
            this.isShowing = false;
        }
    };
    
    // 네이티브 광고 최적화 (타뷸라 스타일)
    var NativeAd = {
        init: function() {
            this.styleNativeAds();
            this.trackVisibility();
        },
        
        styleNativeAds: function() {
            $('.native-ad-container').each(function() {
                var $container = $(this);
                
                // 타뷸라 스타일 적용
                $container.css({
                    'background': '#fff',
                    'border': '1px solid #e0e0e0',
                    'border-radius': '8px',
                    'padding': '15px',
                    'margin': '25px 0',
                    'transition': 'all 0.3s ease'
                });
                
                // 호버 효과
                $container.hover(
                    function() {
                        $(this).css({
                            'box-shadow': '0 4px 12px rgba(0,0,0,0.1)',
                            'transform': 'translateY(-2px)'
                        });
                    },
                    function() {
                        $(this).css({
                            'box-shadow': 'none',
                            'transform': 'translateY(0)'
                        });
                    }
                );
            });
        },
        
        trackVisibility: function() {
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            // 광고가 보일 때 애니메이션
                            $(entry.target).css({
                                'opacity': '0',
                                'transform': 'translateY(20px)'
                            }).animate({
                                'opacity': '1',
                                'transform': 'translateY(0)'
                            }, 500);
                            
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                
                $('.native-ad-container').each(function() {
                    observer.observe(this);
                });
            }
        }
    };
    
    // 광고 컨테이너 최적화
    var AdContainer = {
        init: function() {
            this.optimizeAdContainers();
            this.lazyLoadAds();
        },
        
        optimizeAdContainers: function() {
            $('.ad-container').each(function() {
                var $container = $(this);
                
                // 레이블 스타일
                $container.find('.ad-label').css({
                    'font-size': '10px',
                    'color': '#999',
                    'text-transform': 'uppercase',
                    'letter-spacing': '1px',
                    'margin-bottom': '10px',
                    'text-align': 'center'
                });
                
                // 컨테이너 스타일
                $container.css({
                    'background': '#f8f9fa',
                    'border-radius': '8px',
                    'padding': '15px',
                    'text-align': 'center',
                    'min-height': '250px'
                });
            });
        },
        
        lazyLoadAds: function() {
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var $ad = $(entry.target);
                            
                            // 광고 로드 트리거
                            if ($ad.data('ad-loaded') !== 'true') {
                                $ad.data('ad-loaded', 'true');
                                
                                // 광고 스크립트가 있다면 실행
                                var scripts = $ad.find('script');
                                scripts.each(function() {
                                    if (this.src) {
                                        var script = document.createElement('script');
                                        script.src = this.src;
                                        document.head.appendChild(script);
                                    }
                                });
                            }
                            
                            observer.unobserve(entry.target);
                        }
                    });
                }, { rootMargin: '200px' });
                
                $('.ad-container, .native-ad-container').each(function() {
                    observer.observe(this);
                });
            }
        }
    };
    
    // CTR 향상을 위한 버튼 최적화
    var ButtonOptimizer = {
        init: function() {
            this.optimizeButtons();
            this.addHoverEffects();
        },
        
        optimizeButtons: function() {
            // 모든 링크를 버튼 스타일로
            $('a:not(nav a):not(.post-title a)').each(function() {
                var $link = $(this);
                
                if (!$link.hasClass('read-more') && !$link.closest('.post-card').length) {
                    $link.css({
                        'display': 'inline-block',
                        'padding': '10px 20px',
                        'background': '#3498db',
                        'color': '#fff',
                        'text-decoration': 'none',
                        'border-radius': '5px',
                        'transition': 'all 0.3s ease',
                        'margin': '5px'
                    });
                }
            });
        },
        
        addHoverEffects: function() {
            $('.read-more, a[class*="button"]').hover(
                function() {
                    $(this).css({
                        'transform': 'scale(1.05)',
                        'box-shadow': '0 4px 8px rgba(0,0,0,0.2)'
                    });
                },
                function() {
                    $(this).css({
                        'transform': 'scale(1)',
                        'box-shadow': 'none'
                    });
                }
            );
        }
    };
    
    // 스크롤 기반 광고 표시
    var ScrollAd = {
        init: function() {
            this.trackScroll();
        },
        
        trackScroll: function() {
            var scrollPercentage = 0;
            var adShown = false;
            
            $(window).on('scroll', function() {
                var scrollTop = $(window).scrollTop();
                var docHeight = $(document).height();
                var winHeight = $(window).height();
                
                scrollPercentage = (scrollTop / (docHeight - winHeight)) * 100;
                
                // 50% 스크롤 시 추가 광고 표시 (한 번만)
                if (scrollPercentage > 50 && !adShown) {
                    adShown = true;
                    // 여기에 추가 광고 로직
                }
            });
        }
    };
    
    // 앵커 광고 최적화
    var AnchorAd = {
        init: function() {
            this.optimizeAnchor();
        },
        
        optimizeAnchor: function() {
            var $anchor = $('#anchor-ad');
            
            if ($anchor.length) {
                // 스크롤 시 부드럽게 나타남
                var lastScrollTop = 0;
                $(window).on('scroll', function() {
                    var scrollTop = $(window).scrollTop();
                    
                    if (scrollTop > 300) {
                        $anchor.css({
                            'opacity': '1',
                            'transform': 'translateY(0)'
                        });
                    } else {
                        $anchor.css({
                            'opacity': '0',
                            'transform': 'translateY(100%)'
                        });
                    }
                    
                    lastScrollTop = scrollTop;
                });
            }
        }
    };
    
    // 전면 광고 닫기 함수 (글로벌)
    window.closeInterstitial = function() {
        InterstitialAd.close();
    };
    
    // 문서 준비 완료 시 초기화
    $(document).ready(function() {
        InterstitialAd.init();
        NativeAd.init();
        AdContainer.init();
        ButtonOptimizer.init();
        ScrollAd.init();
        AnchorAd.init();
        
        // 이미지 레이지 로딩
        if ('loading' in HTMLImageElement.prototype) {
            $('img').attr('loading', 'lazy');
        }
        
        // 외부 링크는 새 탭에서
        $('a[href^="http"]').not('[href*="' + window.location.hostname + '"]').attr({
            'target': '_blank',
            'rel': 'noopener noreferrer'
        });
        
        // 광고 클릭 추적 (선택사항)
        $('.ad-container, .native-ad-container').on('click', 'a', function() {
            // 여기에 추적 코드 추가 가능
            console.log('광고 클릭됨');
        });
    });
    
    // 모바일 최적화
    if (window.innerWidth < 768) {
        // 모바일에서 광고 크기 조정
        $('.ad-container').css({
            'padding': '10px',
            'min-height': '200px'
        });
        
        // 전면 광고 간격을 조금 더 늘림
        InterstitialAd.interval = 90000; // 1.5분
    }
    
})(jQuery);
