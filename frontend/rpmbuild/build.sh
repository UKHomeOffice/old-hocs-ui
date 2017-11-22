#!/bin/bash

#===============================================================================
#
#          FILE:  build.sh
#
#         USAGE:  ./build.sh [[-b branch ] | [-h]]
#
#   DESCRIPTION:  Sets up the git repository and runs the RPM build.  The temp
#                 directory is set to the users home dir intentionally, this is
#                 to mitigate virtual machine problems with file shares and hard
#                 links.
#
#       OPTIONS:  -h | -b
#  REQUIREMENTS:  git, curl
#        AUTHOR:  Adam Lewis, Adam.Lewis@digital.homeoffice.gov.uk
#       COMPANY:  Home Office
#       CREATED:  2016/04/26
#===============================================================================

echo "BUILD.SH SCRIPT FOR JENKINS"

#BRANCH=$GIT_COMMIT
BRANCH=`git log --pretty=format:'%h' -n 1`

APPLICATION=cts-frontend-php
VERSION=1
RELEASE=$BUILD_NUMBER
FILENAME=$APPLICATION-$VERSION

RPMDIR="$WORKSPACE/rpmbuild"
LOCALDIR=$RPMDIR

PACKAGE_NAME=cts-frontend-php
RPMARCH=noarch
SUMMARY="Home Office Correspondance Tracking System - PHP frontend to Alfresco API"
RELEASE_PATH=$WORKSPACE/RELEASE
RPMSPECFILE=$LOCALDIR/SPECS/rpm.spec
RPM_NAME="$PACKAGE_NAME-$BUILD_NUMBER.noarch.rpm"
BUILD_ID=$PACKAGE_NAME.$BUILD_NUMBER


echo "Creating RPM directories"
for dir in BUILD RPMS SOURCES SRPMS RELEASE
do
 [[ -d $RPMDIR/$dir ]] && rm -Rf $RPMDIR/$dir
  mkdir $RPMDIR/$dir
done


echo "Removing any old build files"
rm -f $RPMDIR/SOURCES/*

echo "Creating Git archive"
echo $BRANCH
echo $FILENAME
cd $WORKSPACE
git archive $BRANCH --prefix="$FILENAME/" --format=zip -o $RPMDIR/SOURCES/$FILENAME.zip

echo "Building RPM"
rpmbuild -v --define '_topdir '$RPMDIR -bb $RPMSPECFILE

echo "BUILD FINISHED!"
