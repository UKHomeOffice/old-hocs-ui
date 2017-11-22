scp -i /var/lib/jenkins/deploy_rsa $WORKSPACE/PACKAGE/rpmstuff/RPMS/noarch/cts-frontend-php-${PROJECT_VERSION}-${BUILD_NUMBER}.noarch.rpm deployment@${TARGET_SERVER}:/tmp/ && \
ssh -i /var/lib/jenkins/deploy_rsa deployment@${TARGET_SERVER} sudo rpm -Uvh /tmp/cts-frontend-php-${PROJECT_VERSION}-${BUILD_NUMBER}.noarch.rpm

