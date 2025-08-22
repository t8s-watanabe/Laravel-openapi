FROM php:8.3.0-fpm
EXPOSE 5173
EXPOSE 5000
# Composer をインストール
COPY --from=composer:2.8.4 /usr/bin/composer /usr/local/bin/composer
# タイムゾーンを設定
ENV TZ Asia/Tokyo
# コンテナ内で動作していることを示す環境変数
ENV RUN_IN_CONTAINER="True"
# Composer をスーパーユーザーで動作可能にする
ENV COMPOSER_ALLOW_SUPERUSER 1
# システムの言語を英語（UTF-8）に設定
ENV LANG en_US.UTF-8
 # ユーザー情報を引数として設定
ARG UNAME=t8s
ARG UGROUP=t8s
ARG UID=1000
ARG GID=1000

# 必要なパッケージをインストールし、PHP拡張を有効化
RUN apt-get update \
    && apt-get -y install --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        locales \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        zip \
        bcmath \
        sockets \
        pcntl \
    && localedef -f UTF-8 -i en_US en_US.UTF-8 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js 22をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && npm install -g vite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 新しいユーザーを作成し、ディレクトリを設定
RUN adduser --disabled-password --gecos "" $UNAME && \
    usermod  --uid $UID $UNAME && \
    groupmod --gid $GID $UGROUP && \
    mkdir -p /git/openapi && \
    chown -R $UNAME:$UGROUP /git/openapi && \
    chmod 2775 /git/openapi

# PHP-FPMの設定ファイルを配置
COPY php_fpm/php.ini /usr/local/etc/php/php.ini
COPY php_fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY php_fpm/docker.conf /usr/local/etc/php-fpm.d/docker.conf
 
USER $UNAME
WORKDIR /git/openapi
# CMD [ "npm", "run", "dev:tailwind" ]
