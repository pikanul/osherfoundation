<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
class BookRequest extends Model
{
    protected $fillable = ['book_name', 'user_name', 'user_message'];
}