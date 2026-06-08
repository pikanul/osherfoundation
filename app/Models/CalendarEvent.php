<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventType;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOfType($query, $typeId = null)
    {
        if (is_null($typeId) || $typeId === '' || $typeId === 'all') {
            return $query;
        }

        return $query->where('type_id', $typeId);
    }

    public function scopeOverlapDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $query
                ->whereDate('start_date', '<=', $endDate)
                ->whereDate('end_date', '>=', $startDate);
        }

        if ($startDate) {
            return $query->whereDate('end_date', '>=', $startDate);
        }

        if ($endDate) {
            return $query->whereDate('start_date', '<=', $endDate);
        }

        return $query;
    }

    public function event_type_info()
    {
        return $this->belongsTo(EventType::class, 'type_id', 'id');
    }
}
