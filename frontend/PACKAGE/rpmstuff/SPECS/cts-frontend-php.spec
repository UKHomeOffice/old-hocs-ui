Name            : cts-frontend-php
Summary         : Home Office Correspondance Tracking System - PHP frontend to Alfresco API
Version         : VERSION_REPLACE
Release         : RELEASE_REPLACE

Group           : Applications/File
License         : (c)Home Office

BuildArch       : noarch
BuildRoot       : %{_tmppath}/%{name}-%{version}-root

# Use "Requires" for any dependencies, for example:
Requires        : httpd, php55w

#Do not terminate if there are files in the buildroot that are not packed into the rpm
#%define _unpackaged_files_terminate_build 0

# Description gives information about the rpm package. This can be expanded up to multiple lines.
%description
Home Office Correspondance Tracking System - PHP frontend to Alfresco API

# Prep is used to set up the environment for building the rpm package
# Expansion of source tar balls are done in this section
%prep
rm -fr $RPM_BUILD_ROOT

# Used to compile and to build the source
%build
mkdir -p $RPM_BUILD_ROOT
cp %{_sourcedir}/build.tar $RPM_BUILD_ROOT/build-archive.tar

# The installation.
# We actually just put all our install files into a directory structure that mimics a server directory structure here
%install
mkdir -p $RPM_BUILD_ROOT/var/www/cts
cd $RPM_BUILD_ROOT/var/www/cts
tar -xf $RPM_BUILD_ROOT/build-archive.tar
rm $RPM_BUILD_ROOT/build-archive.tar
#rm -fr $RPM_BUILD_ROOT

%post
export RELEASE_PATH=/var/www/cts 
cd $RELEASE_PATH && bin/console assetic:dump --env=prod
rm -rf $RELEASE_PATH/var/cache/*
chown deployment:apache -R $RELEASE_PATH/var/cache
chown deployment:apache -R $RELEASE_PATH/var/logs
chmod g+rwx $RELEASE_PATH/var/cache
chmod g+rwx $RELEASE_PATH/var/logs
setfacl -dm "u:apache:rxw" $RELEASE_PATH/var/cache
setfacl -dm "u:apache:rxw" $RELEASE_PATH/var/logs
setfacl -Rm "u:apache:rxw" $RELEASE_PATH/var/cache
setfacl -Rm "u:apache:rxw" $RELEASE_PATH/var/logs

%clean
rm -fr $RPM_BUILD_ROOT

# Contains a list of the files that are part of the package
# See useful directives such as attr here: http://www.rpm.org/max-rpm-snapshot/s1-rpm-specref-files-list-directives.html
%files
%config(noreplace) /var/www/cts/app/config/parameters.yml
