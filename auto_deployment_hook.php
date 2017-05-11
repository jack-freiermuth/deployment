rm -Rf /var/www/html/deployment/;
git clone git@github.com:jack-freiermuth/deployment.git /var/www/html/deployment/;
chmod -R 775 /var/www/html/deployment;
chown -R root:apache /var/www/html/deployment;