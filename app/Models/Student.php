<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Section;
use App\Models\SmClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{

    use HasFactory;
    protected $guarded = ['id', 'pin'];


    public function class_info() {
        return $this->belongsTo(SmClass::class, 'class_id', 'id');
    }
    public function department_info() {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function section_info() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }


    public function traningApplications()
    {
        return $this->hasMany(TraningApplyList::class, 'student_id');
    }


    // App\Models\Student.php
    protected static function booted()
    {
        static::created(function ($student) {
            if (!$student->pin) {
                $student->pin = (string) $student->id;
                $student->saveQuietly();
            }
        });

        static::updating(function ($student) {
            if ($student->isDirty('pin')) {
                throw new \Exception('PIN cannot be changed');
            }
        });

    }



}
