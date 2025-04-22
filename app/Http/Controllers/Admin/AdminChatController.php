<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function index()
    {
        // Lấy danh sách user đã từng nhắn tin hoặc nhận tin nhắn từ admin (id = 1 ví dụ)
        $adminId = auth()->id(); // hoặc cố định admin ID nếu cần

        // Lấy danh sách user ID đã nhắn tin với admin
        $userIds = Message::where('sender_id', $adminId)
            ->orWhere('receiver_id', $adminId)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->filter(function ($id) use ($adminId) {
                return $id != $adminId; // bỏ admin ra
            });

        // Lấy thông tin user
        $chats = User::whereIn('id', $userIds)->get();

        $template = 'backend.chats.index';

        return view('backend.dashboard.layout', compact('chats', 'template'));
    }

    public function getUsers()
    {
        $adminId = auth()->id();
        $userIds = Message::where('receiver_id', $adminId)
            ->orWhere('sender_id', $adminId)
            ->pluck('sender_id')
            ->merge(
                Message::where('receiver_id', $adminId)
                    ->orWhere('sender_id', $adminId)
                    ->pluck('receiver_id')
            )->unique()->filter(fn($id) => $id != $adminId);

        return User::whereIn('id', $userIds)->select('id', 'name')->get();
    }

    public function getMessages($userId)
    {
        $adminId = auth()->id();

        return Message::where(function ($q) use ($adminId, $userId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($adminId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orderBy('created_at')->get();
    }

    public function sendMessage(Request $request)
    {
        $adminId = auth()->id();

        // Kiểm tra dữ liệu gửi tới
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);

        // Tạo tin nhắn mới
        $message = Message::create([
            'sender_id' => $adminId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Phát sự kiện MessageSent
        broadcast(new MessageSent($message))->toOthers();

        // Trả về phản hồi
        return response()->json(['success' => true, 'message' => $message]);
    }
}
