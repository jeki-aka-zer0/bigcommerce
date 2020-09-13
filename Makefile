up: docker-up

init: docker-clear docker-up permissions env composer

docker-clear:
	docker-compose down --remove-orphans

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

migration-diff:
	rm -rf api/var/cache/doctrine
	docker-compose exec php-cli composer app migrations:diff

migration:
	docker-compose exec php-cli composer app migrations:migrate

test:
	docker-compose exec php-cli composer test

pull:
	git pull origin master
