<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Student;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceMaster extends Model
{

    use HasFactory;
    protected $guarded = ['id'];

   public function student_info()
{
    return $this->belongsTo(Student::class, 'pin', 'pin');
}

    public function first_attendance_info()
    {
        return $this->belongsTo(Attendance::class, 'first_check_id', 'id');
    }

    public function last_attendance_info()
    {
        return $this->belongsTo(Attendance::class, 'last_check_id', 'id');
    }


    public function check_times_for_date($date = null)
    {
        if (!$date) {
          return null;
        }
        return $this->hasMany(Attendance::class, 'pin', 'pin')
                    ->whereDate('check_time', $date);
    }



}
