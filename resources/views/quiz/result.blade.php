@extends('layouts.app')

@section('title', $currentCertification['short_name'] . ($isCorrect ? ' 結果: 正解' : ' 結果: 不正解'))
@section('description', $currentCertification['name'] . 'の回答結果です。正解、あなたの回答、解説を確認できます。')
@section('canonical', route('quiz.index', ['certification' => $currentSlug]))
@section('robots', 'noindex,follow')

@section('content')
    <h1>{{ $currentCertification['short_name'] }} 結果</h1>

    <p>あなたの回答: <strong>{{ $userAnswer }}</strong></p>

    <div class="result-box {{ $isCorrect ? '' : 'incorrect' }}" role="status" aria-live="polite">
        @if ($isCorrect)
            <p><strong>正解です。</strong></p>
        @else
            <p><strong>不正解です。</strong> 正解は {{ $question->answer }} でした。</p>
        @endif

        @if (!empty($question->explanation))
            <h2>解説</h2>
            <p>{{ $question->explanation }}</p>
        @endif
    </div>

    <div class="actions">
        <a class="button" href="{{ route('quiz.index', ['certification' => $currentSlug]) }}">同じ資格で次の問題へ</a>
        <a class="button secondary" href="{{ route('home') }}">資格を選び直す</a>
    </div>
@endsection
