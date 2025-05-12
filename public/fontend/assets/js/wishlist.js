document.addEventListener('DOMContentLoaded', function () {
    const wishlistToggles = document.querySelectorAll('.wishlist-toggle');

    wishlistToggles.forEach(function (wishlistToggle) {
        const wishlistIcon = wishlistToggle.querySelector('.wishlist-icon');

        wishlistToggle.addEventListener('click', function (e) {
            e.preventDefault();

            const productId = wishlistToggle.getAttribute('data-product-id');

            fetch('/wishlist/' + productId + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    wishlistIcon.classList.add('active');
                } else if (data.status === 'removed') {
                    wishlistIcon.classList.remove('active');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    const removeButtons = document.querySelectorAll('.remove-wishlist-item');

    removeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            if (!confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')) return;

            const itemId = this.getAttribute('data-id');

            fetch(`/wishlist/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`#wishlist-item-${itemId}`).remove(); // Xoá dòng
                        alert('Đã xoá khỏi danh sách yêu thích');
                    } else {
                        alert('Không thể xoá. Vui lòng thử lại!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                });
        });
    });
});
