ARG MW_VERSION
FROM gesinn/docker-mediawiki-sqlite:${MW_VERSION}

ARG SMW_VERSION
ARG PS_VERSION
ARG AL_VERSION
RUN sed -i s/80/8080/g /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && \
    COMPOSER=composer.local.json composer require --no-update mediawiki/semantic-media-wiki ${SMW_VERSION} && \
    curl -L https://github.com/wikimedia/mediawiki-extensions-PageSchemas/archive/refs/tags/${PS_VERSION}.tar.gz \
        | tar zx --strip-components=1 --one-top-level=extensions/PageSchemas && \
    curl -L https://github.com/wikimedia/mediawiki-extensions-AdminLinks/archive/refs/tags/${AL_VERSION}.tar.gz \
        | tar zx --strip-components=1 --one-top-level=extensions/AdminLinks && \
    sudo -u www-data composer update && \
    echo \
        "wfLoadExtension( 'SemanticMediaWiki' );\n" \
        "enableSemantics( 'localhost' );\n" \
        "wfLoadExtension( 'PageSchemas' );\n" \
        "wfLoadExtension( 'AdminLinks' );\n" \
    >> LocalSettings.php && \
    php maintenance/update.php --skip-external-dependencies --quick

ENV EXTENSION=SemanticDrilldown
COPY composer*.json package*.json /var/www/html/extensions/$EXTENSION/

RUN cd extensions/$EXTENSION && \
    npm ci && \
    composer update

COPY . /var/www/html/extensions/$EXTENSION

RUN echo \
        "wfLoadExtension( '$EXTENSION' );\n" \
    >> LocalSettings.php
