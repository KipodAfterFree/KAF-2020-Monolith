FROM php:7.4.1-apache
# Install utilities
RUN apt-get update
RUN apt-get -y install ssh
# Copy
COPY src /
# Chmod
RUN chmod 777 -R /var/www/html