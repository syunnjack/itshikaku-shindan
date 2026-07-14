@extends('layouts.app')

@section('title', $mode === 'create' ? '問題追加' : '問題編集')
@section('description', 'IT合格トレーナーの問題編集ページです。')
@section('canonical', $mode === 'create' ? route('admin.questions.create') : route('admin.questions.edit', ['question' => $question]))
@section('robots', 'noindex,nofollow')

@section('content')
    <h1>{{ $mode === 'create' ? '問題追加' : '問題編集' }}</h1>

    @if ($errors->any())
        <div class="error-list" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-grid" method="POST" action="{{ $mode === 'create' ? route('admin.questions.store') : route('admin.questions.update', ['question' => $question]) }}">
        @csrf
        @if ($mode === 'edit')
            @method('PATCH')
        @endif

        <div>
            <label for="certification_slug">資格</label>
            <select id="certification_slug" name="certification_slug" required>
                @foreach ($certifications as $slug => $cert)
                    <option value="{{ $slug }}" @selected(old('certification_slug', $question->certification_slug) === $slug)>{{ $cert['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="sort_order">表示順</label>
            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $question->sort_order) }}" required>
        </div>
        <div>
            <label for="question">問題文</label>
            <textarea id="question" name="question" rows="5" required>{{ old('question', $question->question) }}</textarea>
        </div>
        <div>
            <label for="answer">正解</label>
            <select id="answer" name="answer" required>
                <option value="○" @selected(old('answer', $question->answer) === '○')>○</option>
                <option value="×" @selected(old('answer', $question->answer) === '×')>×</option>
            </select>
        </div>
        <div>
            <label for="explanation">解説</label>
            <textarea id="explanation" name="explanation" rows="6">{{ old('explanation', $question->explanation) }}</textarea>
        </div>
        <div class="actions">
            <button type="submit">{{ $mode === 'create' ? '追加する' : '更新する' }}</button>
            <a class="button secondary" href="{{ route('admin.questions.index', ['certification' => $question->certification_slug]) }}">一覧へ戻る</a>
        </div>
    </form>
@endsection
