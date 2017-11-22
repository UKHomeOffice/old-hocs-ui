#!/bin/sh

short_hostname=`/bin/hostname -s | awk -F'-' '{print $3}'`

# Stop any new connections coming to this server
curl http://pxy01:9200/drain?${short_hostname}
curl http://pxy02:9200/drain?${short_hostname}

# Sleep 10 seconds to allow existing connections to complete
sleep 10

# Stop ALL connections coming to this server
curl http://pxy01:9200/maint?${short_hostname}
curl http://pxy02:9200/maint?${short_hostname}

# Upgrade PHP
RPM_BUILD_ID=`cat BUILD_ID.txt`
sudo rpm -Uvh cts-frontend-php-1-${RPM_BUILD_ID}.noarch.rpm
sudo service php-fpm restart
sudo service nginx restart

# Allow connections to come to this server
curl http://pxy01:9200/ready?${short_hostname}
curl http://pxy02:9200/ready?${short_hostname}

