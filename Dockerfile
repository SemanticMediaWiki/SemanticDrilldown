ARG MW_VERSION=1.35
FROM gesinn/docker-mediawiki-sqlite:${MW_VERSION}

ARG SMW_VERSION=4.0.1
RUN COMPOSER=composer.local.json composer require --no-update mediawiki/semantic-media-wiki ${SMW_VERSION} && \
    curl -L https://github.com/wikimedia/mediawiki-extensions-PageSchemas/archive/refs/tags/0.6.1.tar.gz \
        | tar zx --strip-components=1 --one-top-level=extensions/PageSchemas && \
    sudo -u www-data composer update && \
    php maintenance/update.php --skip-external-dependencies --quick

ENV EXTENSION=SemanticDrilldown
COPY composer*.json package*.json /var/www/html/extensions/$EXTENSION/

RUN cd extensions/$EXTENSION && \
    npm ci && \
    composer update

COPY . /var/www/html/extensions/$EXTENSION

RUN echo \
        "wfLoadExtension( 'SemanticMediaWiki' );\n" \
        "enableSemantics( 'localhost' );\n" \
        "wfLoadExtension( 'PageSchemas' );\n" \
        "wfLoadExtension( '$EXTENSION' );\n" \
    >> LocalSettings.php
