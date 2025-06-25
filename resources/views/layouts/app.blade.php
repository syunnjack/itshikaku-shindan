<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', '一問一答クイズ')</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            padding: 40px;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .quiz-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: inline-block;
            min-width: 300px;
        }
        button:not(.answer-button) {
            font-size: 1.2em;
            margin: 10px;
            padding: 10px 30px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        /*button:hover {
            background-color: #45a049;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }*/

        .result-box {
        background-color: #4CAF50;
        color: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        font-size: 1.2em;
        line-height: 1.5;
        }
        button.answer-button {
        width: 80px;
        height: 80px;
        font-size: 2em;
        line-height: 80px;      /* ← 高さと同じにして文字を中央に */
        text-align: center;
        vertical-align: middle;
        border: none;
        border-radius: 8px;
        background-color: #4CAF50;
        color: white;
        margin: 10px;
        cursor: pointer;
        /*text-align: center; /* ← 中央表示明示 */
        }
        .answer-form {
        display: flex;
        justify-content: center; /* 横方向中央 */
        align-items: center;     /* 縦方向中央（高さがある場合） */
        gap: 20px;               /* ボタン間のスペース */
        margin-top: 20px;
        flex-wrap: wrap;
    }

    </style>
</head>
<body>
    <div class="quiz-box">
        @yield('content')
    </div>
</body>
</html>
