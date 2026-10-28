<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

##貯金目標の保存処理をupdateOrCreateに修正
既存データを更新し、データがなければ新規作成する処理のイメージがつかめなかったため、AIに質問した。しかし、回答をそのまま使用するだけでは十分に納得できなかったので、updateOrCreate() の動作を調べ、自分でコードを書き直した。




# 💰 MoneyShift

アルバイトのシフト・給与・支出・貯金目標を一括管理するWebアプリケーションです。

**アプリURL：** https://shift-money.vercel.app/

---

## 📌 1. アプリ概要

MoneyShiftは、アルバイトのシフト管理から給与計算、支出管理、貯金目標の達成予測までを一つにまとめたWebアプリケーションです。

出勤時間・退勤時間・時給を入力すると日給を計算し、月ごとの収入を確認できます。

また、現在の貯金額と目標金額を比較し、目標達成までに必要な期間を予測できます。

## 🎯 2. 開発背景・目的

アルバイトの給与や貯金を管理する際、複数のアプリを使い分ける必要があり、不便さを感じていました。

特に、深夜勤務や日付をまたぐ勤務では給与計算が複雑になり、収入を把握することが難しいという課題がありました。

そこで、シフトを入力するだけで給与を自動計算し、貯金目標の管理まで一括で行えるアプリを作りたいと考え、MoneyShiftの開発を始めました。

## 🖥️ 3. アプリ画面

### ダッシュボード

シフト収入・ボーナス・浪費・現在の貯金額・目標までの残額・目標達成予測などを確認できます。

<!-- スクリーンショットを追加予定 -->

## 🛠️ 4. 主な機能

### ① シフト・給与管理

- 出勤日・出勤時間・退勤時間・時給の入力
- 勤務時間からの日給計算
- 深夜割増（22:00〜翌5:00、1.25倍）への対応
- 日付をまたぐ勤務への対応
- 休憩時間の控除

### ② 月別収支管理

- 月ごとのシフト収入の集計
- ボーナスの登録・集計
- 浪費（支出）の登録・集計
- 月ごとの収支確認

### ③ 貯金目標管理

- 現在の貯金額の設定
- 目標貯金額の設定
- 目標までの残額表示
- 平均月収を利用した目標達成時期の予測

### ④ カレンダー機能

- シフト情報のカレンダー表示
- 月ごとの登録情報の確認

## 💻 5. 使用技術・技術選定理由

| 分類 | 使用技術 |
|---|---|
| フロントエンド | HTML / CSS / JavaScript |
| バックエンド | PHP / Laravel |
| データベース | SQLite |
| カレンダー | FullCalendar |
| ビルドツール | Vite |
| デプロイ | Vercel |
| バージョン管理 | Git / GitHub |

### PHP・Laravelを選定した理由

PHPを選んだきっかけは、TECH-BASEのチーム対抗型プログラミングインターンシップです。

このインターンシップでは、チームの各メンバーがPHPを用いて掲示板を開発し、その成果をチーム同士で競い合います。

そのため、インターンシップに向けてPHPを学習するとともに、学んだ知識を個人開発にも活かしたいと考え、MoneyShiftの開発言語としてPHPを選びました。

また、Webアプリケーションの開発を進めながら、データベース操作やバックエンドの仕組みについて理解を深めるため、PHPのフレームワークであるLaravelを採用しました。

## 💡 6. 開発で工夫した点・苦労した点

### ① 複雑な給与計算への対応

深夜勤務や日付をまたぐ勤務では、勤務時間によって時給が変化するため、給与計算の条件分岐が複雑になるという問題がありました。

当初は計算結果が正しくならないこともありましたが、勤務時間を通常時間帯と深夜時間帯に分け、それぞれの時間に応じて計算する方法を考えました。

また、ガントチャートを活用して勤務パターンを整理し、処理を細かく分解して、一つずつ検証しながら実装しました。

この経験から、**複雑な問題でも処理を分解し、原因を特定しながら解決することの重要性**を学びました。

### ② 関数・クラスとデータベースの連携

開発当初は、関数やクラスの役割を十分に理解できておらず、どの処理をどこに記述し、どのタイミングで呼び出せばよいのか分からなくなることがありました。

特に、シフト情報・ボーナス・支出・貯金目標など、複数のデータを扱う際、どのデータベースのテーブルに情報を保存し、どの関数で取得・計算するのかを把握することに苦労しました。

そこで、SQLのテーブル構成を確認しながら、それぞれのテーブルの役割やデータの流れを図に書き出して整理しました。

また、関数やクラスの役割も一つずつ確認し、処理同士のつながりを理解しながら開発を進めました。

この経験から、**個々のコードの書き方だけでなく、データの流れや処理全体の構造を把握することの重要性**を学びました。

## 📚 7. 開発を通して学んだこと

### ① 問題を分解して解決する力

給与計算の実装を通して、複雑な問題を一度に解決しようとするのではなく、小さな処理に分けて検証することの重要性を学びました。

### ② 技術を理解して活用する姿勢

開発中は分からない関数や処理も多くありましたが、調べたり、処理の流れを図に整理したりすることで、一つずつ理解を深めながら実装しました。

また、AIを活用する際にも、提案されたコードの意味を確認し、自分で理解することを意識しました。

### ③ チーム開発の重要性

今回のMoneyShiftは、企画から設計、実装まで一人で開発しました。

開発当初はプログラミングの知識が十分ではなく、どのような関数やメソッドがあるのか、どのようにコードを組み合わせればよいのか分からず、苦労することが多くありました。

そのため、分からない部分についてはAIに頼ることもありました。

また、適切な関数や効率的な実装方法を知らなかったことで、**自分で考えて書いたコードが必要以上に長くなってしまうこともありました。**

この経験から、一人で開発する難しさを実感するとともに、チーム内で知識や考え方を共有し、お互いのコードを確認しながら開発することの重要性を感じました。

複数人で意見を出し合うことで、自分では思いつかなかった実装方法や、より簡潔で分かりやすいコードの書き方を学べると考えています。

今後は、自分自身のプログラミング知識を深めるとともに、チーム開発を通して他の人の考え方や実装方法を学び、**可読性や保守性を意識したコードを書けるようになりたい**と考えています。

## 🚀 8. 今後の課題・改善予定

### ① UI/UXの改善とデータの可視化

現在のダッシュボードでは、シフト収入・ボーナス・浪費・貯金額・目標達成予測などを一つの画面に表示しています。

しかし、今後データや機能が増えると情報量が多くなり、必要な情報を把握しにくくなるという課題があります。

そこで、JavaScriptのグラフ描画ライブラリである**Chart.js**を学習し、棒グラフや円グラフを活用した収支分析ページを新たに作成したいと考えています。

具体的には、以下の機能を検討しています。

- 月ごとの収入や支出の推移を棒グラフで表示
- 浪費の金額や内訳を円グラフで可視化
- 貯金目標に対する達成状況の可視化
- ダッシュボードと詳細な分析ページの分離
- 情報量が増えても使いやすい画面設計

単に収支を記録するだけでなく、**貯金の進捗や支出の変化を視覚的に確認することで、利用者が貯金へのモチベーションを維持できるアプリ**に改善していきたいと考えています。

### ② 既存コードの復習と仕組みの理解

MoneyShiftは2026年9月に一度完成しましたが、その後は就職活動に時間を充てていたため、開発から期間が空き、JavaScriptのDOM操作やLaravelの仕組みなど、以前学習した内容を忘れている部分があります。

そのため、まずは既存のコードを一つずつ読み直し、各関数の役割や処理の流れ、フロントエンドとバックエンド、データベースの連携について復習する予定です。

特に、以下の内容を重点的に復習します。

- JavaScriptのDOM操作とイベント処理
- Laravelのルーティング
- コントローラーとモデルの役割
- データベースへの保存・取得処理
- 関数やメソッドの呼び出し関係

**自分の言葉でコードの仕組みを説明できる状態**を目指し、そのうえで新しい機能の実装や既存機能の改善に取り組みたいと考えています。

---

## 🔗 リンク

- **Webアプリ：** https://shift-money.vercel.app/
- **GitHub：** https://github.com/m2452057-png/shift-money

