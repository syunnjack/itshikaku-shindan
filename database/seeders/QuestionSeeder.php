<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Seed exam-prep questions for national, vendor, and private IT certifications.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            $this->question('it-passport', 1, 'ITパスポート試験では、ストラテジ、マネジメント、テクノロジの3分野が出題対象である。', '○', 'ITパスポートはITを利活用するすべての社会人向けの国家試験で、3分野を横断して問われます。'),
            $this->question('fundamental-engineer', 1, '基本情報技術者試験では、アルゴリズムやプログラミングの基礎が重要論点に含まれる。', '○', 'FEでは科目A・科目Bを通じて、アルゴリズム、データ構造、セキュリティなどが問われます。'),
            $this->question('applied-engineer', 1, '応用情報技術者試験は、テクノロジだけでなくマネジメントやストラテジも出題範囲に含む。', '○', 'APはレベル3の試験で、技術知識に加えて設計、管理、経営戦略の応用力も問われます。'),
            $this->question('security-management', 1, '情報セキュリティマネジメント試験は、組織の情報セキュリティ管理やリスク対応を重視する。', '○', 'SGは情報セキュリティを管理・推進する立場に必要な知識を問う試験です。'),
            $this->question('registered-security-specialist', 1, '情報処理安全確保支援士試験では、暗号、認証、脆弱性、インシデント対応など高度なセキュリティ知識が問われる。', '○', 'SCは高度区分相当のセキュリティ試験で、専門的な設計・運用・対応力が必要です。'),
            $this->question('aws-cloud-practitioner', 1, 'AWSの責任共有モデルでは、クラウドのセキュリティはAWSだけがすべて担当する。', '×', 'AWSはクラウド自体のセキュリティを担い、利用者はクラウド内の設定やデータ保護などを担います。'),
            $this->question('azure-fundamentals', 1, 'Azure Fundamentalsでは、IaaS、PaaS、SaaSなどのクラウドサービスモデルが出題範囲に含まれる。', '○', 'AZ-900ではクラウド概念、Azureサービス、管理、料金、ガバナンスが基礎論点です。'),
            $this->question('ccna', 1, 'CCNAでは、IPアドレス、ルーティング、スイッチング、ネットワークセキュリティの基礎が問われる。', '○', 'CCNAはネットワーク基礎とCisco技術のアソシエイトレベル資格です。'),
            $this->question('comptia-security-plus', 1, 'CompTIA Security+では、脅威、脆弱性、リスク管理、セキュリティ運用が主要論点に含まれる。', '○', 'Security+はセキュリティ基礎を広く扱うベンダーニュートラル資格です。'),
            $this->question('google-cloud-digital-leader', 1, 'Cloud Digital Leaderでは、Google Cloudのビジネス価値やデータ・AI活用の基礎も問われる。', '○', 'CDLは技術者だけでなく、クラウド活用を理解したいビジネス職にも向く基礎資格です。'),
            $this->question('mos', 1, 'MOSは、WordやExcelなどMicrosoft Office製品の実務操作スキルを測る資格である。', '○', 'MOSはOfficeアプリケーションの操作力を実技ベースで確認する代表的な民間IT資格です。'),
            $this->question('itil-foundation', 1, 'ITIL 4 Foundationでは、ITサービス管理における価値共創やサービスバリューシステムを扱う。', '○', 'ITIL 4はサービスマネジメントの考え方を体系化しており、運用・管理職で広く使われます。'),
            $this->question('comptia-a-plus', 1, 'CompTIA A+は、ITサポートに必要なPC、OS、ネットワーク、セキュリティの基礎を扱う。', '○', 'A+はITサポート職の入口として、ハードウェアからトラブル対応まで幅広く問います。'),
            $this->question('comptia-network-plus', 1, 'CompTIA Network+では、OSI参照モデル、TCP/IP、ネットワーク運用、障害対応が重要論点になる。', '○', 'Network+は特定ベンダーに偏らずネットワーク基礎を確認する資格です。'),
            $this->question('linuc-level1', 1, 'LinuC レベル1では、Linuxの基本コマンド、ファイル操作、ユーザー管理などが出題範囲に含まれる。', '○', 'LinuC レベル1はLinuxサーバーの基本操作と管理の基礎力を測ります。'),
        ]);
    }

    private function question(string $slug, int $sortOrder, string $question, string $answer, string $explanation): array
    {
        $certification = config("certifications.$slug");

        return [
            'certification_slug' => $slug,
            'certification_name' => $certification['name'],
            'sort_order' => $sortOrder,
            'question' => $question,
            'answer' => $answer,
            'explanation' => $explanation,
        ];
    }
}
