init:
	sudo docker-compose up -d
	docker-compose exec localstack aws --endpoint-url=http://localstack:4566 s3 mb s3://coachtech/
	docker-compose exec localstack aws s3 ls --endpoint-url=http://localstack:4566 --profile localstack