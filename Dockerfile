FROM php:8.2-apache

# 将项目文件复制到容器的 /public 目录
COPY ./ /var/www/html/

# 设置工作目录
WORKDIR /var/www/html/

