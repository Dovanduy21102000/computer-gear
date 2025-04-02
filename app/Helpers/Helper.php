<?php

namespace App\Helpers;

class Helper
{
    // Hàm lấy đường dẫn ảnh avatar
    public static function get_image_avatar_url($avatar_image)
    {
        return asset('storage/' . $avatar_image); 
    }
}
