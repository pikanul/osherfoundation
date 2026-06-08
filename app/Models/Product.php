<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function sub_category(){

        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function category(){

        return $this->belongsTo(Category::class, 'category_id');
    }


}
