connect:
	docker exec -it apache sh

rebuild:
	docker-compose down
	docker-compose up -d --build
	docker-compose up -d
