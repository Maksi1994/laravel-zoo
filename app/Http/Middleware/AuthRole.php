<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $role
   * @return mixed
   */
  public function handle(Request $request, Closure $next, $role = null)
  {
      $userModel = $request->user();
      
      if (empty($userModel)) {
        return response()->json([
          'success' => false
        ]);
      }

      if (!$role) {
        return $next($request);
      } else if ($userModel->hasRole($role)) {
          return response()->json([
            'success' => false
          ]);
      }

      return $next($request);
  }
}
