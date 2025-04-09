        <!-- Go to Top -->
        <a class="js-go-to u-go-to" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed"
            data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp" data-hide-effect="slideOutDown">
            <span class="fas fa-arrow-up u-go-to__inner"></span>
        </a>
        <!-- End Go to Top -->
        <!-- Nút mở chat -->
        <div id="chat-toggle"
            class="fixed bottom-4 right-4 z-50 cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition">
            Chat với chúng tôi 💬
        </div>

        <!-- Khung chat nổi -->
        <div id="chat-box"
            class="fixed bottom-20 right-4 z-50 w-80 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
            <div class="flex justify-between items-center bg-blue-600 text-white px-4 py-2 rounded-t-lg">
                <span>Hỗ trợ khách hàng</span>
                <button id="close-chat" class="text-white hover:text-red-200">✖</button>
            </div>
            <div id="chat-messages" class="h-64 overflow-y-auto p-3 space-y-2 text-sm">
                <!-- Tin nhắn hiển thị tại đây -->
            </div>
            <form id="chat-form" class="p-3 border-t flex gap-2">
                <input type="text" id="chat-input" class="w-full border rounded px-2 py-1 text-sm"
                    placeholder="Nhập tin nhắn..." required>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Gửi</button>
            </form>
        </div>

        <!-- JS Global Compulsory -->
        <script src="{{ asset('fontend/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>

        <script src="{{ asset('fontend/assets/vendor/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/bootstrap/bootstrap.min.js') }}"></script>

        <!-- JS Implementing Plugins -->
        <script src="{{ asset('fontend/assets/vendor/appear.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/hs-megamenu/src/hs.megamenu.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/svg-injector/dist/svg-injector.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}">
        </script>
        <script src="{{ asset('fontend/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/fancybox/jquery.fancybox.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/typed.js/lib/typed.min.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/slick-carousel/slick/slick.js') }}"></script>
        <script src="{{ asset('fontend/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <!-- JS Electro -->
        <script src="{{ asset('fontend/assets/js/hs.core.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.countdown.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.header.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.hamburgers.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.unfold.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.focus-state.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.malihu-scrollbar.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.validation.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.fancybox.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.onscroll-animation.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.slick-carousel.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.show-animation.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.svg-injector.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.go-to.js') }}"></script>
        <script src="{{ asset('fontend/assets/js/components/hs.selectpicker.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <!-- Thêm thư viện jQuery Bar Rating -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-bar-rating/dist/themes/fontawesome-stars.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery-bar-rating/dist/jquery.barrating.min.js"></script>

        <!-- JS Plugins Init. -->
        <script>
            $(window).on('load', function() {
                // initialization of HSMegaMenu component
                $('.js-mega-menu').HSMegaMenu({
                    event: 'hover',
                    direction: 'horizontal',
                    pageContainer: $('.container'),
                    breakpoint: 767.98,
                    hideTimeOut: 0
                });
            });

            $(document).on('ready', function() {
                // initialization of header
                $.HSCore.components.HSHeader.init($('#header'));

                // initialization of animation
                $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    afterOpen: function() {
                        $(this).find('input[type="search"]').focus();
                    }
                });

                // initialization of popups
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of countdowns
                var countdowns = $.HSCore.components.HSCountdown.init('.js-countdown', {
                    yearsElSelector: '.js-cd-years',
                    monthsElSelector: '.js-cd-months',
                    daysElSelector: '.js-cd-days',
                    hoursElSelector: '.js-cd-hours',
                    minutesElSelector: '.js-cd-minutes',
                    secondsElSelector: '.js-cd-seconds'
                });

                // initialization of malihu scrollbar
                $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

                // initialization of forms
                $.HSCore.components.HSFocusState.init();

                // initialization of form validation
                $.HSCore.components.HSValidation.init('.js-validate', {
                    rules: {
                        confirmPassword: {
                            equalTo: '#signupPassword'
                        }
                    }
                });

                // initialization of show animations
                $.HSCore.components.HSShowAnimation.init('.js-animation-link');

                // initialization of fancybox
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of slick carousel
                $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

                // initialization of go to
                $.HSCore.components.HSGoTo.init('.js-go-to');

                // initialization of hamburgers
                $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    beforeClose: function() {
                        $('#hamburgerTrigger').removeClass('is-active');
                    },
                    afterClose: function() {
                        $('#headerSidebarList .collapse.show').collapse('hide');
                    }
                });

                $('#headerSidebarList [data-toggle="collapse"]').on('click', function(e) {
                    e.preventDefault();

                    var target = $(this).data('target');

                    if ($(this).attr('aria-expanded') === "true") {
                        $(target).collapse('hide');
                    } else {
                        $(target).collapse('show');
                    }
                });

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

                // initialization of select picker
                $.HSCore.components.HSSelectPicker.init('.js-select');
            });

            $(document).ready(function() {
                $(document).on('click', '.js-plus', function() {
                    let $input = $(this).closest('.js-quantity').find('.js-result');
                    let value = parseInt($input.val(), 10) || 0;
                    $input.val(value + 1);
                });

                $(document).on('click', '.js-minus', function() {
                    let $input = $(this).closest('.js-quantity').find('.js-result');
                    let value = parseInt($input.val(), 10) || 1;
                    if (value > 1) {
                        $input.val(value - 1);
                    }
                });
            });
        </script>
