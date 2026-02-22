<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Daily_Reports_Count;
use App\Models\Platforms;

class Operator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Redirect to login for unauthenticated users
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->guest(route('login'));
        }

        if (Auth::user()->role === "admin") {
            try {
                $report_count = Daily_Reports_Count::where('id', 1)->value('counter') ?? 0;
                $platforms = Platforms::all();

                // Fetch data di semua route middleware
                view()->share('report_count', $report_count);
                view()->share('platforms', $platforms);
            } catch (\Exception $e) {
                // Fallback if database query fails
                view()->share('report_count', 0);
                view()->share('platforms', collect());
            }

            return $next($request);
        }

        // Return 401 for non-admin authenticated users
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        abort(403);
    }
}
