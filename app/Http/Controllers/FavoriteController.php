<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function store($id)
    {
        $micropost = Micropost::find($id);
        $micropost->favorite(Auth::user()->id);
        return back();
    }


public function destroy($id)
{
    $micropost = Micropost::find($id);
    $result = Auth::user()->unfavorite($id);

    if ($result) {
        // お気に入り解除成功
        session()->flash('success', 'お気に入りを解除しました。');
    } else {
        // 他人のお気に入りで解除できない場合
        session()->flash('danger', 'このお気に入りは解除できません。');
    }

    return back();
}


public function favorites($id)
{
    $user = User::findOrFail($id);
    $favorites = $user->favorites()->get();
    return view('users.favorites', [
        'user' => $user,
        'favorites' => $favorites,
    ]);
}
    
}