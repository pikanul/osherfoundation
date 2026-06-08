<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SmClass;
use App\Models\Department;

class Section extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function class_info() {
        return $this->belongsTo(SmClass::class, 'class_id', 'id');
    }

    public function department_info() {
        return $this->belongsTo(Department::class, 'department_id', 'id');

    }
}
