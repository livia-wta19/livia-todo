FROM php:8.2-cli

RUN apt-get update && apt-get install -y libsqlite3-dev && \
    docker-php-ext-install pdo pdo_sqlite && \
    rm -rf /var/lib/apt/lists/*

COPY todo/ /app/

# Data dir outside web root (mountuj ako Railway Volume na /data)
RUN mkdir -p /data && chmod 777 /data

WORKDIR /app
EXPOSE 8080
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app"]
