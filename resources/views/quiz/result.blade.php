@extends('layouts.app')

@section('title', $currentCertification['short_name'] . ($isCorrect ? ' 結果: 正解' : ' 結果: 不正解'))
@section('description', $currentCertification['name'] . 'の回答結果です。正解、あなたの回答、解説を確認できます。')
@section('canonical', route('quiz.index', ['certification' => $currentSlug]))
@section('robots', 'noindex,follow')

@section('content')
    <h1>{{ $currentCertification['short_name'] }} 結果</h1>

    <p>
        あなたの回答: <strong>{{ $userAnswer }}</strong>
        @if ($question->isMultipleChoice())
            <span class="answer-detail">{{ $question->answerLabel($userAnswer) }}</span>
        @endif
    </p>

    <div class="result-box {{ $isCorrect ? '' : 'incorrect' }}" role="status" aria-live="polite">
        @if ($isCorrect)
            <p><strong>正解です。</strong></p>
        @else
            <p>
                <strong>不正解です。</strong>
                正解は {{ $question->answer }}
                @if ($question->isMultipleChoice())
                    <span class="answer-detail">{{ $question->answerLabel() }}</span>
                @endif
                でした。
            </p>
        @endif

        @if (!empty($question->explanation))
            <h2>解説</h2>
            <p>{!! nl2br(e($question->explanation)) !!}</p>
        @endif
    </div>

    @if ($isPaidMember)
        <div class="notice" role="status">有料会員: 次の問題へ進んで、反復学習を続けられます。</div>
    @elseif ($hasReachedFreeLimit)
        <div class="notice warning" role="status">無料で回答できる {{ $freeQuestionLimit }} 問に到達しました。6問目以降は有料会員限定です。</div>
    @else
        <div class="notice warning" role="status">無料体験中: 残り {{ $remainingFreeQuestions }} 問を無料で回答できます。</div>
    @endif

    <div class="actions">
        @if ($hasReachedFreeLimit)
            <a class="button" href="{{ route('membership', ['certification' => $currentSlug]) }}">有料会員で続ける</a>
        @elseif (!empty($isReviewMode))
            <a class="button" href="{{ route('quiz.review', ['certification' => $currentSlug]) }}">復習を続ける</a>
        @else
            <a class="button" href="{{ route('quiz.index', ['certification' => $currentSlug]) }}">同じ資格で次の問題へ</a>
        @endif
        <a class="button secondary" href="{{ route('quiz.review', ['certification' => $currentSlug]) }}">間違えた問題を復習</a>
        <a class="button secondary" href="{{ route('home') }}">資格を選び直す</a>
    </div>
@endsection
