<?php

namespace App\Models;
use App\Models\SubCategory;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function category(){

        return $this->hasMany(SubCategory::class,'category_id');
    }
    public function subcategory(){

        return $this->hasMany(SubCategory::class,'sub_category_id');
    }


    public function products(){

        return $this->hasMany(Product::class,'category_id');
    }


}
