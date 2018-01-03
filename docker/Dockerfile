# Dockerfile for CentOS STI development
# author: Loan Lassalle

FROM centos/systemd

# sudo sed -i '151s/AllowOverride None/AllowOverride All/'; \

RUN yum -y install httpd php php-pdo php-pdo_mysql wget unzip sudo; \
    yum clean all; \
    systemctl enable httpd.service; \
    mkdir -p /var/www/databases; \
    chgrp -R apache /var/www/databases; \
    chmod g+w /var/www/databases

EXPOSE 80

CMD ["/usr/sbin/init"]
