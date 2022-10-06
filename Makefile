EXTENSION := SemanticDrilldown

MW_VERSION ?= 1.35
SMW_VERSION ?= 4.0.2
PS_VERSION ?= 0.6.1
AL_VERSION ?= 0.4.2
MAPS_VERSION ?= 9.0.7
SRF_VERSION ?= 4.0.1

EXTENSION_FOLDER := /var/www/html/extensions/$(EXTENSION)
extension := $(shell echo $(EXTENSION) | tr A-Z a-z})
IMAGE_NAME := $(extension):test-$(MW_VERSION)-$(SMW_VERSION)-$(PS_VERSION)-$(AL_VERSION)-$(MAPS_VERSION)-$(SRF_VERSION)
PWD := $(shell bash -c "pwd -W 2>/dev/null || pwd")# this way it works on Windows and Linux
DOCKER_RUN_ARGS := --rm -v $(PWD)/coverage:$(EXTENSION_FOLDER)/coverage -w $(EXTENSION_FOLDER) $(IMAGE_NAME)
docker_run := docker run $(DOCKER_RUN_ARGS)

.PHONY: all
all:

# ======== CI ========

.PHONY: ci
ci: build test

.PHONY: ci-coverage
ci-coverage: build test-coverage

.PHONY: build
build:
	docker build --tag $(IMAGE_NAME) \
		--build-arg=MW_VERSION=$(MW_VERSION) \
		--build-arg=SMW_VERSION=$(SMW_VERSION) \
		--build-arg=PS_VERSION=$(PS_VERSION) \
		--build-arg=AL_VERSION=$(AL_VERSION) \
		--build-arg=MAPS_VERSION=$(MAPS_VERSION) \
		--build-arg=SRF_VERSION=$(SRF_VERSION) \
		.

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
