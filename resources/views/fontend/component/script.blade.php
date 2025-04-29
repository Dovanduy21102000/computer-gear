<!-- Go to Top -->
<a class="js-go-to u-go-to" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed"
data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp" data-hide-effect="slideOutDown">
<span class="fas fa-arrow-up u-go-to__inner"></span>
</a>
<!-- End Go to Top -->

<!-- Nút mở chat -->
<button id="chat-toggle" class="btn btn-primary rounded-circle position-fixed"
style="bottom: 20px; right: 20px; z-index: 1050;">
💬
</button>

<!-- Hộp chat -->
<div id="chat-box" class="card shadow position-fixed"
style="width: 300px; bottom: 80px; right: 20px; display: none; z-index: 1050;">
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <span>Hỗ trợ khách hàng</span>
    <button id="close-chat" class="btn-close btn-close-white btn-sm"></button>
</div>
<div class="card-body p-2 overflow-auto" style="height: 300px;" id="chat-messages">
    <!-- Tin nhắn sẽ được hiển thị ở đây -->
</div>
<div class="card-footer p-2">
    <form id="chat-form" class="d-flex gap-2">
        <input type="text" id="chat-input" class="form-control" placeholder="Nhập tin nhắn..." required>
        <button class="btn btn-primary">Gửi</button>
    </form>
</div>
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

<!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<!-- Laravel Echo -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<!-- JS Plugins Init. -->
@php
use App\Models\User;
$admin = User::where('role', 'admin')->first();
@endphp
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

//Chat real time
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const closeBtn = document.getElementById('close-chat');
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');

    const receiverId = {{ $admin?->id ?? 'null' }};
    const userId = {{ auth()->id() ?? 'null' }};
    let echoInstance = null;
    let loadedChat = false;

    function appendMessage(message, who = 'me') {
        const div = document.createElement('div');
        div.className = who === 'me' ? 'text-end mb-2' : 'text-start mb-2';
        div.innerHTML =
            `<span class="badge bg-${who === 'me' ? 'primary' : 'secondary'}">${message}</span>`;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    toggle.onclick = async () => {
        @if (!auth()->check())
            Swal.fire({
                icon: 'warning',
                title: 'Bạn chưa đăng nhập',
                text: 'Vui lòng đăng nhập để sử dụng chức năng chat!',
                confirmButtonText: 'OK'
            });
            return;
        @endif

        chatBox.style.display = chatBox.style.display === 'none' ? 'block' : 'none';

        if (!loadedChat) {
            try {
                const res = await fetch(`/chat/messages/${receiverId}`);
                const messages = await res.json();
                messages.forEach(m => {
                    appendMessage(m.message, m.sender_id == userId ? 'me' : 'them');
                });
                loadedChat = true;
            } catch (error) {
                console.error('Lỗi tải tin nhắn:', error);
            }
        }

        if (!echoInstance && userId) {
            try {
                // Import Echo và Pusher từ CDN
                const {
                    default: Echo
                } = await import(
                    'https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js');
                const Pusher = await import('https://js.pusher.com/7.2/pusher.min.js');

                window.Pusher = Pusher;
                echoInstance = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ env('PUSHER_APP_KEY') }}',
                    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                    forceTLS: true
                });

                echoInstance.private(`chat.${userId}`)
                    .listen('MessageSent', (e) => {
                        appendMessage(e.message, 'them');
                    });

            } catch (err) {
                console.error('Lỗi khi khởi tạo Echo:', err);
            }
        }
    };

    closeBtn.onclick = () => chatBox.style.display = 'none';

    chatForm.onsubmit = async (e) => {
        e.preventDefault();
        const msg = chatInput.value;
        if (!msg.trim()) return;

        appendMessage(msg, 'me');

        try {
            await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: msg
                })
            });
            chatInput.value = '';
        } catch (error) {
            console.error('Lỗi khi gửi tin nhắn:', error);
        }
    };
});

function appendMessage(message, who = 'me') {
    const chatMessages = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = who === 'me' ? 'text-end mb-2' : 'text-start mb-2';
    div.innerHTML = `<span class="badge bg-${who === 'me' ? 'primary' : 'secondary'}">${message}</span>`;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
    $(document).ready(function () {
        $(document).on('click', '.wishlist-button', function (e) {
            e.preventDefault();
            var button = $(this);
            var productId = button.data('product-id');

            $.ajax({
                url: "{{ route('wishlist.store') }}",
                method: "POST",
                data: {
                    product_id: productId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        button.toggleClass('active');
                        var icon = button.find('i');
                        if (button.hasClass('active')) {
                            icon.removeClass('far').addClass('fas').css('color', 'red');
                        } else {
                            icon.removeClass('fas').addClass('far').css('color', '#333');
                        }
                        toastr.success(response.message);
                    } else {
                        toastr.error('Có lỗi xảy ra.');
                    }
                },
                error: function () {
                    toastr.error('Bạn cần đăng nhập để thêm vào yêu thích.');
                }
            });
        });
    });




</script>