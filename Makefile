-include .env
export

EXTENSION := SemanticDrilldown

MW_VERSION ?= 1.35
SMW_VERSION ?= 4.1.0
PS_VERSION ?= 0.6.1
AL_VERSION ?= 0.4.2
MAPS_VERSION ?= 9.0.7
SRF_VERSION ?= 4.0.1

PHP_VERSION ?= 7.4
DB_TYPE ?= sqlite
DB_IMAGE ?= ""

EXTENSION_FOLDER := /var/www/html/extensions/$(EXTENSION)
extension := $(shell echo $(EXTENSION) | tr A-Z a-z})

environment = IMAGE_NAME=$(IMAGE_NAME) \
MW_VERSION=$(MW_VERSION)  \
SMW_VERSION=$(SMW_VERSION) \
PS_VERSION=$(PS_VERSION) \
AL_VERSION=$(AL_VERSION) \
MAPS_VERSION=$(MAPS_VERSION) \
SRF_VERSION=$(SRF_VERSION) \
PHP_VERSION=$(PHP_VERSION) \
DB_TYPE=$(DB_TYPE) \
DB_IMAGE=$(DB_IMAGE)

compose = $(environment) docker-compose $(COMPOSE_ARGS)
compose-run = $(compose) run -T --rm
compose-exec-wiki = $(compose) exec -T wiki

IMAGE_NAME := extension:test-$(MW_VERSION)-$(SMW_VERSION)-$(PS_VERSION)-$(AL_VERSION)-$(MAPS_VERSION)-$(SRF_VERSION)
PWD := $(shell bash -c "pwd -W 2>/dev/null || pwd")# this way it works on Windows and Linux
DOCKER_RUN_ARGS := --rm -v $(PWD)/coverage:$(EXTENSION_FOLDER)/coverage -w $(EXTENSION_FOLDER) $(IMAGE_NAME)
docker_run := docker run $(DOCKER_RUN_ARGS)


show-current-target = @echo; echo "======= $@ ========"

.PHONY: all
all:

# ======== CI ========

.PHONY: .init
.init:
	$(show-current-target)
	$(eval COMPOSE_ARGS = --project-name ${extension}-$(DB_TYPE) --profile $(DB_TYPE))
ifeq ($(DB_TYPE), sqlite)
	$(eval WIKI_DB_CONFIG = --dbtype=$(DB_TYPE) --dbpath=/tmp/sqlite)
else
	$(eval WIKI_DB_CONFIG = --dbtype=$(DB_TYPE) --dbserver=$(DB_TYPE) --installdbuser=root --installdbpass=database)
endif
	@echo "COMPOSE_ARGS: $(COMPOSE_ARGS)"



.PHONY: install
install: destroy up .install

.PHONY: up
up: .init .build .up

.PHONY: down
down: .init .down

.PHONY: destroy
destroy: .init .destroy

.PHONY: ci
ci: install test

.PHONY: ci-coverage
ci-coverage: .init .build test-coverage

.PHONY: .build
.build:
	$(show-current-target)
	$(compose) build wiki
.PHONY: .up
.up:
	$(show-current-target)
	$(compose) up -d

.PHONY: .install
.install: .wait-for-db
	$(show-current-target)
	$(compose-exec-wiki) bash -c "sudo -u www-data \
		php maintenance/install.php \
		    --pass=wiki4everyone --server=http://localhost:8080 --scriptpath='' \
    		--dbname=wiki --dbuser=wiki --dbpass=wiki $(WIKI_DB_CONFIG) wiki WikiSysop && \
		cat __setup_extension__ >> LocalSettings.php && \
		sudo -u www-data php maintenance/update.php --skip-external-dependencies --quick \
		"

.PHONY: .down
.down:
	$(show-current-target)
	$(compose) down

.PHONY: .destroy
.destroy:
	$(show-current-target)
	$(compose) down -v

.PHONY: .wait-for-db
.wait-for-db:
	$(show-current-target)
ifeq ($(DB_TYPE), mysql)
	$(compose-run) wait-for $(DB_TYPE):3306 -t 120
else ifeq ($(DB_TYPE), postgres)
	$(compose-run) wait-for $(DB_TYPE):5432 -t 120
endif




.PHONY: test
test: composer-test npm-test

.PHONY: test-coverage
test-coverage: composer-test-coverage npm-test-coverage

.PHONY: composer-test
composer-test:
	$(docker_run) composer test

.PHONY: composer-test-coverage
composer-test-coverage:
	$(docker_run) composer test-coverage

.PHONY: npm-test
npm-test:
	$(docker_run) npm run test

.PHONY: npm-test-coverage
npm-test-coverage:
	$(docker_run) npm run test-coverage

.PHONY: bash
bash:
	docker run -it -v $(PWD):/src $(DOCKER_RUN_ARGS) bash

.PHONY: dev-bash
dev-bash:
	docker run -it --rm -p 8080:8080 \
		-v $(PWD):$(EXTENSION_FOLDER) \
		-v $(EXTENSION_FOLDER)/vendor/ -v $(EXTENSION_FOLDER)/node_modules/ \
		-w $(EXTENSION_FOLDER) $(IMAGE_NAME) bash -c 'service apache2 start && bash'

.PHONY: run
run:
	docker run -d -p 8080:8080 --name $(extension) \
		-v $(PWD):$(EXTENSION_FOLDER) \
		-v $(EXTENSION_FOLDER)/vendor/ -v $(EXTENSION_FOLDER)/node_modules/ \
		$(IMAGE_NAME)

# ======== Releasing ========

VERSION = `node -e 'console.log(require("./extension.json").version)'`

.PHONY: release
release: ci git-push gh-login
	gh release create $(VERSION)

.PHONY: git-push
git-push:
	git diff --quiet || (echo 'git directory has changes'; exit 1)
	git push

.PHONY: gh-login
gh-login: require-GH_API_TOKEN
	gh config set prompt disabled
	@echo $(GH_API_TOKEN) | gh auth login --with-token

.PHONY: require-GH_API_TOKEN
require-GH_API_TOKEN:
ifndef GH_API_TOKEN
	$(error GH_API_TOKEN is not set)
endif
