@extends('layouts.app')

@section('title', '学習ダッシュボード')
@section('description', 'IT合格トレーナーの学習ダッシュボードです。回答履歴、正答率、復習対象を確認できます。')
@section('canonical', route('dashboard'))
@section('robots', 'noindex,follow')

@section('content')
    <h1>学習ダッシュボード</h1>
    <p class="lead">{{ $user->name }} さんの回答履歴と復習対象です。</p>

    <div class="stats-grid" aria-label="学習サマリー">
        <div class="stat-card">
            <span class="stat-label">回答数</span>
            <strong>{{ $totalAttempts }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">正答数</span>
            <strong>{{ $correctAttempts }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">正答率</span>
            <strong>{{ $accuracyRate }}%</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">復習対象</span>
            <strong>{{ $incorrectReviewCount }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">今日の復習</span>
            <strong>{{ $dueReviewCount }}</strong>
        </div>
    </div>

    <h2>資格別の進捗</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>資格</th>
                    <th>回答数</th>
                    <th>正答率</th>
                    <th>復習対象</th>
                    <th>今日の復習</th>
                    <th>次の行動</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $stat)
                    <tr>
                        <td>{{ $stat['short_name'] }}</td>
                        <td>{{ $stat['attempt_count'] }}</td>
                        <td>{{ $stat['accuracy_rate'] === null ? '-' : $stat['accuracy_rate'] . '%' }}</td>
                        <td>{{ $stat['incorrect_review_count'] }}</td>
                        <td>{{ $stat['due_review_count'] }}</td>
                        <td>
                            @if ($stat['due_review_count'] > 0)
                                <a href="{{ route('quiz.review', ['certification' => $stat['slug']]) }}">復習する</a>
                            @else
                                <a href="{{ route('quiz.index', ['certification' => $stat['slug']]) }}">問題を解く</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>最近の回答</h2>
    @if ($recentAttempts->isEmpty())
        <p>まだ回答履歴がありません。まずは1問解いて、弱点を見える化しましょう。</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>日時</th>
                        <th>資格</th>
                        <th>結果</th>
                        <th>あなたの回答</th>
                        <th>正解</th>
                        <th>次回復習</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentAttempts as $attempt)
                        <tr>
                            <td>{{ $attempt->created_at?->format('Y-m-d H:i') }}</td>
                            <td>{{ config('certifications.' . $attempt->certification_slug . '.short_name', $attempt->certification_slug) }}</td>
                            <td>{{ $attempt->is_correct ? '正解' : '不正解' }}</td>
                            <td>{{ $attempt->user_answer }}</td>
                            <td>{{ $attempt->correct_answer }}</td>
                            <td>{{ $attempt->review_due_at?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
