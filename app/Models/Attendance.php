<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    use HasFactory;
    protected $guarded = ['id'];


    public function device_info()
    {
        return $this->belongsTo(ZkDevice::class, 'device_id', 'id');
    }

    public function student_info()
    {

        return $this->belongsTo(Student::class, 'pin', 'pin');
    }

}
