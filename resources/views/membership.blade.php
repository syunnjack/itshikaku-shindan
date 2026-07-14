@extends('layouts.app')

@section('title', '有料会員登録')
@section('description', 'IT合格トレーナーの有料会員案内ページです。無料5問の先で、全資格・全問題を使って知識定着を継続できます。')
@section('canonical', route('membership'))
@section('robots', 'noindex,follow')

@section('content')
    <h1>有料会員で学習を継続</h1>
    <p class="lead">無料体験では {{ $freeQuestionLimit }} 問まで回答できます。6問目以降は有料会員限定です。</p>

    @if (session('status'))
        <div class="notice warning" role="status">{{ session('status') }}</div>
    @endif

    @auth
        <div class="notice {{ $isPaidMember ? '' : 'warning' }}" role="status">
            <strong>現在の回答数:</strong> {{ $answeredCount }} / {{ $freeQuestionLimit }} 問<br>
            <strong>会員状態:</strong> {{ $isPaidMember ? '有料会員' : '無料会員' }}<br>
            <strong>直前の学習:</strong> {{ $currentCertification['name'] }}
        </div>
    @else
        <div class="notice warning" role="status">
            無料5問を利用するには、まず新規登録またはログインしてください。
        </div>
    @endauth

    <h2>有料会員でできること</h2>
    <ul>
        <li>国家試験・ベンダー資格・民間IT資格の全問題を制限なく利用</li>
        <li>解説つき一問一答で、間違えた判断軸をすぐに修正</li>
        <li>短時間の反復で、本試験で迷いやすい論点を定着</li>
    </ul>

    <h2>次に接続する機能</h2>
    <p>決済サービスと接続すると、このページのCTAから有料会員登録へ進めるようになります。現時点では管理側でユーザーの有料会員フラグを有効化できる土台まで実装しています。</p>

    <div class="actions">
        @guest
            <a class="button" href="{{ route('register') }}">無料で5問試す</a>
            <a class="button secondary" href="{{ route('login') }}">ログイン</a>
        @else
            <a class="button" href="mailto:hello@it-goukaku.jp?subject=IT%E5%90%88%E6%A0%BC%E3%83%88%E3%83%AC%E3%83%BC%E3%83%8A%E3%83%BC%20%E6%9C%89%E6%96%99%E4%BC%9A%E5%93%A1%E5%B8%8C%E6%9C%9B">有料会員について問い合わせる</a>
            <a class="button secondary" href="{{ route('home') }}">資格を選び直す</a>
        @endguest
    </div>
@endsection
