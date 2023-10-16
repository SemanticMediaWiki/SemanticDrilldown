ARG MW_VERSION
ARG PHP_VERSION
FROM gesinn/mediawiki-ci:${MW_VERSION}-php${PHP_VERSION}
ENV EXTENSION=SemanticDrilldown

ARG MW_VERSION
ARG SMW_VERSION
ARG PS_VERSION
ARG AL_VERSION
ARG MAPS_VERSION
ARG SRF_VERSION
ARG PHP_VERSION

# get needed dependencies for this extension
RUN sed -i s/80/8080/g /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN composer-require.sh mediawiki/semantic-media-wiki ${SMW_VERSION}
RUN get-github-extension.sh PageSchemas ${PS_VERSION}
RUN get-github-extension.sh AdminLinks ${AL_VERSION}
RUN composer-require.sh mediawiki/maps ${MAPS_VERSION}
RUN composer-require.sh mediawiki/semantic-result-formats ${SRF_VERSION}
RUN composer update 

RUN chown -R www-data:www-data /var/www/html/extensions/SemanticMediaWiki/

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
