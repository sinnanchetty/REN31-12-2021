<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{

	//notifications post table used
    use HasFactory;
    protected $table = 'post';
    protected $fillable = [
    	'id',
    	'likes_count',
    	'comments_count'

    ];
}

