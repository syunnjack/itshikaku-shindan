@extends('layouts.app')

@section('title', 'プライバシーポリシー')
@section('description', 'IT合格トレーナーのプライバシーポリシーです。')
@section('canonical', route('legal.privacy'))

@section('content')
    <h1>プライバシーポリシー</h1>
    <p class="lead">学習履歴と会員情報の取り扱いについて説明します。</p>

    <h2>取得する情報</h2>
    <ul>
        <li>名前、メールアドレス、ログイン情報</li>
        <li>回答履歴、正誤、学習対象資格</li>
        <li>有料会員状態、決済サービスから通知される顧客IDや契約ID</li>
    </ul>

    <h2>利用目的</h2>
    <p>本人認証、学習履歴の保存、復習機能の提供、有料会員機能の提供、問い合わせ対応、サービス改善のために利用します。</p>

    <h2>第三者サービス</h2>
    <p>決済にはStripeを利用します。カード番号などの決済情報は本サービスでは保持せず、Stripeの管理する決済画面で処理されます。</p>
@endsection
