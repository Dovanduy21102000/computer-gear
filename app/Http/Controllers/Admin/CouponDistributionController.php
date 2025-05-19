<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponDistributionController extends BaseCRUDController
{
    public $pathView = 'backend.coupon_distribution.';
    public $model = Coupon::class;
    public $fieldImage = null;
    public $folderImage;
    public $urlBase = 'coupon-distribution.';
    public $titleIndex = 'Quản lý phân phối mã giảm giá';
    public $titleCreate = 'Tạo mã giảm giá mới';
    public $titleEdit = 'Chỉnh sửa mã giảm giá';
    public $titleShow = 'Chi tiết phân phối mã giảm giá';

    public $columns = [
        'id' => 'ID',
        'name' => 'Tên mã giảm giá',
        'code' => 'Mã giảm giá',
        'type' => 'Loại',
        'price' => 'Giá trị',
        'quantity' => 'Số lượng',
        'used_count' => 'Đã sử dụng',
        'status' => 'Trạng thái',
        'expire_date' => 'Ngày hết hạn'
    ];

    public function index()
    {
        $coupons = $this->model::where('is_public', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $title = $this->titleIndex;
        $columns = $this->columns;
        $urlBase = $this->urlBase;
        $template = 'backend.coupon_distribution.index';
        return view('backend.dashboard.layout', compact('template', 'coupons', 'title', 'columns', 'urlBase'));
    }

    public function show($id)
    {
        $coupon = $this->model::findOrFail($id);
        $assignedUsers = CouponUser::where('coupon_id', $id)
            ->with('user')
            ->get();
        $title = $this->titleShow;
        $urlBase = $this->urlBase;
        $template = 'backend.coupon_distribution.show';
        $availableUsers = \App\Models\User::where('status', 'active')->where('role', 'member')->get();
        return view('backend.dashboard.layout', compact('template', 'coupon', 'assignedUsers', 'title', 'urlBase', 'availableUsers'));
    }

    public function assignUsers(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // Delete existing assignments
            CouponUser::where('coupon_id', $id)->delete();

            // Create new assignments, skip users who have already used the coupon
            $assignments = [];
            $skippedUsers = [];
            foreach ($request->user_ids as $userId) {
                $alreadyUsed = CouponUser::where('coupon_id', $id)
                    ->where('user_id', $userId)
                    ->where('used', true)
                    ->exists();
                if ($alreadyUsed) {
                    $user = \App\Models\User::find($userId);
                    $skippedUsers[] = $user ? $user->name . ' (' . $user->email . ')' : $userId;
                    continue;
                }
                $assignments[] = [
                    'user_id' => $userId,
                    'coupon_id' => $id,
                    'used' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (count($assignments)) {
                CouponUser::insert($assignments);
            }

            DB::commit();
            $message = 'Phân phối mã giảm giá thành công';
            if (count($skippedUsers)) {
                $message .= '. Một số người dùng đã từng sử dụng mã này và không được phân phối lại: ' . implode(', ', $skippedUsers);
            }
            return redirect()->route('coupon-distribution.show', $id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Coupon distribution error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi phân phối mã giảm giá: ' . $e->getMessage());
        }
    }

    public function getAvailableUsers()
    {
        $users = User::whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('coupon_user')
                ->where('used', true);
        })->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
