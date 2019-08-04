<?php

namespace App\Http\Middleware;

class AuthRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $role)
  {
      $userModel = $request->user();

      if (!empty($userModel) || $userModel->isRole($role)) {
          return response()->json([
            'success' => false
          ]);
      }

      return $next($request);
  }
}
