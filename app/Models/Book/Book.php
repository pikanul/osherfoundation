<?php

namespace App\Models\Book;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

}
