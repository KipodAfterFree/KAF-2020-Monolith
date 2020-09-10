FROM debian:buster-slim
# Expose port
EXPOSE 22
# Install sshd
RUN apt-get update
RUN apt-get install -y openssh-server
# Create the sshd directory
RUN mkdir /var/run/sshd
# Create the user
RUN useradd -m -d /home/mailman -s /usr/sbin/nologin mailman
# Configure ssh
RUN echo "StrictModes no" >> /etc/ssh/sshd_config
RUN echo "PasswordAuthentication no" >> /etc/ssh/sshd_config
# Copy keys
COPY configurations/authorized_keys /home/mailman/.ssh/authorized_keys
# Copy flag / motd
COPY configurations/motd.txt /etc/motd
# Set command
CMD ["/usr/sbin/sshd", "-D"]
