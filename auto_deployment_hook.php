rm -rf /var/www/html/deployment/.*;
rm -rf /var/www/html/deployment/**;
git clone git@github.com:jack-freiermuth/deployment.git /var/www/html/deployment/;
chmod -R 775 /var/www/html/deployment;
chgrp -R www-data /var/www/html/deployment;