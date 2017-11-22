Name: cts-frontend-php
Summary: Home Office Correspondence Service System - PHP frontend to Alfresco API
Version: 1
License: (c)Home Office
Group: Development/Library

BuildRoot: %{_tmppath}/%{name}-root
Source: %{name}-%{version}.zip
BuildRequires: php >= 5.5, php-xml
Requires: php >= 5.5, php-xml
BuildArch: noarch

%define build_number %(echo $BUILD_NUMBER)
%define git_commit %(echo $GIT_COMMIT)


Release:  %{build_number}

%description
Home Office Correspondence Service - PHP frontend to Alfresco API

export TMPDIR=~
%prep
%setup -q
# Install the vendors (optimised autoloader, and no interaction)
curl -s https://getcomposer.org/installer | php
./composer.phar install --prefer-dist -o -n --no-dev --no-progress --no-scripts

%build

export SYMFONY_ENV=prod
# Run post-install scripts defined in composer config (build bootstrap, asset install)
./composer.phar run-script post-install-cmd --no-dev --no-interaction

# Update the version used in asset output scheme
sed -i -e 's/\(assets_version: v=[ ]*\)\([a-zA-Z0-9_]*\)\(.*\)$/\1%{version}\3/g' app/config/config.yml

# dump the application assets
bin/console assets:install 
bin/console assetic:dump --env=prod

sed -i "s/buildidnot_set/%{build_number}/g" app/config/build.yml
sed -i "s/hashnot_set/%{git_commit}/g" app/config/build.yml

# Remove;
# non production controllers (app._{env}.php)
# build files
# test config files
# Remove the parameters.yml file, needed for the above install_prod_assets, but not needed on production
# as the 'real' file is managed by puppet
rm -f web/app_*.php composer.* behat.yml phpspec.yml .travis.yml app/config/parameters.yml
rm -rf var/cache/prod
mkdir -p var/cache/prod

%install
[ "$RPM_BUILD_ROOT" != "/" ] && rm -rf $RPM_BUILD_ROOT

export INSTALL_ROOT=$RPM_BUILD_ROOT

# Install application files
install -d -m 755 $RPM_BUILD_ROOT/var/www/cts
cp -R * $RPM_BUILD_ROOT/var/www/cts

%post
/bin/ln -s /var/www/cts/web/app.php /var/www/cts/web/app_dev.php
pushd /var/www/cts 2> /dev/null
/bin/rm -rf /var/www/cts/var/cache/*/
/usr/bin/php bin/console cache:clear --env=prod
popd > /dev/null
chown -R apache:apache /var/www/cts/var

%preun
/bin/rm -f /var/www/cts/web/app_dev.php

%postun
# Its an upgrade - so delete the existing cache, and make a new empty one
if [ $1 = 1 ]; then
  pushd /var/www/cts 2> /dev/null
  /bin/rm -rf /var/www/cts/var/cache/*/
  /usr/bin/php bin/console cache:clear --env=prod
  popd > /dev/null
  chown -R apache:apache /var/www/cts/var
fi

%triggerpostun -- %{name}

%clean
#[ "$RPM_BUILD_ROOT" != "/" ] && rm -rf $RPM_BUILD_ROOT

%files
%dir /var/www/cts
%defattr(-,root,root,-)
/var/www/cts
%attr(755, apache, apache) %dir /var/www/cts/var/cache
%attr(755, apache, apache) %dir /var/www/cts/var/cache/prod
%attr(755, apache, apache) %dir /var/www/cts/var/logs
