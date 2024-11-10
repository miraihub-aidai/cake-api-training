## CakePHP5でのRESTfulAPIチュートリアル

- Articles, Users, Tags のモデルの単位で、RESTfulAPIを作成せよ。
  - Articlesを一覧取得する GET /api/articles のみテスト含めて実装済み。
  - 最低限必要なRESTfulAPIのエンドポイントの種類は下記。xxxxはModel名。
    - GET /api/xxxx
    - GET /api/xxxx/{id}
    - POST /api/xxxx
    - PUT /api/xxxx/{id}
    - DELETE /api/xxxx/{id}
- Controller, Domain\UseCase, Service, ServiceProvider を実装し、それぞれのテストも実装すること。
- 各領域の実装内容
  - Controller - 1つのControllerにそれぞれのModelに対するCRUD操作をするアクションメソッドをそれぞれ作成
  - UseCase - API単位で作成
  - ServiceProvider - API単位で作成
  - Sevice - 1つのServiceにそれぞれのModelに対するCRUD操作をするメソッドをそれぞれ作成

## RESTfulAPIチュートリアルのヒント

- Controller → UseCase → Service → UseCase → Controller で処理/データが流れている。
- DIコンテナに UseCase, Service を登録し、Controller から呼べるように、フレームワークの Controller Action Injection の機能を利用する。
- DIの妥当性はServiceProviderのUnitTestで確認し、それ以外の領域ではMockを利用したUnitTestを実装している。
