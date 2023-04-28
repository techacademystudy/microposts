<div class="mt-4">

    @if (isset($favorites))
        <ul class="list-none">
            @foreach ($favorites as $favorite)

                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="{{ Gravatar::get($favorite->user->email) }}" alt="" />
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $favorite->user->id) }}">{{ $favorite->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $favorite->created_at }}</span>
                        </div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($favorite->content)) !!}</p>
                        </div>
                        <div>
                            {{-- 既にお気に入り済みか判定 --}}
                            @if (Auth::user()->is_favoriting($favorite->id))
                                {{-- お気に入りしているので、お気に入り解除ボタンを表示 --}}
                                <div class="flex">
                                    <form method="POST" action="{{ route('micropost.unfavorite', $favorite->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-sm normal-case"
                                            onclick="return confirm('この投稿をお気に入りから外しますか？')">Unfavorite</button>
                                    </form>
                                    {{-- 自分の投稿の場合 --}}
                                    @if (Auth::user()->is_myposts($favorite->id) == true)
                                        {{-- 投稿削除ボタンのフォーム --}}
                                        <form method="POST" action="{{ route('microposts.destroy', $favorite->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-sm normal-case flex ml-2" 
                                                onclick="return confirm('Delete id = {{ $favorite->id }} ?')">Delete</button>
                                        </form>
                                    @endif
                                    <!--gittest-->
                                </div>
                            @else
                                {{-- お気に入りしていないので、お気に入りボタを表示 --}}
                                <div class="flex">
                                    <form method="POST" action="{{ route('micropost.favorite', $favorite->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm normal-case flex">Favorite</button>
                                    </form>
                                    {{-- 自分の投稿の場合 --}}
                                    @if (Auth::user()->is_myposts($favorite->id) == true)
                                        {{-- 投稿削除ボタンのフォーム --}}
                                        <form method="POST" action="{{ route('microposts.destroy', $favorite->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-sm normal-case flex ml-2" 
                                                onclick="return confirm('Delete id = {{ $favorite->id }} ?')">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
    @endif
</div>