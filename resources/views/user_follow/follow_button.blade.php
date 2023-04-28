<!--現在認証されているユーザーのIDが、表示中のユーザーのIDと異なる場合にのみ、フォロー/アンフォローボタンを表示するように条件分岐を行っています。-->
@if (Auth::id() != $user->id)
    <!--表示中のユーザーが既にフォローされている場合、アンフォローボタンを表示します。 -->
    @if (Auth::user()->is_following($user->id))
        {{-- アンフォローボタンのフォーム --}}
        <form method="POST" action="{{ route('user.unfollow', $user->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error btn-block normal-case" 
                onclick="return confirm('id = {{ $user->id }} のフォローを外します。よろしいですか？')">Unfollow</button>
        </form>
    @else
        {{-- フォローボタンのフォーム --}}
        <form method="POST" action="{{ route('user.follow', $user->id) }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-block normal-case">Follow</button>
        </form>
    @endif
@endif

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-danger" role="alert">
        {{ session('danger') }}
    </div>
@endif