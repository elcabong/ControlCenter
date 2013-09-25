ControlCenter
==================

Access any web based software to control your home and media needs.

Required:
  Apache web server with php 5+ with curl
  
0.  install prerequisites:   
sudo apt-get install git  
sudo apt-get install apache2
sudo apt-get install php5 libapache2-mod-php5 php5-curl

sudo mkdir /var/www/ControlCenter

  
1. Download:   git clone git://github.com/elcabong/ControlCenter.git /var/www/ControlCenter/


2. Permissions: 
sudo chown -R www-data:www-data /var/www/ControlCenter/sessions

sudo chown -R www-data:www-data /var/www/ControlCenter/media


3. browse to your http://webserver/ControlCenter/


