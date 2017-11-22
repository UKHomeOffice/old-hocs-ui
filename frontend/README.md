# CTS App

This is the PHP user interface counter-part to https://github.com/UKHomeOffice/CTS-Alfresco.

This is a Symfony2 project, with dependencies managed using composer. Feel free to run this however you want, but the following is recommended. Using a puppet provisioned vagrant box (https://github.com/UKHomeOffice/cts-frontend-puppet)

## Development setup
### Prerequisites

Ensure you have installed the following before doing anything else: -

- VirtualBox (https://www.virtualbox.org/wiki/Downloads)
- Vagrant (https://www.vagrantup.com/downloads.html)
- Vagrant virtual box guest additions plugin (https://github.com/dotless-de/vagrant-vbguest)
- Ensure no firewall restrictions are in place which will prevent connection to Alfresco.  In OSX, under "Security & Privacy", either add a rule or turn it off

### Commands

Run the following commands to setup the development environment using a puppet provisioned vagrant box.

```
git clone https://github.com/UKHomeOffice/cts-frontend-puppet.git
cd cts-frontend-puppet
git clone https://github.com/UKHomeOffice/CTS-App.git cts-app
vagrant up
vagrant ssh
cd /vagrant/cts-app
composer install
bin/console assets:install --symlink
bin/console assetic:dump
```

### Local config

It is recommended to add a hosts file entry to map the vagrant box IP address to a more user-friendly url. Edit /etc/hosts on OSX, add the following: -

```
192.168.40.13   cts-app.local
```

### Browse


Load up your favourite browser and go to http://cts-app.local/app_dev.php/login. Default username and password is admin / admin.
 