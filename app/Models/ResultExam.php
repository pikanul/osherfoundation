<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultExam extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function classInfo()
    {
        return $this->belongsTo(SmClass::class, 'class_id');
    }

    public function departmentInfo()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
