.PHONY: $(MAKECMDGOALS)

.DEFAULT_GOAL := help
SHELL := /bin/bash

up: #Запуск наших контейнеров
	docker-compose up -d

up-alone: #Запуск наших контейнеров, остановка других контейнеров, которые остались открытыми
	docker-compose up -d --remove-orphans

down: #Остановка контейнера
	docker-compose down

down-all: #Остановка наших контейнеров + всех остальных, которые остались открытыми
	docker-compose down --remove-orphans

##Команды для работы с Symfony + Doctrine

fixtures: #Заполнение базы данных таблицами из сущностей + загрузка фикстур
	docker-compose exec php php bin/console doctrine:schema:update --dump-sql && docker-compose exec php php bin/console doctrine:schema:update --force && docker-compose exec php php bin/console doctrine:fixtures:load

db-update: #Заполнение базы данных таблицами из сущностей
	docker-compose exec php php bin/console doctrine:schema:update --dump-sql && docker-compose exec php php bin/console doctrine:schema:update --force

db-validate: #Валидация БД
	docker-compose exec php php bin/console doctrine:schema:validate

composer-install: #Запуск установки пакетов композером
	docker-compose exec php composer install

npm-install: #Запуск установки NPM пакетов
	docker-compose exec php npm install

yarn: #Запуск сборка проекта при помощи yarn
	docker-compose exec php yarn encore dev

build: #Собрать проект(composer install + npm install + yarn encore dev + dump sql + update db)
	docker-compose exec php composer install && docker-compose exec php npm install && docker-compose exec php npm rebuild node-sass && docker-compose exec php php bin/console doctrine:schema:update --dump-sql && docker-compose exec php php bin/console doctrine:schema:update --force

jwt-key-gen: #Генерируем ключи для JWT авторизации
	docker-compose exec php php bin/console lexik:jwt:generate-keypair

host: #Добавить хосты проекта в файл hosts
	/bin/bash ./hosts.sh

rm: #Добавить хосты проекта в файл hosts
	docker-compose down --remove-orphans && docker stop $$(docker ps -qa) && docker network prune -f

test-all: #Запуск всех тестов
	docker-compose exec php php bin/console doctrine:schema:update --dump-sql --env=test && docker-compose exec php php bin/console doctrine:schema:update --force --env=test && docker-compose exec php php bin/console doctrine:fixtures:load --env=test -n && docker-compose exec php php vendor/bin/codecept run --steps --env=test
