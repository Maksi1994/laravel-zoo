<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Visitor extends Model
{

    protected $guarded = [];
    public $timestamps = true;


    public function type()
    {
        return $this->belongsTo(VisitorType::class);
    }

    public function scopeGetList($query, Request $request)
    {

        $query->when($request->type === 'new' || empty($request->type), function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->type === 'name', function ($q) use ($request) {
            $q->orderBy('name', $request->order ?? 'desc');
        });

        return $query;
    }
}
