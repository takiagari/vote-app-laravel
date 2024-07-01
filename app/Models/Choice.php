<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'title',
        'image',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}