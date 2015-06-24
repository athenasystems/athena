Athena CE - Community Edition Installation
========
Do the following as root, or via sudo :-

`wget -N https://raw.githubusercontent.com/athenasystems/athena/master/athena-setup && bash athena-setup`


Email Set Up
=========
In order to send emails from this system you will need to fill in your SMTP service details and add a line in your **cron tab**.

Once you are logged in tot the Intranet, choose the "System" navigation link, and then click on "Company Details".  
At the bottom of that page you can fill in your SMTP Server details.

At a command prompt when logged in as root type

`crontab -e`

And add some thing like

`*/5 * * * * /usr/bin/php /srv/athenace/lib/cron/sendmail.php >> /srv/athenace/var/logs/maillog 2>&1`

This will send one email every 5 minutes, and save a log to 'maillog' in the Athena Logs directory.  

Please adjust if your athena installation is in a different location.

