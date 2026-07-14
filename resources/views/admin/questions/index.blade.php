@extends('layouts.app')

@section('title', '問題管理')
@section('description', 'IT合格トレーナーの問題管理ページです。')
@section('canonical', route('admin.questions.index'))
@section('robots', 'noindex,nofollow')

@section('content')
    <h1>問題管理</h1>
    <p class="lead">資格別に問題と解説を追加・編集できます。</p>

    @if (session('status'))
        <div class="notice" role="status">{{ session('status') }}</div>
    @endif

    <form class="form-grid" method="GET" action="{{ route('admin.questions.index') }}">
        <div>
            <label for="certification">資格で絞り込み</label>
            <select id="certification" name="certification">
                <option value="">すべて</option>
                @foreach ($certifications as $slug => $cert)
                    <option value="{{ $slug }}" @selected($certification === $slug)>{{ $cert['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="actions">
            <button type="submit">絞り込む</button>
            <a class="button secondary" href="{{ route('admin.questions.create', ['certification' => $certification]) }}">問題を追加</a>
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>資格</th>
                    <th>順序</th>
                    <th>形式</th>
                    <th>問題</th>
                    <th>答え</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ config('certifications.' . $question->certification_slug . '.short_name', $question->certification_slug) }}</td>
                        <td>{{ $question->sort_order }}</td>
                        <td>{{ $question->format === 'multiple_choice' ? '4択' : '○×' }}{{ $question->is_trial ? ' / 無料' : '' }}</td>
                        <td>{{ $question->question }}</td>
                        <td>{{ $question->answer }}</td>
                        <td>
                            <div class="actions compact">
                                <a href="{{ route('admin.questions.edit', ['question' => $question]) }}">編集</a>
                                <form method="POST" action="{{ route('admin.questions.destroy', ['question' => $question]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="nav-button" type="submit">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $questions->links() }}
@endsection
