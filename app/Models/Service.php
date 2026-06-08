<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable','model_type','model_id');
    }


    public function category(){
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

   

}
