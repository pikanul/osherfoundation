<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
