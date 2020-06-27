FROM php:7.4.1-apache
# Update
#RUN apt-get update
# Install supervisor
#RUN apt-get -y install supervisor
# Install apache2 & php
#RUN apt-get -y install apache2 php
# Copy
COPY src /
# Chmod
RUN chmod 777 -R /var/www/html