@extends('layouts.app')

@section('title','クイズ')

@section('content')
    <h1>問題</h1>

    <form method="POST" action="{{url('/quiz/check')}}">
        @csrf
        <p>{{ $question->question }}</p>
        <input type="hidden" name="id" value="{{ $question->id }}">
        <!--<input type="text" name="answer">-->
        <button type="submit" name="answer" value="〇" class="answer-button">〇</button>
        <button type="submit" name="answer" value="×" class="answer-button">×</button>
    </form>
@endsection
