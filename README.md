# virtualphp
* Linux-Ubuntu16.04
* PHP5.6 (ext:xml,mbstring,gd,mysql,mysqli,pdo)
* Apache2.4 / nginx
* MariaDB 10
* PHP Framework : ThinkPHP 3.2.3
* pure-ftpd

* this project web root : /home/www/html
* Apache document root : /home/www
* Apache site config root : /etc/apache2/sites-enabled/

* change system sudoers
```console
sudo visudo
```
* add content:  `www-data ALL=(ALL) NOPASSWD: /bin/chown, (ALL) NOPASSWD: /bin/mv, (ALL) NOPASSWD: /etc/init.d/apache2`

* edit /Application/Conf/config.php for your domain and IP and emailServer

use your host create a virtual space domain

Demo domain: <a title="demaxi virtualhost" href="http://www.demaxi.cn/" target="_blank">www.demaxi.cn</a>
