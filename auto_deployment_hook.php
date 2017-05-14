<?php
exec( 'rm -Rf /var/www/html/deployment/;', $rm_output, $rm_return_var );
exec( 'git clone git@github.com:jack-freiermuth/deployment.git /var/www/html/deployment/;', $clone_output, $clone_return_var );
exec( 'chmod -R 775 /var/www/html/deployment;', $chmod_output, $chmod_return_var );
exec( 'chown -R root:apache /var/www/html/deployment;', $chown_output, $chown_return_var );

echo '<pre>';
echo '<br>rm_output: ';print_r($rm_output);
echo '<br>rm_return_var: ';print_r($rm_return_var);
echo'<br>';
echo '<br>clone_output: ';print_r($clone_output);
echo '<br>clone_return_var: ';print_r($clone_return_var);
echo'<br>';
echo '<br>chmod_output: ';print_r($chmod_output);
echo '<br>chmod_return_var: ';print_r($chmod_return_var);
echo'<br>';
echo '<br>chown_output: ';print_r($chown_output);
echo '<br>chown_return_var: ';print_r($chown_return_var);
echo'<br>';
echo '</pre>';
