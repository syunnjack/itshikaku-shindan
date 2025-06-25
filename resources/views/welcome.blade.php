@extends('layouts.app')

@section('title','トップページ')

@section('content')
    <h1>一問一答クイズアプリ</h1>

    <p>〇×で答える簡単クイズにチャレンジ！</p>

    <a href="{{ url('/quiz') }}">
        <button>クイズをはじめる</button>
    </a>
@endsection

