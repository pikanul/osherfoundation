<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultSubject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function classInfo()
    {
        return $this->belongsTo(SmClass::class, 'class_id');
    }

    public function departmentInfo()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function sectionInfo()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
