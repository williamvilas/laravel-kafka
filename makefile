build:
	if ! [ -f .env ];then cp .env.example .env;fi
	docker compose up -d
	docker compose exec instace-01.app composer install
	docker compose exec instace-01.app php artisan key:generate

#consumer:
#	docker compose exec kafka opt/kafka_2.13-2.8.1/bin/kafka-console-consumer.sh --topic EXPERIENCE --from-beginning --bootstrap-server localhost:9092

consumer_01:
	docker compose exec instace-01.app php artisan kafka:consumer

consumer_02:
	docker compose exec instace-02.app php artisan kafka:consumer

producer:
	docker compose exec instace-01.app php artisan kafka:producer

describe:
	docker exec -it kafka1 kafka-topics --describe --bootstrap-server localhost:9092

kafka_consumer_01:
	docker exec -it kafka1 kafka-console-consumer --topic EXPERIENCE --from-beginning --bootstrap-server localhost:9092

kafka_consumer_02:
	docker exec -it kafka2 kafka-console-consumer --topic EXPERIENCE --from-beginning --bootstrap-server localhost:9092

kafka_consumer_03:
	docker exec -it kafka3 kafka-console-consumer --topic EXPERIENCE --from-beginning --bootstrap-server localhost:9092

kafka_delete:
	docker exec -it kafka1 kafka-topics --bootstrap-server localhost:9092 --topic EXPERIENCE --delete
