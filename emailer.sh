#!/bin/bash

rm -rf /var/www/vhosts/demos/emailer/.*;
rm -rf /var/www/vhosts/demos/emailer/**;
git clone git@github.com:UnityWorksMedia/emailer.git /var/www/vhosts/demos/emailer/
