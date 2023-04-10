init:
	sudo docker-compose up -d
	docker-compose exec awscli aws --endpoint-url=http://localstack:4566 s3 mb s3://test2/
	docker-compose exec awscli aws s3 ls --endpoint-url=http://localstack:4566 --profile localstack