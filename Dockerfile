FROM php:8.2-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev \
    npm \
    nodejs

# Composer インストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www/html

# アプリケーションのコードをコピー
COPY . .

# Laravel セットアップ
RUN composer install && npm install && npm run build && php artisan config:clear

# SQLiteファイルをプロジェクト内に生成（ないとエラーになることがある）
RUN mkdir -p database && touch database/database.sqlite

# ポート開放
EXPOSE 10000

# Laravel アプリケーション起動コマンド
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "10000"]
