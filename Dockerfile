FROM ubuntu:22.04

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN echo 'APT::Install-Suggests "0";' >> /etc/apt/apt.conf.d/00-docker
RUN echo 'APT::Install-Recommends "0";' >> /etc/apt/apt.conf.d/00-docker
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
 && apt-get install -y \
    software-properties-common \
    gpg-agent \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN add-apt-repository ppa:ondrej/php -y \
 && apt-get update \
 && apt-get install -y \
    php8.3-cli \
    php8.3-curl \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    build-essential \
    ca-certificates \
    curl \
    git \
    zip \
    unzip \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_PROCESS_TIMEOUT=600
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /www

WORKDIR /www
