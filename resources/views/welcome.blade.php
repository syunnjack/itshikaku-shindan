@extends('layouts.app')

@section('title', 'IT合格トレーナー')
@section('description', '国家試験、人気ベンダー資格、民間IT資格を切り替えながら、本試験合格に必要な知識を一問一答で即時確認し、解説で定着させる学習アプリです。')
@section('canonical', route('home'))

@php
    $groups = [
        'national' => '国家試験 受験者数順 5選',
        'vendor' => 'ベンダー資格 人気・受験規模順 5選',
        'private' => '民間IT資格 人気・受験規模順 5選',
    ];

    $homeStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => config('app.name', 'IT合格トレーナー'),
        'url' => route('home'),
        'inLanguage' => 'ja',
        'description' => '国家試験、ベンダー資格、民間IT資格の本試験対策を一問一答で行う学習アプリです。',
        'about' => array_values(array_map(fn ($certification) => $certification['name'], $certifications)),
        'potentialAction' => [
            '@type' => 'ReadAction',
            'target' => route('quiz.index', ['certification' => 'it-passport']),
        ],
    ];
@endphp

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode($homeStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <h1>IT資格を本試験合格レベルまで固める</h1>

    <p class="lead">受験者数・普及度が大きい資格を優先し、資格ごとの頻出論点を一問一答で即答できる状態にします。</p>
    <p>無料で5問まで試せます。6問目以降は有料会員として、全資格の問題、回答履歴、間違えた問題の復習を継続できます。</p>

    @foreach ($groups as $category => $heading)
        <h2>{{ $heading }}</h2>
        <div class="cert-grid" aria-label="{{ $heading }}">
            @foreach (collect($certifications)->where('category', $category)->sortBy('category_rank') as $slug => $certification)
                <a class="cert-card" href="{{ route('certifications.show', ['certification' => $slug]) }}">
                    <span class="cert-rank">{{ $certification['category_rank'] }}位 / {{ $certification['code'] }}</span>
                    <span class="cert-name">{{ $certification['name'] }}</span>
                    <span class="cert-meta">{{ $certification['level'] }}・{{ $certification['description'] }}</span>
                </a>
            @endforeach
        </div>
    @endforeach

    <h2>学習の狙い</h2>
    <ul>
        <li>資格ごとの頻出論点を短時間で反復する</li>
        <li>正誤だけでなく、解説で判断根拠を確認する</li>
        <li>回答履歴から間違えた問題を復習し、知識の穴を埋める</li>
    </ul>
@endsection
