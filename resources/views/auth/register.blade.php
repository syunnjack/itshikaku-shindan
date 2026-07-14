@extends('layouts.app')

@section('title', '新規登録')
@section('description', 'IT合格トレーナーへ新規登録して、無料5問から資格学習を開始できます。')
@section('canonical', route('register'))
@section('robots', 'noindex,follow')

@section('content')
    <h1>新規登録</h1>
    <p class="lead">登録すると、無料5問の回答数をアカウント単位で管理できます。</p>

    @if ($errors->any())
        <div class="error-list" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-grid" method="POST" action="{{ route('register.store') }}">
        @csrf
        <div>
            <label for="name">名前</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
        </div>
        <div>
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
        </div>
        <div>
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required>
        </div>
        <div>
            <label for="password_confirmation">パスワード確認</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        </div>
        <div class="actions">
            <button type="submit">無料で5問試す</button>
            <a class="button secondary" href="{{ route('login') }}">ログイン</a>
        </div>
    </form>
@endsection
