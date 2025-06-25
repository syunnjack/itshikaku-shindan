@extends('layouts.app')

@section('tilte','結果')

@section('content')
    <h1>結果</h1>
<p>あなたの回答：{{ $userAnswer }}</p>
<div class="result-box">
@if ($isCorrect)
    <div style="background-color: #4CAF50; color: #fff; padding: 20px; border-radius: 10px; margin-top: 20px;">
        <p>正解！</p>
</div>
@else
    <div style="background-color: #4CAF50; color: #fff; padding: 20px; border-radius: 10px; margin-top: 20px;">
    <p>残念！正解は {{ $question->answer }} でした。</p>
@endif
@if(!empty($question->explanation))
    <hr>
    <p>{{$question->explanation}}</p>
@endif
</div>
<br>
<a href="{{url('/quiz')}}">次の問題へ</a><br>
<a href="{{ url('/') }}">トップページへ戻る</a>
@endsection
