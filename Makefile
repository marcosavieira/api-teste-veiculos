down:
	sudo docker compose down -v
db:
	sudo docker compose up -d db
build:
	sudo docker compose up --build laravelapp
migrate:
    sudo docker exec laravelapp php artisan migrate		
