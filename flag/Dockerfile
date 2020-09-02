FROM debian:buster-slim
# Expose port
EXPOSE 22
# Install sshd
RUN apt-get update
RUN apt-get install -y openssh-server
# Create the sshd directory
RUN mkdir /var/run/sshd
# Create the user
RUN useradd -m -d /home/admin -s /usr/sbin/nologin admin
RUN echo "admin:2t4ere4tc" | chpasswd
# Copy flag / motd
COPY configurations/motd.txt /etc/motd
# Set command
CMD ["/usr/sbin/sshd", "-D"]
