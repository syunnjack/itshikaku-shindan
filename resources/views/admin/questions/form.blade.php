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
            <label for="format">問題形式</label>
            <select id="format" name="format" required>
                <option value="true_false" @selected(old('format', $question->format ?? 'true_false') === 'true_false')>○×形式</option>
                <option value="multiple_choice" @selected(old('format', $question->format) === 'multiple_choice')>本試験形式（4択）</option>
            </select>
        </div>
        <div class="checkbox-row">
            <input id="is_trial" name="is_trial" type="checkbox" value="1" @checked(old('is_trial', $question->is_trial))>
            <label for="is_trial">無料5問で優先表示する</label>
        </div>
        <div>
            <label for="question">問題文</label>
            <textarea id="question" name="question" rows="5" required>{{ old('question', $question->question) }}</textarea>
        </div>
        @php
            $choices = $question->choices ?? [];
        @endphp
        <div>
            <label for="choice_a">選択肢 ア</label>
            <input id="choice_a" name="choice_a" type="text" value="{{ old('choice_a', $choices['ア'] ?? '') }}">
        </div>
        <div>
            <label for="choice_i">選択肢 イ</label>
            <input id="choice_i" name="choice_i" type="text" value="{{ old('choice_i', $choices['イ'] ?? '') }}">
        </div>
        <div>
            <label for="choice_u">選択肢 ウ</label>
            <input id="choice_u" name="choice_u" type="text" value="{{ old('choice_u', $choices['ウ'] ?? '') }}">
        </div>
        <div>
            <label for="choice_e">選択肢 エ</label>
            <input id="choice_e" name="choice_e" type="text" value="{{ old('choice_e', $choices['エ'] ?? '') }}">
        </div>
        <div>
            <label for="answer">正解</label>
            <select id="answer" name="answer" required>
                <option value="○" @selected(old('answer', $question->answer) === '○')>○</option>
                <option value="×" @selected(old('answer', $question->answer) === '×')>×</option>
                <option value="ア" @selected(old('answer', $question->answer) === 'ア')>ア</option>
                <option value="イ" @selected(old('answer', $question->answer) === 'イ')>イ</option>
                <option value="ウ" @selected(old('answer', $question->answer) === 'ウ')>ウ</option>
                <option value="エ" @selected(old('answer', $question->answer) === 'エ')>エ</option>
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
