<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected    $fillable=['name','extension' , 'size','source','user_id' , 'orginal_name'];
    public function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }
}
