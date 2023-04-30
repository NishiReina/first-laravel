概要

### コンテナの立ち上げ
```
docker-compose up -d
```

### Laravel　データベースの作成、データの挿入

- phpのコンテナに入る
```
docker-compose exec php bash
```

- composerのインストール
```
composer install
```

- マイグレーションの実行
```
php artisan migrate
```

- データの挿入
```
php artisan db:seed
```

- Laravelのコンテナから出る
```
exit
```

### LocalStackの環境設定

- localstackのコンテナに入る
```
docker-compose exec localstack bash
```

- AWSキー、パスワードを設定する
```
aws configure --profile localstack
```

上記のコマンドを打ち込むと、下記の項目を１つずつ質問されるので、それぞれ「:」の右側の値を打ち込む。
```
AWS Access Key ID [None]: coachtech
AWS Secret Access Key [None]: coachtech_pass
Default region name [None]: ap-northeast-1
Default output format [None]: json
```

実際にAWSにアクセスするわけではないので、
AWS Access Key ID、AWS Secret Access Keyは適当な値を設定して問題ありません。

- バケットを作成する

今回、バケット名はcoachtechとしています。
```
aws --endpoint-url=http://localstack:4566 s3 mb s3://coachtech/
```

- 作成されたバケットを確認する
```
aws s3 ls --endpoint-url=http://localstack:4566 --profile localstack
```

- localstackのコンテナから出る
```
exit
```