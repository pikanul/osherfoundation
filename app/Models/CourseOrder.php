<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
        'status',
        'price',
        'expire_date',
        'transaction_id'
    ];

    protected $appends = [
      'status_text'
    ];

    public function  user(){
        $this->belongsTo(User::class);
    }

    public function getStatusTextAttribute(){
        return $this->status == 1 ? 'Success' : $this->status;
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
