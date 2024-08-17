<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // 管理ダッシュボード表示
    public function index()
    {
        return view('admin.dashboard');
    }

    public function __construct()
    {
        $this->middleware('auth');  // ログインしていることを確認
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 1) {
                abort(403);  // admin でなければアクセス禁止
            }
            return $next($request);
        });
    }

    // shop_manager の管理画面
    public function manageShopManagers()
    {
        $managers = User::where('role', '2')->get();
        return view('admin.manage-shop-managers', ['managers' => $managers]);
    }

    //店舗代表者を作成
    public function createShopManager(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'user_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 2,
        ]);

        return redirect()->route('admin.dashboard')->with('success', '新しいShopManagerが正常に登録されました');
    }   

    //新しい店舗とその代表者を同時に作成
    public function createShop(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'shop_name' => 'required|string',
            'description' => 'nullable|string',
            'genre' => 'required|string',
            'area' => 'required|string',
            'image' => 'nullable|image',
            'open_time' => 'nullable|string',
            'close_time' => 'nullable|string',
        ]);

        // ユーザー（店舗代表者）を作成
        $user = User::create([
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 2,
        ]);

        // 画像ファイルがある場合はアップロード
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // 店舗を作成し、作成したユーザーを店舗代表者として関連付ける
        $shop = Shop::create([
            'shop_name' => $validated['shop_name'],
            'description' => $validated['description'],
            'genre' => $validated['genre'],
            'area' => $validated['area'],
            'image' => $validated['image'] ?? null,
            'open_time' => $validated['open_time'],
            'close_time' => $validated['close_time'],
            'user_id' => $user->id,
        ]);

        // 既存の店舗に新しく作成したユーザーを店舗代表者として関連付ける
        $shop = Shop::find($validated['shop_id']);
        $shop->user_id = $user->id;
        $shop->save();

        return redirect()->route('admin.dashboard')->with('success', '新しい店舗とShopManagerが正常に作成されました');
    }

    public function destroy(User $user)
    {
        // 削除前に適切な権限があるか確認
        if (!auth()->user()->can('delete', $user)) {
           abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
