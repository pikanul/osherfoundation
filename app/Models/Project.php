<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function category(){

        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }


}
