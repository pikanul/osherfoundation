<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Management extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function boot(){
        parent::boot();
        static::created(function($management){
            $management->slug = $management->createSlug($management->designation);
            $management->save();
        });
    }


    private function createSlug($designation){
        if(static::whereSlug($slug = Str::slug($designation))->exists()){

            $max = static::whereName($designation)->latest('id')->skip(1)->value('slug');
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
