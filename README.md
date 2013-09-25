MediaCenter-Portal
==================

Access any web based software to control your home and media needs.

Required:
  Apache web server with php 5+ with curl
  
0.  install prerequisites:   
sudo apt-get install git  
sudo apt-get install apache2
sudo apt-get install php5 libapache2-mod-php5 php5-curl
sudo mkdir /var/www/MediaCenter

  
1. Download:   git clone git://github.com/elcabong/MediaCenter-Portal.git /var/www/MediaCenter/
2. Permissions: sudo chown -R www-data:www-data /var/www/MediaCenter/sessions
3. browse to your http://webserver/MediaCenter/


