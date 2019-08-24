<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    public $timestamps = true;
    public $guarded = [];

    public function estimable() {
      return $this->morphTo();
    }
}
