##################
# Docker compose
##################

dc_build:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env build

dc_start:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env start

dc_stop:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env stop

dc_up:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env up -d --remove-orphans

dc_ps:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env ps

dc_logs:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env logs -f

dc_down:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env down -v --rmi=all --remove-orphans


##################
# App
##################

app_bash:
	docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env exec -u www-data php-fpm bash