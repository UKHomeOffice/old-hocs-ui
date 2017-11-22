ID=$BUILD_NUMBER--$BUILD_ID
FILENAME=cts-app-build-$ID.tar
PACKAGE_NAME=cts-frontend-php
#VERSION=0.0.1_BETA
RPMARCH=noarch
SUMMARY="Home Office Correspondance Tracking System - PHP frontend to Alfresco API"
RELEASE_PATH=/var/www/cts
RPMTOPDIR=$WORKSPACE/PACKAGE/rpmstuff
RPMSPECFILE=$RPMTOPDIR/SPECS/$PACKAGE_NAME.spec

RPM_NAME="$PACKAGE_NAME-$PROJECT_VERSION-$BUILD_NUMBER.noarch.rpm"

#CURRENT_RELEASE_PATH=/var/www/cts/current
#SHARED_ASSETS_PATH=/var/www/cts/shared

for dir in BUILD RPMS SOURCES SRPMS
do
 [[ -d $RPMTOPDIR/$dir ]] && rm -Rf $RPMTOPDIR/$dir
  mkdir $RPMTOPDIR/$dir
done

rm -fr $WORKSPACE/var/www/cts/var/cache/*
rm -fr $WORKSPACE/var/www/cts/var/logs/*

echo $PROJECT_NAME > $WORKSPACE/BUILD_ID && \
echo $PROJECT_VERSION-$BUILD_NUMBER >> $WORKSPACE/BUILD_ID && \
echo $ID >> $WORKSPACE/BUILD_ID

tar -cf $WORKSPACE/PACKAGE/rpmstuff/SOURCES/build.tar --exclude='./PACKAGE' . && \

sed -i "s/VERSION_REPLACE/$PROJECT_VERSION/g" $RPMSPECFILE && \
sed -i "s/RELEASE_REPLACE/$BUILD_NUMBER/g" $RPMSPECFILE && \

$WORKSPACE/PACKAGE/scripts/tar2rpm.sh --target $RELEASE_PATH --fileuser deployment --filegroup apache -A --print $RPMTOPDIR/SOURCES/build.tar |tail -n+30 >> $RPMSPECFILE && \

rpmbuild --define '_topdir '$RPMTOPDIR -bb $RPMSPECFILE && echo "BUILD SUCCESS"

#scp -i /var/lib/jenkins/deploy_rsa $RPMTOPDIR/RPMS/$RPMARCH/$RPM_NAME deployment@$TARGET_SERVER:/tmp/
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER sudo rpm -Uvh /tmp/$RPM_NAME

#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER mkdir /tmp/$ID $RELEASE_PATH
#scp -i /var/lib/jenkins/deploy_rsa build.tar.gz deployment@$TARGET_SERVER:/tmp/$ID
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER tar -xzf /tmp/$ID/build.tar.gz -C $RELEASE_PATH

#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER rm -rf $RELEASE_PATH/app/config/parameters.yml
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER ln -s $SHARED_ASSETS_PATH/app/config/parameters.yml $RELEASE_PATH/app/config/parameters.yml
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER ln -n -f -s $RELEASE_PATH $CURRENT_RELEASE_PATH
## ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER 'cd /var/www/cts/releases && (ls -t|head -n 5;ls)|sort|uniq -u|sed -e '\''s,.*,"&",g'\''|xargs rm -rf '
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER "cd $CURRENT_RELEASE_PATH && bin/console assetic:dump --env=prod"

#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER rm -rf $RELEASE_PATH/var/cache
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER rm -rf $SHARED_ASSETS_PATH/var/cache/*
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER ln -s $SHARED_ASSETS_PATH/var/cache $RELEASE_PATH/var/cache
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER rm -rf $RELEASE_PATH/var/logs
#ssh -i /var/lib/jenkins/deploy_rsa deployment@$TARGET_SERVER ln -s $SHARED_ASSETS_PATH/var/logs $RELEASE_PATH/var/logs

