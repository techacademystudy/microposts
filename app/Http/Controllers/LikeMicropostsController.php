<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeMicropostsController extends Controller
{
    /**
     * 投稿をお気に入りするアクション。
     *
     * @param  $id  気に入った投稿のid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿をお気に入りする
        \Auth::user()->like($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * 投稿をお気に入り解除するアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿をお気に入りから解除する
        \Auth::user()->unlike($id);
        // 前のURLへリダイレクトさせる
        return back();
    }}
