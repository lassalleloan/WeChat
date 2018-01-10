#!/bin/bash
#
# Prepare and run environment
# author: Loan Lassalle

# Get the web application archive
wget -O ~/Downloads/weChat.zip "https://github.com/lassalleloan/WeChat/archive/master.zip"

# Unzip the archive
unzip ~/Downloads/weChat.zip -d /var/www/html/

# Rename folder of web application
mv /var/www/html/WeChat-master /var/www/html/wechat

# Change the owner group of the folder
sudo chgrp -R apache /var/www/html/wechat

# Chnage policy for htaccess file
sudo sed -i '151s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf

# Remove unused folder
rm -rf ~/Downloads/weChat.zip

# Start the httpd service
sudo systemctl start httpd

# Open home page of web application in browser
xdg-open "http://localhost/wechat/controllers/init.php"