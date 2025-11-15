-include .env
export

EXTENSION=SemanticMetaTags

# docker images
MW_VERSION?=1.43
PHP_VERSION?=8.3
DB_TYPE?=mysql
DB_IMAGE?="mariadb:11.2"

# extensions
SMW_VERSION?=dev-master

# composer
# Enables "composer update" inside of extension
COMPOSER_EXT?=true

# nodejs
# Enables node.js related tests and "npm install"
# NODE_JS?=true


