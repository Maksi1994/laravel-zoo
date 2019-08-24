<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Http\Request;

class Role extends Model
{
  protected $guarded = [];
  public $timestamps = true;

  public function users()  {
    return $this->hasMany(User::class);
  }

  public function scopeGetList($query, Request $request) {
        $query->when($request->orderType === 'new' || $request->orderType !== 'count', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'count', function ($q) use ($request) {
            $q->orderBy('users_count', $request->order ?? 'desc');
        });

        return $query;
  }
}
