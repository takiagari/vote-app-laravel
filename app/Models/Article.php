<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'body',
        'max_choices',
        'comments_enabled',
        'start_time',
        'end_time',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (is_null($article->end_time)) {
                // 日付が設定されていなければ14日後を設定
                $article->end_time = now()->addDays(14);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 画像リレーション
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    public function Choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
