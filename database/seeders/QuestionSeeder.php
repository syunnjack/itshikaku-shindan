<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class QuestionSeeder extends Seeder
{
    /**
     * Seed original exam-style questions for knowledge retention.
     */
    /**
     * 一問一答の問題を取り込む。
     *
     * 問題は database/data/questions/{資格スラッグ}.json に置いてある。
     * 資格ごとにファイルが分かれているので、追加や差し替えはそのファイルだけを触ればよい。
     * 解説の後半（本試験の見抜き方・定着ポイント）は有料会員向けにここで付け足す。
     */
    public function run(): void
    {
        $slugs = array_keys(config('certifications'));
        $rows = [];
        $missing = [];

        foreach ($slugs as $slug) {
            $path = database_path("data/questions/{$slug}.json");

            if (! File::exists($path)) {
                $missing[] = $slug;
                continue;
            }

            $questions = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

            foreach ($questions as $index => $question) {
                $rows[] = $this->question(
                    $slug,
                    (int) ($question['sortOrder'] ?? $index + 1),
                    $question['question'],
                    $question['answer'],
                    $question['explanation'],
                    $question['format'] ?? 'true_false',
                    $question['choices'] ?? null,
                    (bool) ($question['isTrial'] ?? false),
                );
            }
        }

        if ($rows === []) {
            throw new RuntimeException('問題データが見つかりません（database/data/questions）。');
        }

        DB::table('questions')->whereIn('certification_slug', $slugs)->delete();

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('questions')->insert($chunk);
        }

        $this->command?->info(number_format(count($rows)).'問を取り込みました。'
            .($missing === [] ? '' : '（問題ファイルが無い資格: '.implode('、', $missing).'）'));
    }

    private function question(
        string $slug,
        int $sortOrder,
        string $question,
        string $answer,
        string $explanation,
        string $format = 'true_false',
        ?array $choices = null,
        bool $isTrial = false
    ): array {
        $certification = config("certifications.$slug");
        $explanation = $isTrial
            ? $explanation
            : $this->premiumExplanation($slug, $answer, $explanation);

        return [
            'certification_slug' => $slug,
            'certification_name' => $certification['name'],
            'sort_order' => $sortOrder,
            'format' => $format,
            'choices' => $choices ? json_encode($choices, JSON_UNESCAPED_UNICODE) : null,
            'is_trial' => $isTrial,
            'question' => $question,
            'answer' => $answer,
            'explanation' => $explanation,
        ];
    }

    private function premiumExplanation(string $slug, string $answer, string $baseExplanation): string
    {
        $guidance = $this->premiumGuidance($slug);
        $judgement = $answer === '×'
            ? '誤りの選択肢は、用語の定義を一部だけ入れ替えたり、「必ず」「だけ」「すべて」のように範囲を広げすぎたりする形で出題されやすいです。'
            : '正しい選択肢は、用語の目的、対象、責任範囲が自然につながっています。理由まで声に出して説明できる状態を目標にしてください。';

        return $baseExplanation
            . "\n\n本試験の見抜き方: " . $guidance['exam']
            . "\n\n定着ポイント: " . $judgement . ' ' . $guidance['retention'];
    }

    private function premiumGuidance(string $slug): array
    {
        return [
            'it-passport' => [
                'exam' => 'ITパスポートでは、経営・ストラテジ・マネジメント・テクノロジの用語定義を、実務場面に置き換えて判断させる問題が多く出ます。略語は日本語の意味、目的、利用場面をセットで確認してください。',
                'retention' => '似た用語を1つ並べて違いを説明すると、暗記ではなく判断軸として定着します。',
            ],
            'fundamental-engineer' => [
                'exam' => '基本情報技術者では、データ構造、アルゴリズム、ネットワーク、DB、セキュリティの前提条件を問う問題が頻出です。問題文の「整列済み」「コネクション型」などの条件語を必ず拾ってください。',
                'retention' => '正解だけでなく、なぜその前提が崩れると不正解になるのかを1行で説明できるまで復習してください。',
            ],
            'applied-engineer' => [
                'exam' => '応用情報技術者では、単語の意味だけでなく、設計・運用・リスク対応でどの手段を選ぶべきかが問われます。目的と副作用をセットで見ると選択肢を絞れます。',
                'retention' => '自分の業務や架空システムに当てはめ、どの場面で使う知識かを言語化すると忘れにくくなります。',
            ],
            'security-management' => [
                'exam' => '情報セキュリティマネジメントでは、技術対策だけでなく、規程、教育、監視、報告、証拠保全まで含めた管理策の妥当性が問われます。',
                'retention' => '「予防」「検知」「対応」「復旧」のどの段階の話かを分類して覚えると、似た対策を整理できます。',
            ],
            'registered-security-specialist' => [
                'exam' => '情報処理安全確保支援士では、攻撃手法、暗号、認証、Web脆弱性、ログ調査を原因と対策の対応関係で問われます。攻撃の成立条件を押さえるのが近道です。',
                'retention' => '脆弱性名、攻撃の流れ、影響、代表的な対策を4点セットで復習してください。',
            ],
            'aws-cloud-practitioner' => [
                'exam' => 'AWS Cloud Practitionerでは、サービス名の暗記だけでなく、責任共有モデル、課金、可用性、セキュリティの基本責任が問われます。AWS側と利用者側の境界を意識してください。',
                'retention' => '各サービスを「何を保存するか」「何を実行するか」「何を管理するか」で分類すると選びやすくなります。',
            ],
            'azure-fundamentals' => [
                'exam' => 'Azure Fundamentalsでは、クラウド概念、ID管理、コスト、リージョン、IaaS/PaaS/SaaSの責任範囲がよく問われます。管理主体が誰かを確認してください。',
                'retention' => 'Azure固有名詞は、一般的なクラウド概念と対応付けて覚えると混同しにくくなります。',
            ],
            'ccna' => [
                'exam' => 'CCNAでは、OSI階層、IPアドレス、ルーティング、スイッチング、VLANの役割分担が問われます。どの層の話かを最初に判定してください。',
                'retention' => '通信の出発点から宛先まで、MAC、IP、ゲートウェイがどこで使われるかを図にすると定着します。',
            ],
            'comptia-security-plus' => [
                'exam' => 'CompTIA Security+では、攻撃手法、認証、ログ監視、インシデント対応、リスク管理を実務判断として問われます。単独対策より多層防御を選ぶ視点が重要です。',
                'retention' => '対策を「防ぐ」「見つける」「封じ込める」「復旧する」に分けて覚えると、設問の意図をつかみやすくなります。',
            ],
            'google-cloud-digital-leader' => [
                'exam' => 'Cloud Digital Leaderでは、Google Cloudの技術名だけでなく、データ活用、AI、移行、ビジネス価値の説明力が問われます。技術が何の価値につながるかを見てください。',
                'retention' => 'サービス名を覚えるときは、利用部門、解決する課題、代表的な成果をセットで整理してください。',
            ],
            'mos' => [
                'exam' => 'MOSでは、機能名だけでなく、実際の作業でどの機能を使うと効率よく目的を達成できるかが問われます。結果として画面やデータがどう変わるかを意識してください。',
                'retention' => 'Excel、Word、PowerPointの操作は、実際に同じ操作を1回行い、ショートカットや設定場所と結び付けて覚えると強く残ります。',
            ],
            'itil-foundation' => [
                'exam' => 'ITIL Foundationでは、価値共創、プラクティス、インシデント、問題、変更などの目的の違いが問われます。活動のゴールを取り違えないことが重要です。',
                'retention' => '「早く復旧する」「原因をなくす」「変更リスクを下げる」のように、各プラクティスの目的を短文で覚えてください。',
            ],
            'comptia-a-plus' => [
                'exam' => 'CompTIA A+では、ハードウェア、OS、ネットワーク基礎、トラブルシューティング手順が問われます。最初に確認すべき安全な切り分けを選ぶ視点が大切です。',
                'retention' => '症状、原因候補、確認方法、対応策を順番に並べると、現場対応型の問題に強くなります。',
            ],
            'comptia-network-plus' => [
                'exam' => 'CompTIA Network+では、DNS、DHCP、サブネット、ルーティング、疎通確認など、通信が成立する条件を問われます。名前解決、アドレス割当、経路の順に切り分けてください。',
                'retention' => 'コマンドやプロトコルは、何を確認するためのものかを1文で説明できる状態にしてください。',
            ],
            'linuc-level1' => [
                'exam' => 'LinuC Level 1では、基本コマンド、権限、プロセス、ファイル操作、管理者権限の扱いが問われます。コマンドの目的と代表的なオプションを結び付けてください。',
                'retention' => '実際の端末操作を想像し、入力するコマンド、対象ファイル、期待される出力をセットで復習すると定着します。',
            ],
        ][$slug] ?? [
            'exam' => '本試験では、用語の定義、目的、利用場面を混同させる選択肢が出やすいです。',
            'retention' => '正解理由と不正解理由を両方説明できるまで復習してください。',
        ];
    }
}
