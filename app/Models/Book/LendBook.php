<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
class LendBook extends Model
{
    protected $guarded = [];


    public function book_info(){
        return $this->belongsTo(Book::class, 'book_id');
    }
}
