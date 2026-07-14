@extends('layouts.app')

@section('title', 'IT資格の学習設計')
@section('description', '国家試験、ベンダー資格、民間IT資格の本試験合格に必要な知識を一問一答で定着させる学習設計、対象分野、AI検索向け情報をまとめたページです。')
@section('canonical', route('about'))

@push('structured_data')
    <script type="application/ld+json">
        @json([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'このアプリはどのIT資格に対応していますか？',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => '国家試験5区分、ベンダー資格5区分、民間IT資格5区分に対応し、資格別に一問一答で学習できます。',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => '本試験合格レベルの知識定着にどう使いますか？',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => '資格を選び、問題を即答し、間違えた論点は解説で判断根拠を確認します。短い反復を重ねることで本試験での正誤判断を鍛えます。',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
    </script>
@endpush

@section('content')
    <h1>IT資格の学習設計</h1>

    <p class="lead">合格に必要なのは、用語を見た瞬間に意味・使いどころ・誤答パターンを判断できる状態です。</p>

    <h2>対象分類</h2>
    <ul>
        <li>国家試験: ITパスポート、基本情報、応用情報、情報セキュリティマネジメント、情報処理安全確保支援士</li>
        <li>ベンダー資格: AWS、Azure、Cisco、CompTIA Security+、Google Cloud</li>
        <li>民間IT資格: MOS、ITIL、CompTIA A+、CompTIA Network+、LinuC</li>
    </ul>

    <h2>定着の流れ</h2>
    <ul>
        <li>トップページで資格を選ぶ</li>
        <li>問題文を読んで、○×を即答する</li>
        <li>結果画面で正解と解説を確認する</li>
        <li>同じ資格で反復し、本試験で迷わない判断軸に変える</li>
    </ul>

    <h2>検索・AI向け情報</h2>
    <p>このサイトはIT資格の本試験対策に特化した日本語の一問一答アプリです。資格別URL、XMLサイトマップ、LLMs.txt、教育系の構造化データを用意し、検索エンジンやAI検索が用途を理解しやすい構成にしています。</p>

    <div class="actions">
        <a class="button" href="{{ route('home') }}">資格を選ぶ</a>
        <a class="button secondary" href="{{ route('llms') }}">LLMs.txt を見る</a>
    </div>
@endsection
