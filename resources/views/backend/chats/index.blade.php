<main id="main" class="main">
    <div class="row">
        <!-- Danh sách người dùng -->
        <div class="col-md-4 border-end" style="height: 80vh; overflow-y: auto;">
            <h5 class="p-3">Danh sách người dùng</h5>
            <ul class="list-group" id="userList">
                <!-- AJAX sẽ load danh sách user -->
            </ul>
        </div>

        <!-- Khung chat -->
        <div class="col-md-8 d-flex flex-column" style="height: 80vh;">
            <div class="border-bottom p-3 bg-light">
                <strong id="chatWith">Chọn người dùng để chat</strong>
            </div>

            <div class="flex-grow-1 overflow-auto p-3" id="chatBox" style="background: #f9f9f9;">
                <!-- Tin nhắn sẽ hiển thị ở đây -->
            </div>

            <div class="border-top p-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="messageInput" placeholder="Nhập tin nhắn...">
                    <button class="btn btn-primary" id="sendBtn">Gửi</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    let currentUserId = null;
    let echoInstance = null;

    // Load danh sách user đã nhắn với admin
    function loadUserList() {
        fetch('/admin/chat/users') // Route trả danh sách user
            .then(res => res.json())
            .then(users => {
                const list = document.getElementById('userList');
                list.innerHTML = '';
                users.forEach(u => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = u.name;
                    li.onclick = () => loadChat(u.id, u.name);
                    list.appendChild(li);
                });
            });
    }

    // Load chat với người dùng cụ thể
    function loadChat(userId, userName) {
        currentUserId = userId;
        document.getElementById('chatWith').textContent = `Đang chat với: ${userName}`;
        document.getElementById('chatBox').innerHTML = '';

        fetch(`/admin/chat/messages/${userId}`)
            .then(res => res.json())
            .then(messages => {
                messages.forEach(m => {
                    appendMessage(m.message, m.sender_id == userId ? 'them' : 'me');
                });
            });

        setupEcho(userId);
    }

    // Hiển thị tin nhắn
    function appendMessage(message, type = 'me') {
        const div = document.createElement('div');
        div.className = `text-${type == 'me' ? 'end' : 'start'} mb-2`;
        div.innerHTML = `<span class="badge bg-${type == 'me' ? 'primary' : 'secondary'}">${message}</span>`;
        document.getElementById('chatBox').appendChild(div);
    }

    // Gửi tin nhắn
    document.getElementById('sendBtn').onclick = async () => {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        if (!message || !currentUserId) return;

        const res = await fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receiver_id: currentUserId,
                message: message
            })
        });

        if (res.ok) {
            appendMessage(message, 'me');
            input.value = ''; // Xóa nội dung input sau khi gửi
        } else {
            alert('Không thể gửi tin nhắn. Hãy thử lại!');
        }
    };


    async function setupEcho(userId) {
        if (!echoInstance) {
            // Kiểm tra và chắc chắn rằng Echo được import đúng cách
            const {
                default: Echo
            } = await import('https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js');
            const Pusher = await import('https://js.pusher.com/7.2/pusher.min.js');
            window.Pusher = Pusher;

            echoInstance = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true,
            });
        }

        echoInstance.private(`chat-admin.${userId}`)
            .listen('MessageSent', (e) => {
                if (e.message.sender_id == currentUserId) {
                    appendMessage(e.message.message, 'them');
                }
            });
    }
    loadUserList();
</script>
