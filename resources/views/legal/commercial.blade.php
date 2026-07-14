@extends('layouts.app')

@section('title', '特定商取引法に基づく表記')
@section('description', 'IT合格トレーナーの特定商取引法に基づく表記です。')
@section('canonical', route('legal.commercial'))

@section('content')
    <h1>特定商取引法に基づく表記</h1>
    <p class="lead">有料会員機能の公開前に、運営者情報と販売条件を実情報へ差し替えてください。</p>

    <h2>販売事業者</h2>
    <p>IT合格トレーナー運営者。正式な事業者名、所在地、連絡先は本番公開前に記載します。</p>

    <h2>販売価格</h2>
    <p>有料会員登録画面およびStripe Checkout画面に表示される価格に従います。</p>

    <h2>支払方法</h2>
    <p>クレジットカードなど、Stripe Checkoutで利用可能な支払方法に対応します。</p>

    <h2>解約・返金</h2>
    <p>サブスクリプションの解約方法、返金条件、日割り精算の有無は本番公開前に明記してください。</p>
@endsection
