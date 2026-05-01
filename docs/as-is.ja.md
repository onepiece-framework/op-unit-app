# App Unit As-Is

## 対象範囲

この文書は、App unit の現行 As-Is の責務と flow を説明します。

## 主責務

App unit は、framework startup 後の application flow management を担当します。

この unit の責務は次ではありません。

- routing rule 自体を決めること
- layout の詳細実装を自分で持つこと

この unit の責務は次です。

- Router unit から endpoint を取得する
- endpoint を実行する
- 実行結果を buffer する
- 後段の rendering flow が必要なら Layout unit を呼び出す

## 現行 flow

現行の App unit flow は次です。

1. `OP()->Unit()->Router()->EndPoint()` から endpoint を取得する
2. その endpoint を実行する
3. output content を buffer する
4. 後段の rendering 条件が必要なら Layout unit を呼ぶ

## Router との境界

App unit 自体は、どの endpoint を実行すべきかを決めません。

それは Router unit の責務です。

App unit は Router の結果を消費します。

## Layout との境界

App unit は layout behavior 自体を実装しません。

役割は、layout 処理を継続すべき場合に Layout unit を呼ぶことだけです。

実際に layout をどのように行うかは Layout unit の責務です。

## 現行の MIME ベース判定

現行実装では、layout へ進むかどうかの判断は MIME をもとに行われています。

これは unit 製作者による As-Is 実装判断として理解すべきです。

ここでは、永続的な抽象仕様として文書化しているわけではありません。

現行挙動の実務的意図は、不要な unit の load を避け、メモリ使用量を減らすことです。

## 意味

重要なのは、App unit の目的は application flow management であることです。

責任の範囲は次で止まります。

- endpoint の実行
- output の buffer
- 必要に応じて Layout unit へ handoff すること

この明確な分離により、次が保たれます。

- routing は Router
- application flow は App
- 最終的な layout behavior は Layout

