<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClearSessionAfterReturn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 次のミドルウェアまたはアプリケーションへリクエストを進める
        $response = $next($request);

        \Log::info('Session clear flag: ' . $request->session()->get('clear_session_on_leave'));

        // ユーザーが他のページに遷移する際にセッションデータをクリアするためのフラグをチェック
        if ($request->session()->pull('clear_session_on_leave', false)) {
            $request->session()->forget('reservation_details');
            \Log::info('Session data cleared');
        }

        \Log::info('After clearing session: ' . json_encode($request->session()->all()));
        
        return $response;
    }
}
