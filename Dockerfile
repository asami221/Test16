FROM php:8.2-fpm

# 必要パッケージのインストール（Node系は使わないなら削除）
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev

# Composerのインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /var/www/html

# アプリケーションファイルをコンテナにコピー
COPY . .

# Laravel依存のインストールとキャッシュクリア（npm不要）
RUN composer install && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear

# SQLiteファイルを生成（存在しないとRenderでエラーになる）
RUN mkdir -p database && touch database/database.sqlite

# ポート指定（Renderで使われるポート）
EXPOSE 10000

# 起動時コマンド（マイグレーションもついでに）
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
