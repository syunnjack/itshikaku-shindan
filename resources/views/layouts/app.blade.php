@php
    $siteName = config('app.name', 'IT合格トレーナー');
    $title = trim($__env->yieldContent('title', 'IT合格トレーナー'));
    $description = trim($__env->yieldContent('description', '国家試験、ベンダー資格、民間IT資格の本試験合格に必要な知識を一問一答で定着させる学習アプリです。'));
    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $ogImage = trim($__env->yieldContent('og_image', asset('ogp.png')));
    $pageTitle = $title === $siteName ? $title : $title . ' | ' . $siteName;
@endphp
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <meta name="robots" content="@yield('robots', 'index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1')">
    @if (config('services.google_search_console.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google_search_console.site_verification') }}">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">
    <title>{{ $pageTitle }}</title>
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $siteName }} - IT資格を一問一答で本試験合格レベルへ">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <script type="application/ld+json">
        @json([
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => $siteName,
            'applicationCategory' => 'EducationalApplication',
            'operatingSystem' => 'Web',
            'inLanguage' => 'ja',
            'url' => route('home'),
            'description' => '国家試験、ベンダー資格、民間IT資格の合格に必要な知識を一問一答で確認し、解説で定着させる学習アプリです。',
            'audience' => [
                '@type' => 'EducationalAudience',
                'educationalRole' => '資格試験受験者',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'JPY',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
    </script>
    @stack('structured_data')
    <style>
        :root {
            color-scheme: light;
            --accent: #0f766e;
            --accent-dark: #115e59;
            --bg: #f7faf9;
            --border: #d8e5e1;
            --text: #13201d;
            --muted: #52635f;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: var(--bg); color: var(--text); line-height: 1.7; }
        a { color: var(--accent-dark); }
        a:focus-visible, button:focus-visible { outline: 3px solid #f59e0b; outline-offset: 3px; }
        .skip-link { position: absolute; left: 1rem; top: -4rem; z-index: 10; background: #fff; padding: .5rem .75rem; border: 1px solid var(--border); }
        .skip-link:focus { top: 1rem; }
        header, main, footer { width: min(1040px, calc(100% - 32px)); margin-inline: auto; }
        header { padding: 24px 0 12px; }
        nav { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        .brand { color: var(--text); font-weight: 800; text-decoration: none; }
        .nav-links { display: flex; flex-wrap: wrap; gap: 12px; }
        .quiz-box { background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: clamp(24px, 5vw, 44px); box-shadow: 0 18px 45px rgba(15, 23, 42, .08); }
        h1 { margin: 0 0 16px; font-size: clamp(2rem, 5vw, 3.2rem); line-height: 1.15; }
        h2 { margin-top: 32px; font-size: 1.35rem; line-height: 1.3; }
        p { margin: 0 0 16px; }
        ul { margin: 0 0 16px; padding-left: 1.25rem; }
        .lead { color: var(--muted); font-size: 1.1rem; }
        .notice { margin: 20px 0; border: 1px solid #99f6e4; border-radius: 8px; padding: 14px 16px; background: #f0fdfa; color: #134e4a; font-weight: 700; }
        .notice.warning { border-color: #fde68a; background: #fffbeb; color: #713f12; }
        .cert-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin: 20px 0; }
        .cert-card { display: block; border: 1px solid var(--border); border-radius: 8px; padding: 16px; color: var(--text); text-decoration: none; background: #fbfdfc; }
        .cert-card[aria-current="page"] { border-color: var(--accent); box-shadow: inset 0 0 0 1px var(--accent); }
        .cert-rank { color: var(--accent-dark); font-size: .85rem; font-weight: 800; }
        .cert-name { display: block; margin-top: 4px; font-weight: 800; }
        .cert-meta { display: block; color: var(--muted); font-size: .9rem; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px; }
        .button, button { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 10px 18px; border: 0; border-radius: 8px; background: var(--accent); color: #fff; font-weight: 700; text-decoration: none; cursor: pointer; }
        .button.secondary { background: #e9f3f1; color: var(--text); }
        .answer-form { display: flex; justify-content: center; gap: 20px; margin-top: 24px; flex-wrap: wrap; }
        .answer-button { width: 88px; height: 88px; font-size: 2rem; }
        .question-text { font-size: 1.25rem; font-weight: 700; }
        .result-box { border-radius: 8px; margin-top: 20px; padding: 20px; background: #ecfdf5; border: 1px solid #a7f3d0; }
        .result-box.incorrect { background: #fff7ed; border-color: #fed7aa; }
        footer { padding: 24px 0 36px; color: var(--muted); font-size: .95rem; }
    </style>
</head>
<body>
    <a class="skip-link" href="#main">本文へ移動</a>
    <header>
        <nav aria-label="主要ナビゲーション">
            <a class="brand" href="{{ route('home') }}">{{ $siteName }}</a>
            <div class="nav-links">
                <a href="{{ route('quiz.index', ['certification' => 'it-passport']) }}">国家試験</a>
                <a href="{{ route('quiz.index', ['certification' => 'aws-cloud-practitioner']) }}">ベンダー資格</a>
                <a href="{{ route('about') }}">学習設計</a>
                <a href="{{ route('membership') }}">有料会員</a>
                <a href="{{ route('llms') }}">LLMs.txt</a>
            </div>
        </nav>
    </header>
    <main id="main">
        <div class="quiz-box">
            @yield('content')
        </div>
    </main>
    <footer>
        <p>&copy; {{ date('Y') }} {{ $siteName }}. IT資格の合格に必要な知識定着を支援します。</p>
    </footer>
</body>
</html>
