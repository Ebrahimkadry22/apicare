<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'client_id',
        'comment',
        'rate',
    ];

    public function post () {
        return $this->belongsTo(Post::class);
    }
    public function client () {
        return $this->belongsTo(Client::class);

    }
}
