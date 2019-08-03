<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorType extends Model
{
    public $timestamps = true;
    protected $guarded = [];
    protected $table = 'visitors_types';

    public function visitors() {
        return $this->hasMany(Visitor::class, 'type_id');
    }

    public function scopeGetList(Request $request) {

    }
}
