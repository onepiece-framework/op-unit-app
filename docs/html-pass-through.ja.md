# `op-unit-app` における HTML Pass-Through

## 概要

この文書は、現在の HTML Pass-Through のうち、`op-unit-app` が担当している部分だけを説明します。

Router 側の endpoint 選択全体は詳述せず、App unit が endpoint を受け取った後の流れに限定します。

## App unit の現在の責務

現在の実装で、App unit は次を担当しています。

- Router unit が選んだ endpoint を受け取る
- endpoint の拡張子から MIME type を決定する
- endpoint を text / non-text のどちらとして扱うかを決める
- text 系 endpoint を `OP()->Template()` で実行する
- 最終出力前に結果を保持する
- layout を適用するかどうかを判断する

## 現在の実行フロー

App unit が endpoint を受け取った後の流れは次の通りです。

1. `OP()->Unit()->Router()->EndPoint()` から endpoint を取得する
2. endpoint path から拡張子を取り出す
3. その拡張子から MIME type を決定する
4. MIME type が `text/*` でない場合は `file_get_contents()` で直接返す
5. MIME type が text の場合は endpoint を meta path に変換する
6. `OP()->Template()` で endpoint を実行する
7. 出力を App unit 内の buffer に保持する
8. 最終 MIME type が `text/html` なら `OP()->Unit()->Layout()->Auto()` を呼ぶ
9. それ以外なら保持した content をそのまま出力する

## 現在の設計上の意味

App unit の視点で見ると、HTML Pass-Through の意味は次の通りです。

- text 系 resource を endpoint として実行できる
- 実行結果を即時出力しない
- layout が必要かどうかを framework が後から判断するまで、最終出力を遅延する

これは NEW WORLD 実行モデルの App 側の実装部分です。

## non-text の扱い

App unit は non-text の endpoint を `OP()->Template()` では実行しません。

解決された MIME type が `text/*` でない場合、現在の App unit は file content を直接返します。

つまり App unit では、実務上次の線引きがあります。

- text 系 resource は pass-through 実行
- non-text resource は直接レスポンス処理

## [DOC-GAP] 現在の用語上の齟齬

コードコメントでは `For HTML Pass Through` と書かれていますが、App unit の挙動はすでに HTML だけには限定されていません。

App unit レベルでは、現在のロジックは HTML 固定ではなく、MIME に基づく text / non-text の分岐になっています。

つまり、歴史的名称は残っている一方で、App unit の実態はすでに resource 指向です。

## [DOC-GAP] 現在の実装上の齟齬

歴史的名称と App unit の現在の挙動の間には、明確なズレがあります。

### 1. 名称は HTML を指している

しかし App unit のロジックは MIME と text / non-text 判定に基づいています。

### 2. text 系の処理範囲は HTML より広い

`text/*` に解決される endpoint は、`OP()->Template()` と出力保持のフローを通ります。

### 3. non-text は別の処理系になっている

non-text resource は同じ実行経路では処理されず、`file_get_contents()` により直接返されます。

つまり、歴史的には HTML Pass-Through と呼ばれている中に、App unit ではすでに異なる系統の挙動が混在しています。

## [DOC-FUTURE] 将来方針

現在の名称と App unit の実装は完全には一致していません。

当面は歴史的名称である `HTML Pass-Through` を使い続けます。

ただし、このズレは認識されており、将来的にはより明確にするか、より自然な形で解消していくべき対象です。
