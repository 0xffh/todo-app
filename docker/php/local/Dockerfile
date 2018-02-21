FROM       php:7.1-fpm-alpine

RUN adduser -s /bin/ash -D docker
WORKDIR /var/www
RUN rm -rf /var/www/html

#Install PHP extensions
RUN echo "@main34 http://dl-cdn.alpinelinux.org/alpine/v3.4/main" >> /etc/apk/repositories \
    && apk update \
    && apk --no-cache add \
        $PHPIZE_DEPS \
        libxslt-dev \
        icu-dev \
    && pecl install \
        apcu \
    && docker-php-ext-install \
        opcache \
        pdo \
        pdo_mysql \
        xsl \
        intl \
    && docker-php-ext-enable \
        apcu

#Install utils
RUN apk add --no-cache \
        nano \
        git \
        openssl

#Install Composer
ENV COMPOSER_HOME /home/docker/.composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --version=1.5.5 --install-dir=/bin \
    && php -r "unlink('composer-setup.php');" \
    && ln -s /bin/composer.phar /bin/composer \
    && mkdir /home/docker/.composer/cache \
    && chown -R docker:docker /home/docker/.composer \
    && chmod -R g+w /home/docker/.composer
USER docker
RUN composer global require hirak/prestissimo \
    && chmod -R g+w /home/docker/.composer
USER root

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY php.ini /usr/local/etc/php/