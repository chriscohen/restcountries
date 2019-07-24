# Ubuntu 18.04 Server LTS with PHP 7.3 and Apache2. Includes APCu for PHP. Also includes xdebug
# in case it's needed for development purposes.
FROM ubuntu:18.04

# Tell the OS that it cannot ask for prompts.
ENV DEBIAN_FRONTEND noninteractive

# Install all prerequisites.
RUN apt-get -y update && apt-get -y install \
    apt-transport-https \
    ca-certificates \
    software-properties-common \
    wget

# Add custom package PPA for 7.3 with xdebug. Update again.
RUN apt-get -y update && add-apt-repository ppa:ondrej/php && apt-get -y update

# Install all packages.
RUN apt-get -y install \
  apache2 \
  libapache2-mod-php7.3 \
  php7.3 \
  php7.3-apcu \
  php7.3-cli \
  php7.3-curl \
  php7.3-gd \
  php7.3-imagick \
  php7.3-intl \
  php7.3-json \
  php7.3-mbstring \
  php7.3-memcached \
  php7.3-mysql \
  php7.3-xdebug \
  php7.3-xml \
  php7.3-zip \
  vim

# Clean up.
RUN apt-get -y clean

# Copy the xdebug configuration file.
COPY xdebug.ini /etc/php/7.3/mods-available/xdebug.ini

# Copy the 000-default file for Apache2.
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the PHP APCu module, the Apache2 rewrite module and restart Apache.
RUN phpenmod apcu && a2enmod rewrite && service apache2 restart

# Allow external access on port 80.
EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
