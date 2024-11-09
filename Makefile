# Docker Compose のファイル名
COMPOSE_FILE := docker-compose.yml

# ホストのUIDとGIDを取得
HOST_UID ?= $(shell id -u)
HOST_GID ?= $(shell id -g)

# ユーザー名を設定
DOCKER_USER ?= cakephp_user

# 環境変数をエクスポート
export HOST_UID
export HOST_GID
export DOCKER_USER

# デフォルトのターゲット
.PHONY: all
all: up

# Docker Compose でコンテナを起動
.PHONY: up
up:
	docker compose -f $(COMPOSE_FILE) up -d

# Docker Compose でコンテナを停止
.PHONY: down
down:
	docker compose -f $(COMPOSE_FILE) down

# Docker Compose でコンテナのログを表示
.PHONY: logs
logs:
	docker compose -f $(COMPOSE_FILE) logs -f

# Docker Compose でコンテナを再起動
.PHONY: restart
restart:
	docker compose -f $(COMPOSE_FILE) restart

# コンテナのビルド
.PHONY: build
build:
	docker compose -f $(COMPOSE_FILE) build

# コンテナの状態を確認
.PHONY: ps
ps:
	docker compose -f $(COMPOSE_FILE) ps

# webコンテナ内でbashを実行
.PHONY: bash
bash:
	docker compose -f $(COMPOSE_FILE) exec -e HOME=/home/cakephp_user -u cakephp_user web bash -l

# データベースコンテナ内でpsqlを実行
.PHONY: psql
psql:
	docker compose -f $(COMPOSE_FILE) exec db psql -U cakephp -d cakephp

# CakePHPのマイグレーションを実行
.PHONY: migrate
migrate:
	docker compose -f $(COMPOSE_FILE) exec -e USER=$(DOCKER_USER) --user $(HOST_UID):$(HOST_GID) web bin/cake migrations migrate

# Composerの更新
.PHONY: composer-update
composer-update:
	docker compose -f $(COMPOSE_FILE) exec -e USER=$(DOCKER_USER) --user $(HOST_UID):$(HOST_GID) web composer update

# キャッシュのクリア
.PHONY: clear-cache
clear-cache:
	docker compose -f $(COMPOSE_FILE) exec -e USER=$(DOCKER_USER) --user $(HOST_UID):$(HOST_GID) web bin/cake cache clear_all

# ユニットテストの実行
.PHONY: test
test:
	docker compose -f $(COMPOSE_FILE) exec -e USER=$(DOCKER_USER) --user $(HOST_UID):$(HOST_GID) web vendor/bin/phpunit
