        dd($user);
@if (Auth::id() === $user->id)
    {{-- 自分自身のユーザー詳細画面の場合に、お気に入り一覧とお気に入り解除ボタンを表示する --}}
    <h2>Favorite Posts</h2>
    <ul>
        @foreach ($user->favoritePosts as $post)
            <li>{{ $post->title }}</li>
            <form method="POST" action="{{ route('post.unfavorite', $post->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error btn-sm" 
                    onclick="return confirm('「{{ $post->title }}」のお気に入りを解除しますか？')">Unfavorite</button>
            </form>
        @endforeach
    </ul>
@else
    {{-- 自分以外のユーザー詳細画面の場合に、お気に入りボタンを表示する --}}
    @auth
        @if (Auth::user()->is_favoriting($micropost->id))
            {{-- お気に入り解除ボタンのフォーム --}}
            <form method="POST" action="{{ route('micropost.unfavorite', $micropost->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error btn-block normal-case"
                    onclick="return confirm('この投稿をお気に入りから外しますか？')">Unfavorite</button>
            </form>
        @else
            {{-- お気に入りボタンのフォーム --}}
            <form method="POST" action="{{ route('micropost.favorite', $micropost->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-block normal-case">Favorite</button>
            </form>
        @endif
    @endauth
@endif

@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('danger'))
    <div class="alert alert-danger" role="alert">
        {{ session('danger') }}
    </div>
@endif