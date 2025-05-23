<!-- Go to Top -->
<a class="js-go-to u-go-to" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed"
    data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp" data-hide-effect="slideOutDown">
    <span class="fas fa-arrow-up u-go-to__inner"></span>
</a>
<!-- End Go to Top -->

<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();

    // Thiết lập thông tin visitor trước khi load widget
    @if (Auth::check())
        Tawk_API.visitor = {
            name: "{{ Auth::user()->name }}",
            email: "{{ Auth::user()->email }}"
        };

        // Callback khi widget load xong
        Tawk_API.onLoad = function() {
            Tawk_API.setAttributes({
                'name': "{{ Auth::user()->name }}",
                'email': "{{ Auth::user()->email }}",
                'hash': "{{ Auth::user()->id }}"
            });
            console.log('Chat đã thiết lập cho user: {{ Auth::user()->name }}');
        };
    @else
        Tawk_API.visitor = {
            name: "Người dùng ẩn danh",
            email: "anonymous@guest.com"
        };

        // Callback khi widget load xong
        Tawk_API.onLoad = function() {
            Tawk_API.setAttributes({
                'name': 'Ẩn danh',
                'email': 'anonymous@guest.com'
            });
            console.log('Chat đã thiết lập cho người dùng ẩn danh');
        };
    @endif

    // Load tawk.to widget
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/68308b588169ba190d611e09/1iruq0l0h';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>


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
<script
    src="{{ asset('fontend/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}">
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

{{-- <!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script> --}}

<!-- JS Plugins Init. -->
{{-- @php
    use App\Models\User;
    $admin = User::where('role', 'admin')->first();
@endphp --}}

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

    // //Chat real time
    // document.addEventListener('DOMContentLoaded', () => {
    //     const toggle = document.getElementById('chat-toggle');
    //     const chatBox = document.getElementById('chat-box');
    //     const closeBtn = document.getElementById('close-chat');
    //     const chatMessages = document.getElementById('chat-messages');
    //     const chatForm = document.getElementById('chat-form');
    //     const chatInput = document.getElementById('chat-input');

    //     const receiverId = {{ $admin?->id ?? 'null' }};
    //     const userId = {{ auth()->id() ?? 'null' }};
    //     let echoInstance = null;
    //     let loadedChat = false;

    //     function appendMessage(message, who = 'me') {
    //         const div = document.createElement('div');
    //         div.className = who === 'me' ? 'text-end mb-2' : 'text-start mb-2';
    //         div.innerHTML =
    //             `<span class="badge bg-${who === 'me' ? 'primary' : 'secondary'}">${message}</span>`;
    //         chatMessages.appendChild(div);
    //         chatMessages.scrollTop = chatMessages.scrollHeight;
    //     }

    //     toggle.onclick = async () => {
    //         @if (!auth()->check())
    //             Swal.fire({
    //                 icon: 'warning',
    //                 title: 'Bạn chưa đăng nhập',
    //                 text: 'Vui lòng đăng nhập để sử dụng chức năng chat!',
    //                 confirmButtonText: 'OK'
    //             });
    //             return;
    //         @endif

    //         chatBox.style.display = chatBox.style.display === 'none' ? 'block' : 'none';

    //         if (!loadedChat) {
    //             try {
    //                 const res = await fetch(`/chat/messages/${receiverId}`);
    //                 const messages = await res.json();
    //                 messages.forEach(m => {
    //                     appendMessage(m.message, m.sender_id == userId ? 'me' : 'them');
    //                 });
    //                 loadedChat = true;
    //             } catch (error) {
    //                 console.error('Lỗi tải tin nhắn:', error);
    //             }
    //         }

    //         if (!window.Echo && userId) {
    //             try {
    //                 window.Pusher = Pusher;

    //                 window.Echo = new Echo({
    //                     broadcaster: 'pusher',
    //                     key: '{{ env('PUSHER_APP_KEY') }}',
    //                     cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    //                     forceTLS: true,
    //                 });

    //                 window.Echo.connector.pusher.connection.bind('connected', () => {
    //                     console.log('✅ Đã kết nối thành công đến Pusher');
    //                 });

    //                 window.Echo.private(`chat.${userId}`)
    //                     .listen('MessageSent', (e) => {
    //                         console.log('📨 Nhận được tin nhắn:', e.message);
    //                         appendMessage(e.message.message, 'them');
    //                     });

    //             } catch (err) {
    //                 console.error('❌ Lỗi khi khởi tạo Echo:', err);
    //             }
    //         }

    //     };

    //     closeBtn.onclick = () => chatBox.style.display = 'none';

    //     chatForm.onsubmit = async (e) => {
    //         e.preventDefault();
    //         const msg = chatInput.value;
    //         if (!msg.trim()) return;

    //         appendMessage(msg, 'me');

    //         try {
    //             const response = await fetch('/chat/send', {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json',
    //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                 },
    //                 body: JSON.stringify({
    //                     receiver_id: receiverId,
    //                     message: msg
    //                 })
    //             });

    //             const result = await response.json();
    //             console.log('Server response:', result);
    //             chatInput.value = '';
    //         } catch (error) {
    //             console.error('Lỗi khi gửi tin nhắn:', error);
    //         }
    //     };
    // });

    // function appendMessage(message, who = 'me') {
    //     const chatMessages = document.getElementById('chat-messages');
    //     const div = document.createElement('div');
    //     div.className = who === 'me' ? 'text-end mb-2' : 'text-start mb-2';
    //     div.innerHTML = `<span class="badge bg-${who === 'me' ? 'primary' : 'secondary'}">${message}</span>`;
    //     chatMessages.appendChild(div);
    //     chatMessages.scrollTop = chatMessages.scrollHeight;
    // }
</script>
