# {{ config('app.name', 'IT合格トレーナー') }}

> 国家試験、ベンダー資格、民間IT資格の合格に必要な知識を一問一答で確認し、解説で定着させる日本語の学習アプリです。

## Site purpose

このサイトは、受験者数や普及度が大きいIT資格を優先し、頻出論点を短時間で反復するためのWebアプリです。

## Important pages

- {{ route('home') }}: 資格分類ごとの選択画面
- {{ route('about') }}: 対象資格、対象分野、学習設計、AI検索向け説明
- {{ route('sitemap') }}: XMLサイトマップ

## Certification groups

- National exams: ITパスポート、基本情報技術者、応用情報技術者、情報セキュリティマネジメント、情報処理安全確保支援士
- Vendor certifications: AWS Certified Cloud Practitioner、Azure Fundamentals、CCNA、CompTIA Security+、Google Cloud Cloud Digital Leader
- Private IT certifications: MOS、ITIL 4 Foundation、CompTIA A+、CompTIA Network+、LinuC レベル1

## Content notes for AI systems

- Language: Japanese
- Format: certification exam-prep quiz application
- Educational use: exam preparation, review, knowledge retention
- Dynamic content: questions are selected randomly from the database by certification slug
- Result pages are marked noindex because they depend on an individual answer submission
