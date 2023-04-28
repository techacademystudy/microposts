<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        //このメソッドでは、'microposts', 'followings', 'followers', 'favorites'それぞれのメソッドのレコード数を取得する。
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }

    /**
     * このユーザがフォロー中のユーザ。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    /**
     * このユーザをフォロー中のユーザ。（Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        //既にフォロー済みか確認する
        $exist = $this->is_following($userId);
        //フォロー先が自分かどうかを確認する
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function unfollow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     * 
     * @param  int $userId
     * @return bool
     */
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////
    
    // お気に入り関連のメソッド
    

    /**
     * このユーザがお気に入りにしている投稿。（Userモデルとの関係を定義）
     */
    public function favorites()
    {
        // ユーザーIDを入力すると、そのユーザーがお気に入りした投稿一覧が返る
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }

    public function favorite($micropostId)
    {
        // 既にお気に入り済みか確認する
        $exist = $this->is_favoriting($micropostId);
        // お気に入り先が自分の投稿かどうかを確認する
        $its_me = $this->microposts()->where('id', $micropostId)->where('user_id', $this->id)->exists();
    
        // if ($exist || $its_me) { //自分の投稿であれば、お気に入りできないようにする
         if ($exist) {
            // 既にお気に入り済みであれば何もしない
            return false;
        } else {
            // 未お気に入りであればお気に入りする
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    public function is_myposts($micropostId)
    {
        // お気に入り先が自分の投稿かどうかを確認する
        $its_me = $this->microposts()->where('id', $micropostId)->where('user_id', Auth::user()->id)->exists();
        if ($its_me == true) {
            // 自分の投稿
            return true;
        } else {
            // 他人の投稿
            return false;
        }
    }

    public function unfavorite($micropostId)
    {
        // 既にお気に入り済みか確認する
        $exist = $this->is_favoriting($micropostId);
    
        if ($exist) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
    
    public function is_favoriting($micropostId) {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }


}
