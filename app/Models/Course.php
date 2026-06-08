<?php

namespace App\Models;
use App\Models\CourseCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $guarded = ['id'];

    public function lessons()
    {
        return $this->hasMany(Lession::class);
    }


    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function applicationList()
    {
        return $this->hasMany(TraningApplyList::class, 'course_id');
    }

    public function seatAvailable()
    {
        $pendingHolding = $this->applicationList()
            ->where('application_status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(60))
            ->count();

        $approvedList = $this->applicationList()
            ->where('application_status', 'approved')
            ->count();

        return $this->avialable_seat - $pendingHolding + $approvedList;
    }

    public function enrollments()
    {
        return $this->hasMany(TraningApplyList::class, 'course_id')->where('application_status', 'approved');
    }
}
