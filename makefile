build:
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose up -d
	docker-compose exec instace-01.app composer install
	docker-compose exec instace-01.app php artisan key:generate

consumer:
	docker-compose exec kafka opt/kafka_2.13-2.8.1/bin/kafka-console-consumer.sh --topic EXPERIENCE --from-beginning --bootstrap-server localhost:9092

producer:
	docker-compose exec instace-01.app php artisan kafka:producer
