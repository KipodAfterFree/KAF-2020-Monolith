FROM php:7.4.1-apache
# Install utilities
RUN apt-get update
RUN apt-get -y install ssh
RUN apt-get -y install mailutils
RUN apt-get -y install netcat
RUN apt-get -y install sudo
# Create users
RUN useradd -s /usr/sbin/nologin mailman

# Copy
COPY src /
# First permission change
RUN chown www-data:www-data -R /var/www/html
RUN chmod 771 -R /var/www/html

# Change permissions for specific directories
RUN chown mailman:mailman -R /home/mailman
RUN chmod 600 /home/mailman/.ssh/config

# SSH key permissions
RUN chown mailman -R /var/www/html/files/ssh
RUN chmod 400 /var/www/html/files/ssh/private.pem

# Mail service
RUN chown mailman:www-data -R /var/www/html/work/service/private

# Setup crontab
RUN sudo -u mailman crontab /home/mailman/crontab
RUN chmod +x /home/mailman/execute

# Change command permissions
RUN chmod +x /bin/cmd
# Command
CMD ["/bin/cmd"]
