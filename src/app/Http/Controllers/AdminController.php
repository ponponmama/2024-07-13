<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // 管理ダッシュボード表示
    public function index()
    {
        $shops = Shop::all();
        return view('admin.dashboard',compact('shops'));
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
            'email_verified_at' => now(),
            'shop_id' => $validated['shop_id'],
        ]);

        $shop = Shop::find($validated['shop_id']);
        $shop->user_id = $user->id;
        $shop->save();

        return redirect()->route('admin.dashboard')->with('success', '新しいShopManagerが正常に登録されました');
    }

    //新しい店舗作成
    public function createShop(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string',
            'description' => 'nullable|string',
            'genre' => 'required|string',
            'area' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'open_time' => 'nullable|string',
            'close_time' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $shop = new Shop([
                'shop_name' => $validated['shop_name'],
                'description' => $validated['description'],
                'genre' => $validated['genre'],
                'area' => $validated['area'],
                'open_time' => $validated['open_time'],
                'close_time' => $validated['close_time'],
            ]);

            if ($request->hasFile('image')) {
                $shop->image = $request->file('image')->store('images', 'public');
                }

            $shop->save();

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', '新規店舗が正常に登録されました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('登録に失敗しました。' . $e->getMessage());
        }
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