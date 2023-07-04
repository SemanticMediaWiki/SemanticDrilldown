ARG MW_VERSION
ARG PHP_VERSION
FROM gesinn/mediawiki-ci:${MW_VERSION}-php${PHP_VERSION}

ARG MW_VERSION
ARG SMW_VERSION
ARG PS_VERSION
ARG AL_VERSION
ARG MAPS_VERSION
ARG SRF_VERSION
ARG PHP_VERSION

# get needed dependencies for this extension
RUN sed -i s/80/8080/g /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN COMPOSER=composer.local.json composer require --no-update mediawiki/semantic-media-wiki ${SMW_VERSION}
RUN curl -L https://github.com/wikimedia/mediawiki-extensions-PageSchemas/archive/refs/tags/${PS_VERSION}.tar.gz \
        | tar zx --strip-components=1 --one-top-level=extensions/PageSchemas
RUN curl -L https://github.com/wikimedia/mediawiki-extensions-AdminLinks/archive/refs/tags/${AL_VERSION}.tar.gz \
        | tar zx --strip-components=1 --one-top-level=extensions/AdminLinks
RUN COMPOSER=composer.local.json composer require --no-update mediawiki/maps ${MAPS_VERSION}
RUN COMPOSER=composer.local.json composer require --no-update mediawiki/semantic-result-formats ${SRF_VERSION}
RUN composer update 


ENV EXTENSION=SemanticDrilldown
COPY composer*.json package*.json /var/www/html/extensions/$EXTENSION/

RUN cd extensions/$EXTENSION && npm ci 
RUN cd extensions/$EXTENSION && composer update

COPY . /var/www/html/extensions/$EXTENSION

RUN echo \
        "wfLoadExtension( 'SemanticMediaWiki' );\n" \
        "enableSemantics( 'localhost' );\n" \
        "wfLoadExtension( 'PageSchemas' );\n" \
        "wfLoadExtension( 'AdminLinks' );\n" \
        "wfLoadExtension( 'Maps' );\n" \
        "wfLoadExtension( 'SemanticResultFormats' );\n" \
        "wfLoadExtension( '$EXTENSION' );\n" \
    >> __setup_extension__
