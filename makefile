connect:
	docker exec -it apache sh

start:
	docker-compose up

rebuild:
	docker-compose down
	docker-compose up -d --build

test:
	bin/phpunit tests/ConsumerTest.php
