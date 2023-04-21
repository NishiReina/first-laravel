init:
	sudo docker-compose up -d
	docker-compose exec localstack aws --endpoint-url=http://localstack:4566 s3 mb s3://coachtech2/
	docker-compose exec localstack aws s3 ls --endpoint-url=http://localstack:4566 --profile localstack
	docker-compose exec php php artisan migrate:fresh
	docker-compose exec php php artisan db:seed

