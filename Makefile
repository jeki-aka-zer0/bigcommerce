up: docker-up

init: docker-clear docker-up permissions env composer ssl

docker-clear:
	docker-compose down --remove-orphans
	sudo rm -rf var/docker

docker-up:
	docker-compose up --build -d

permissions:
	sudo chown -R root:root var
	sudo chmod -R 777 var
	
env:
	docker-compose exec php-cli rm -f .env
	docker-compose exec php-cli ln -sr .env.example .env

composer:
	docker-compose exec php-cli composer install

ssl:
	docker-compose exec nginx rm -f docker/nginx/ssl-cert-snakeoil.key
	docker-compose exec nginx rm -f docker/nginx/ssl-cert-snakeoil.pem
	docker-compose exec nginx ln -sr docker/nginx/ssl-cert-snakeoil.example.key docker/nginx/ssl-cert-snakeoil.key
	docker-compose exec nginx ln -sr docker/nginx/ssl-cert-snakeoil.example.pem docker/nginx/ssl-cert-snakeoil.pem

migration-diff:
	rm -rf api/var/cache/doctrine
	docker-compose exec php-cli composer app migrations:diff

migration:
	docker-compose exec php-cli composer app migrations:migrate

test:
	docker-compose exec php-cli composer test
