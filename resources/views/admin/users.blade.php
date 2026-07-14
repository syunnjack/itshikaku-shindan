@extends('layouts.app')

@section('title', '会員管理')
@section('description', 'IT合格トレーナーの会員管理ページです。')
@section('canonical', route('admin.users'))
@section('robots', 'noindex,nofollow')

@section('content')
    <h1>会員管理</h1>
    <p class="lead">無料回答数と有料会員状態を確認できます。</p>

    @if (session('status'))
        <div class="notice" role="status">{{ session('status') }}</div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>メール</th>
                    <th>無料回答数</th>
                    <th>有料会員</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->free_questions_answered }}</td>
                        <td>{{ $user->is_paid_member ? '有効' : '無効' }}</td>
                        <td>{{ $user->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle-paid-member', ['user' => $user]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit">{{ $user->is_paid_member ? '無効化' : '有効化' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection
