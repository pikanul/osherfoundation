<?php

namespace App\Models;
use App\Models\SmClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function class_info() {
        return $this->belongsTo(SmClass::class, 'class_id', 'id');
    }

}
