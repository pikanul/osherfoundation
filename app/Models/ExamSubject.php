<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function exam()
    {
        return $this->belongsTo(ResultExam::class, 'exam_id');
    }

    public function subject()
    {
        return $this->belongsTo(ResultSubject::class, 'subject_id');
    }

    public function classInfo()
    {
        return $this->belongsTo(SmClass::class, 'class_id');
    }

    public function departmentInfo()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
