-include .env
export

# setup for docker-compose-ci build directory
# delete "build" directory to update docker-compose-ci

ifeq (,$(wildcard ./build/))
    $(shell git submodule update --init --remote)
endif

EXTENSION := SemanticDrilldown

# docker images
MW_VERSION?=1.39
PHP_VERSION?=8.1
DB_TYPE?=mysql
DB_IMAGE?="mariadb:11.2"

# extensions
SMW_VERSION?=4.2.0
AL_VERSION ?= 0.6.3         # SD adds a link to BrowseData
MAPS_VERSION ?= 9.0.7
SRF_VERSION ?= 4.2.1

# composer
# Enables "composer update" inside of extension
COMPOSER_EXT?=true

# nodejs
# Enables node.js related tests and "npm install"
NODE_JS?=true

# check for build dir and git submodule init if it does not exist
include build/Makefile

