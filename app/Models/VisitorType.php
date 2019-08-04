<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VisitorType extends Model
{
    public $timestamps = true;
    protected $guarded = [];
    protected $table = 'visitors_types';

    public function visitors() {
        return $this->hasMany(Visitor::class, 'type_id');
    }

    public function scopeGetList($query, Request $request) {
        $query->when($request->orderType === 'new' || empty($request->orderType), function($q) use ($request)  {
              $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'popular', function($q) use ($request)  {
              $q->orderBy('visitors_count', $request->order ?? 'desc');
        });

        return $query;
    }
}
