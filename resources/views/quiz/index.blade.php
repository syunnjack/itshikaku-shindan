@extends('layouts.app')

@section('title', $currentCertification['name'] . ' 本試験対策')
@section('description', $currentCertification['name'] . 'の本試験で問われやすい重要論点を一問一答で確認できます。')
@section('canonical', route('quiz.index', ['certification' => $currentSlug]))

@php
    $quizStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Quiz',
        'name' => $currentCertification['name'] . ' 本試験対策クイズ',
        'inLanguage' => 'ja',
        'about' => $currentCertification['name'],
        'url' => route('quiz.index', ['certification' => $currentSlug]),
        'educationalLevel' => $currentCertification['level'],
        'educationalUse' => 'exam preparation',
        'learningResourceType' => 'quiz',
        'teaches' => [$currentCertification['name'], '本試験頻出論点', '選択式問題', '詳しい解説'],
    ];
@endphp

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode($quizStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <h1>{{ $currentCertification['name'] }} {{ !empty($isReviewMode) ? '間違えた問題の復習' : '本試験対策' }}</h1>
    <p class="lead">{{ $currentCertification['description'] }}</p>

    @guest
        <div class="notice warning" role="status">無料5問を利用するには、新規登録またはログインしてください。回答数はアカウント単位で保存されます。</div>
    @elseif ($isPaidMember)
        <div class="notice" role="status">有料会員: すべての問題を制限なく利用できます。</div>
    @else
        <div class="notice warning" role="status">無料体験中: 残り {{ $remainingFreeQuestions }} 問を無料で回答できます。6問目以降は有料会員限定です。</div>
    @endif

    <h2>同じ分類の資格に切り替える</h2>
    <div class="cert-grid" aria-label="資格切り替え">
        @foreach (collect($certifications)->where('category', $currentCertification['category'])->sortBy('category_rank') as $slug => $certification)
            <a class="cert-card" href="{{ route('quiz.index', ['certification' => $slug]) }}" @if ($slug === $currentSlug) aria-current="page" @endif>
                <span class="cert-rank">{{ $certification['category_rank'] }}位 / {{ $certification['code'] }}</span>
                <span class="cert-name">{{ $certification['short_name'] }}</span>
                <span class="cert-meta">{{ $certification['level'] }}</span>
            </a>
        @endforeach
    </div>

    @if ($question)
        <p class="lead">{{ !empty($isReviewMode) ? '過去に間違えた問題を再確認して、弱点をつぶしてください。' : '本試験で問われる判断軸を意識して、最も適切な選択肢を選んでください。' }}</p>

        @if (!empty($shouldUseTrialQuestion) && $question->is_trial)
            <div class="notice" role="status">無料体験の5問は、本試験形式に近い選択式問題と詳しい解説で、購入前に学習効果を確認できます。</div>
        @endif

        @auth
            <form method="POST" action="{{ route('quiz.check', ['certification' => $currentSlug]) }}" aria-label="{{ $currentCertification['name'] }}の回答フォーム">
                @csrf
                <p class="question-text">{{ $question->question }}</p>
                <input type="hidden" name="id" value="{{ $question->id }}">
                @if (!empty($isReviewMode))
                    <input type="hidden" name="review" value="1">
                @endif
                @if ($question->isMultipleChoice())
                    <div class="choice-form">
                        @foreach ($question->choices as $key => $choice)
                            <button type="submit" name="answer" value="{{ $key }}" class="choice-button">
                                <span class="choice-key">{{ $key }}</span>
                                <span>{{ $choice }}</span>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="answer-form">
                        <button type="submit" name="answer" value="○" class="answer-button" aria-label="正しい">○</button>
                        <button type="submit" name="answer" value="×" class="answer-button" aria-label="間違い">×</button>
                    </div>
                @endif
            </form>
        @else
            <p class="question-text">{{ $question->question }}</p>
            <div class="actions">
                <a class="button" href="{{ route('register') }}">無料で5問試す</a>
                <a class="button secondary" href="{{ route('login') }}">ログイン</a>
            </div>
        @endauth
    @else
        @if (!empty($isReviewMode))
            <p class="lead">現在、{{ $currentCertification['name'] }} で今日復習すべき問題はありません。</p>
            <p>通常問題に回答すると、正誤に応じて次回復習日が自動で設定されます。</p>
            <div class="actions">
                <a class="button" href="{{ route('quiz.index', ['certification' => $currentSlug]) }}">通常問題へ進む</a>
            </div>
        @else
            <p class="lead">現在、{{ $currentCertification['name'] }} の問題がありません。</p>
            <p>データベースにこの資格の問題を追加すると、ここにランダムな本試験対策クイズが表示されます。</p>
        @endif
    @endif
@endsection
