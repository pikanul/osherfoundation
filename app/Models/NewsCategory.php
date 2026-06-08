<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function newses(){
        
        return $this->hasMany(News::class,'news_category_id','id');
    }

    protected static function boot(){
        parent::boot();
        static::created(function($news_category){
            $news_category->slug = $news_category->createSlug($news_category->name);
            $news_category->save();
        });
    }

    private function createSlug($name){
        if(static::whereSlug($slug = Str::slug($name))->exists()){
    
            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');
            if(is_numeric($max[-1])){
                return preg_replace_callback('/(\d+)$/', function($matches){
                    return $matches[1] + 1;
                }, $max);
            }
            return "{$slug}-1";
            }
            return $slug;
    }

}
