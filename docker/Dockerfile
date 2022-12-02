# dockerfile outils
FROM php:7.4-alpine3.13
ARG WORKSPACE

ARG ssh_prv_key
ARG ssh_pub_key
ARG composer_auth

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    sed -i -e "s/memory_limit = 128M/memory_limit = 512M/g" /usr/local/etc/php/php.ini

RUN	apk update && \
	apk upgrade && \
	apk add --update git docker curl openssl openssh-client php7-mysqli php7-dom php7-json php7-tokenizer php7-iconv php7-xmlwriter php7-curl php7-zip && \
	apk add --update php7-simplexml php7-session php7-pdo php7-pdo_mysql php7-mbstring php7-ldap php7-gd grep php7-sockets php7-intl && \
	apk add --update libpng libpng-dev curl-dev openldap-dev libzip-dev libintl icu icu-dev  libxml2-dev && \
	apk add --update nodejs ruby-full && \
	apk add --update yarn && \
	apk add --update python3 py3-setuptools py3-sphinx build-base && \
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
	chmod +x /usr/local/bin/composer && \
    rm -rf /var/cache/apk/* \

RUN gem install bundler -v '1.16.1'

# Install Composer
RUN composer --version
# Set timezone
#RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

RUN docker-php-ext-install zip pdo_mysql ldap curl gd sockets intl soap

RUN apk del curl openssl libpng libpng-dev curl-dev openldap-dev

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

# Add the keys and set permissions
RUN mkdir /root/.ssh && \
    echo "$ssh_prv_key" > /root/.ssh/id_rsa && \
    echo "$ssh_pub_key" > /root/.ssh/id_rsa.pub && \
    echo "$composer_auth" > /root/.composer/auth.json && \
    chmod 600 /root/.ssh/id_rsa && \
    chmod 600 /root/.ssh/id_rsa.pub && \
    chmod 600 /root/.composer/auth.json