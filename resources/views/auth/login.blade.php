@extends('layouts.app')

@section('title', 'ログイン')
@section('description', 'IT合格トレーナーへログインして、無料5問の学習状況と有料会員状態をユーザー単位で管理します。')
@section('canonical', route('login'))
@section('robots', 'noindex,follow')

@section('content')
    <h1>ログイン</h1>
    <p class="lead">無料5問の回答数と会員状態をアカウントに保存します。</p>

    @if (session('status'))
        <div class="notice warning" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-list" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-grid" method="POST" action="{{ route('login.store') }}">
        @csrf
        <div>
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
        </div>
        <div>
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <div class="checkbox-row">
            <input id="remember" name="remember" type="checkbox" value="1">
            <label for="remember">ログイン状態を保持</label>
        </div>
        <div class="actions">
            <button type="submit">ログイン</button>
            <a class="button secondary" href="{{ route('register') }}">新規登録</a>
        </div>
    </form>
@endsection
