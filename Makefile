down:
	sudo docker compose down -v
db:
	sudo docker compose up -d db
build:
	sudo docker compose up --build laravelapp
migrate:
    sudo docker exec laravelapp php artisan migrate
run:
    sudo docker compose up	

	build: docker push southamerica-east1-docker.pkg.dev/api-veiculos-411205/laravelapp:2.2.3
		
