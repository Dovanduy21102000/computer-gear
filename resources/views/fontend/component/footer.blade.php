<footer style="background-color: #f8f9fa; padding: 40px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #495057;">
  <div class="container" style="max-width: 1140px; margin: 0 auto;">

    <!-- Logo và hotline -->
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 20px; margin-bottom: 50px;">
      <a href="#" style="display: inline-block; background: #e9ecef; padding: 12px; border-radius: 10px; transition: background-color 0.3s;">
        <img src="{{ asset('fontend/assets/img/100X100/img6.png') }}" alt="Logo Electro" height="80" style="display: block;">
      </a>
      <div style="min-width: 220px;">
        <p style="margin: 0; font-size: 14px; color: #6c757d;">Có gì thắc mắc? Liên hệ 24/7!</p>
        <p style="margin: 5px 0 0; font-size: 18px; font-weight: 700; color: #007bff; line-height: 1.2;">
          <a href="tel:03586234256" style="color: #007bff; text-decoration: none; margin-right: 15px;">0358 623 4256</a>
          <span style="color: #007bff;">|</span>
          <a href="tel:09563421233" style="color: #007bff; text-decoration: none; margin-left: 15px;">0956 342 1233</a>
        </p>
      </div>
    </div>

    <!-- Các cột thông tin -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">

      <!-- Sản phẩm máy tính -->
      <div>
        <h5 style="font-size: 18px; font-weight: 700; color: #212529; margin-bottom: 20px; text-transform: uppercase; border-bottom: 2px solid #007bff; padding-bottom: 5px;">
          Sản phẩm máy tính
        </h5>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 15px; color: #495057;">
          @foreach ($categories as $category)
          <li style="margin-bottom: 10px;">
            <a href="{{ route('client.products.index', ['category' => $category->slug]) }}"
               style="color: #212529; text-decoration: none; transition: color 0.3s;">
               {{ $category->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      <!-- Chăm sóc khách hàng -->
      <div>
        <h5 style="font-size: 18px; font-weight: 700; color: #212529; margin-bottom: 20px; text-transform: uppercase; border-bottom: 2px solid #007bff; padding-bottom: 5px;">
          Chăm sóc khách hàng
        </h5>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 15px; color: #495057;">
          <li style="margin-bottom: 10px;"><a href="{{ route('user.show') }}" style="color: #212529; text-decoration: none;">Tài khoản của tôi</a></li>
          {{-- <li style="margin-bottom: 10px;"><a href="{{ route('order.track') }}" style="color: #212529; text-decoration: none;">Theo dõi đơn hàng</a></li> --}}
          <li style="margin-bottom: 10px;"><a href="{{ route('client.contacts.index') }}" style="color: #212529; text-decoration: none;">Liên hệ & Hỗ trợ</a></li>
          <li><a href="{{ route('faqs') }}" style="color: #212529; text-decoration: none;">Câu hỏi thường gặp</a></li>
        </ul>
      </div>


    </div>

    <!-- Dòng cuối -->
    <div style="margin-top: 30px; border-top: 1px solid #dee2e6; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
      <p style="margin: 0; color: #6c757d; font-size: 14px;">&copy; <span style="color: #212529; font-weight: 700;">Electro</span> - All rights reserved.</p>
      <div style="display: flex; gap: 15px;">
        <img src="{{ asset('fontend/assets/img/100X60/img1.jpg') }}" alt="Payment 1" style="height: 30px;">
        <img src="{{ asset('fontend/assets/img/100X60/img2.jpg') }}" alt="Payment 2" style="height: 30px;">
        <img src="{{ asset('fontend/assets/img/100X60/img3.jpg') }}" alt="Payment 3" style="height: 30px;">
        <img src="{{ asset('fontend/assets/img/100X60/img4.jpg') }}" alt="Payment 4" style="height: 30px;">
        <img src="{{ asset('fontend/assets/img/100X60/img5.jpg') }}" alt="Payment 5" style="height: 30px;">
      </div>
    </div>
  </div>

  <style>
    footer a:hover {
      color: #0056b3 !important;
      text-decoration: underline;
    }
  </style>
</footer>
