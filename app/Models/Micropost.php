<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    ////////////////////////////////////////////////////
    
    /**
     * この投稿をお気に入り中のユーザ。（Userモデルとの関係を定義）
     */
    public function favoritedBy()
    {
        // 投稿IDを入力すると、それをお気に入りしたユーザー一覧が返る
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }


}
