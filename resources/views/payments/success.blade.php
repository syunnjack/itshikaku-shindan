@extends('layouts.app')

@section('title', '決済完了')
@section('description', 'IT合格トレーナーの有料会員決済完了ページです。')
@section('canonical', route('payment.success'))
@section('robots', 'noindex,follow')

@section('content')
    <h1>決済を受け付けました</h1>
    <p class="lead">Stripeからの決済完了通知を受信すると、有料会員状態が自動で有効になります。</p>

    @if ($sessionId)
        <div class="notice" role="status">Checkout Session: {{ $sessionId }}</div>
    @endif

    <div class="actions">
        <a class="button" href="{{ route('quiz.index', ['certification' => 'it-passport']) }}">学習を再開する</a>
        <a class="button secondary" href="{{ route('membership') }}">会員状態を確認</a>
    </div>
@endsection
