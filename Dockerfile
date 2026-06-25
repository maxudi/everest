FROM php:8.2-apache

# Instala a extensão PDO MySQL necessária para o config.php
RUN docker-php-ext-install pdo pdo_mysql

# Ativa o módulo rewrite do Apache (caso precise no futuro)
RUN a2enmod rewrite

# Copia os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Garante que a pasta de uploads tenha permissão de escrita para o Apache
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/