@extends('layouts.app')

@section('title', $currentCertification['name'] . ' 合格対策')
@section('description', $currentCertification['name'] . 'の頻出論点、無料問題、復習機能を使って本試験合格レベルの知識定着を目指すページです。')
@section('canonical', route('certifications.show', ['certification' => $certification]))

@php
    $courseStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $currentCertification['name'] . ' 合格対策',
        'description' => $currentCertification['description'],
        'provider' => [
            '@type' => 'Organization',
            'name' => config('app.name', 'IT合格トレーナー'),
            'url' => route('home'),
        ],
        'educationalLevel' => $currentCertification['level'],
        'inLanguage' => 'ja',
        'url' => route('certifications.show', ['certification' => $certification]),
    ];

    $faqStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => $currentCertification['name'] . 'は無料で学習できますか？',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => '無料会員は5問まで回答できます。6問目以降は有料会員として継続学習できます。',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => '間違えた問題だけ復習できますか？',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'ログイン後に回答履歴を保存し、間違えた問題だけを復習できます。',
                ],
            ],
        ],
    ];
@endphp

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode($courseStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($faqStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <h1>{{ $currentCertification['name'] }} 合格対策</h1>
    <p class="lead">{{ $currentCertification['description'] }}</p>

    <div class="notice" role="status">
        現在の収録問題数: {{ $questionCount }} 問。無料で5問まで回答できます。
    </div>

    <h2>この資格で固めること</h2>
    <ul>
        <li>{{ $currentCertification['level'] }}で問われる基本用語と判断軸</li>
        <li>本試験で迷いやすい正誤問題の見極め</li>
        <li>回答履歴に基づく不正解問題の反復</li>
    </ul>

    @if ($sampleQuestions->isNotEmpty())
        <h2>収録問題の例</h2>
        <ul>
            @foreach ($sampleQuestions as $question)
                <li>{{ $question->question }}</li>
            @endforeach
        </ul>
    @endif

    <div class="actions">
        <a class="button" href="{{ route('quiz.index', ['certification' => $certification]) }}">無料で問題を解く</a>
        @auth
            <a class="button secondary" href="{{ route('quiz.review', ['certification' => $certification]) }}">間違えた問題を復習</a>
        @else
            <a class="button secondary" href="{{ route('register') }}">新規登録</a>
        @endauth
    </div>
@endsection
