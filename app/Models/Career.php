<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['file_name'];

    public function getFileNameAttribute()
    {
        if ($this->attributes['file_name']) {
            return dynamic_asset($this->attributes['file_name']);
        }
        return null;
    }
}
