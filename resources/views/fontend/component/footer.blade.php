<footer
    style="background-color: #f8f9fa; padding: 30px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div class="container" style="max-width: 1140px; margin: 0 auto;">

        <!-- Logo và hotline -->
        <!-- Thông tin liên hệ và giới thiệu -->
        <div style="margin-bottom: 40px;">
            <!-- Logo và hotline -->
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <a href="#" style="display: inline-block; background: #e9ecef; padding: 10px; border-radius: 8px;">
                    <img src="{{ asset('fontend/assets/img/100X100/img6.png') }}" alt="Logo Electro" height="60"
                        style="display: block;">
                </a>
                <div>
                    <p style="margin: 0; font-size: 14px; color: #6c757d;">Có gì thắc mắc? Liên hệ 24/7!</p>
                    <p style="margin: 0; font-size: 16px; font-weight: 600; color: #007bff; line-height: 1.1;">
                        <span style="color: #007bff;">0358 623 4256</span> &nbsp;&nbsp;|&nbsp;&nbsp;
                        <span style="color: #007bff;">0956 342 1233</span>
                    </p>

                </div>
            </div>

            <!-- 2 cột trên -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px;">
                <!-- Chính sách bảo hành -->
                <div>
                    <h5
                        style="font-size: 18px; font-weight: 700; color: #212529; margin-bottom: 15px; text-transform: uppercase;">
                        Chính sách bảo hành</h5>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; color: #495057; font-size: 15px; line-height: 1.6;">
                        <li>Bảo hành 12 tháng chính hãng cho tất cả sản phẩm.</li>
                        <li>Đổi mới trong 7 ngày nếu có lỗi kỹ thuật từ nhà sản xuất.</li>
                        <li>Hỗ trợ kỹ thuật và bảo trì 24/7 qua điện thoại và email.</li>
                        <li>Miễn phí kiểm tra và vệ sinh định kỳ sản phẩm.</li>
                    </ul>
                </div>

                <!-- Thông tin công ty -->
                <div>
                    <h5
                        style="font-size: 18px; font-weight: 700; color: #212529; margin-bottom: 15px; text-transform: uppercase;">
                        Thông tin công ty ComputerGear</h5>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; color: #495057; font-size: 15px; line-height: 1.6;">
                        <li>ComputerGear - Nhà cung cấp máy tính uy tín .</li>
                        <li>Địa chỉ: 123 Đường Cầu Giấy, TP.Hà Nội</li>
                        <li>Email: doduy@computergear.vn</li>
                        <li>Điện thoại: 0358 623 4256 - 0956 342 1233</li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Các cột bên dưới: 2 hàng, 3 cột mỗi hàng -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 30px;">

            <!-- Sản phẩm máy tính -->
            <div>
                <h5
                    style="font-size: 16px; font-weight: 700; color: #212529; margin-bottom: 15px; text-transform: uppercase;">
                    Sản phẩm máy tính</h5>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                    <li><a href="{{ route('client.products.index', ['category' => 'laptop']) }}"
                            style="color: #212529; text-decoration: none;">Laptop & Máy tính xách tay</a></li>
                    <li><a href="{{ route('client.products.index', ['category' => 'desktop']) }}"
                            style="color: #212529; text-decoration: none;">Máy tính để bàn (Desktop)</a></li>
                    <li><a href="{{ route('client.products.index', ['category' => 'gaming']) }}"
                            style="color: #212529; text-decoration: none;">Máy tính chơi game</a></li>
                    <li><a href="{{ route('client.products.index', ['category' => 'all-in-one']) }}"
                            style="color: #212529; text-decoration: none;">Máy tính All-in-One(Nhiều chức năng)</a></li>
                    <li><a href="{{ route('client.products.index', ['category' => 'workstation']) }}"
                            style="color: #212529; text-decoration: none;">Máy tính hiệu năng cao</a></li>
                </ul>
            </div>

            <!-- Thiết bị phụ kiện -->
            <div>
                <h5
                    style="font-size: 16px; font-weight: 700; color: #212529; margin-bottom: 15px; text-transform: uppercase;">
                    Thiết bị - Phụ kiện</h5>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                    <li><a href="{{ route('client.products.index', ['category' => 'components']) }}"
                            style="color: #212529; text-decoration: none;">Linh kiện máy tính</a></li>
                    <li><a href="{{ route('client.products.index', ['category' => 'accessories']) }}"
                            style="color: #212529; text-decoration: none;">Phụ kiện</a></li>
                </ul>
            </div>

            <!-- Chăm sóc khách hàng -->
            <div>
                <h5
                    style="font-size: 16px; font-weight: 700; color: #212529; margin-bottom: 15px; text-transform: uppercase;">
                    Chăm sóc khách hàng</h5>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                    <li><a href="{{ route('user.show') }}" style="color: #212529; text-decoration: none;">Tài khoản của
                            tôi</a></li>
                    <li><a href="{{ route('order.track') }}" style="color: #212529; text-decoration: none;">Theo dõi đơn
                            hàng</a></li>
                    <li><a href="{{ route('client.contacts.index') }}"
                            style="color: #212529; text-decoration: none;">Liên hệ & Hỗ trợ</a></li>
                    <li><a href="{{ route('faqs') }}" style="color: #212529; text-decoration: none;">Câu hỏi thường
                            gặp</a></li>
                </ul>
            </div>

        </div>

        <!-- Dòng cuối -->
        <div
            style="margin-top: 40px; border-top: 1px solid #dee2e6; padding-top: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <p style="margin: 0; color: #6c757d; font-size: 14px;">&copy; <span href="#"
                    style="color: #212529; font-weight: 700; text-decoration: none;">Electro</span> - All rights reserved.
            </p>
            <div>
                <img src="{{ asset('fontend/assets/img/100X60/img1.jpg') }}" alt="Payment 1"
                    style="height: 30px; margin-left: 10px;">
                <img src="{{ asset('fontend/assets/img/100X60/img2.jpg') }}" alt="Payment 2"
                    style="height: 30px; margin-left: 10px;">
                <img src="{{ asset('fontend/assets/img/100X60/img3.jpg') }}" alt="Payment 3"
                    style="height: 30px; margin-left: 10px;">
                <img src="{{ asset('fontend/assets/img/100X60/img4.jpg') }}" alt="Payment 4"
                    style="height: 30px; margin-left: 10px;">
                <img src="{{ asset('fontend/assets/img/100X60/img5.jpg') }}" alt="Payment 5"
                    style="height: 30px; margin-left: 10px;">
            </div>
        </div>
    </div>
</footer>
