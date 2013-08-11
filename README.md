# Welcome to the Archive

The Archive tracks Artworks, Architectural Projects, Exhibitions, and Publications for one or more artists.

### Requirements

* Apache
* PHP 5.3+
* MySQL
* ImageMagick
* imagick and mod_xsendfile

### Installation

First, clone this repository into your public webroot:

    git clone git@github.com:Fabricatorz/fakework.git .
	git submodule init
	git submodule update

Clone these extra libraries if you want support for PDFs and database migrations:

	cd app/libraries
	git clone https://github.com/nashape/li3_ruckusing_migrations.git
	git clone https://github.com/nashape/ruckusing-migrations.git
	git clone git://github.com/masom/li3_pdf.git

Finally, download [TCPDF](http://www.tcpdf.org) and extract it into app/libraries.

After you install ImageMagick for your platform, you also need the imagick extension.

	sudo apt-get install php5-dev libmagick9-dev
	sudo pecl channel-update pecl.php.net
	sudo pecl install Imagick

Edit `/etc/php5/apache2/conf.d/imagick.ini` and add the line: 

	extension=imagick.so

To check the settings you can run:

	sudo apache2ctl configtest

Then restart Apache.

### Setup

Make the resources folder writeable:

	chmod -R 0777 app/resources

Create a new database user for the archive using MySQL shell:

	CREATE USER 'USER'@'localhost' IDENTIFIED BY 'PASSWORD';

You must create a minimum of two databases for 'development' and 'test' purposes. You can also add a 'production' database if you have the need. Create each database in MySQL shell as following:

	CREATE DATABASE development DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

Next, give your database user the necessary privileges:

	GRANT ALL PRIVILEGES ON development.* TO 'USERNAME'@'localhost';

Repeat the above MySQL commands for 'test' and 'production' databases.

Rename the connections-sample.php file to connections.php:

	cp app/config/bootstrap/connections-sample.php app/config/bootstrap/connections.php

Edit your new connections.php file, and fill in the access details for your databases.

In the connections.php file you can also enable or disable the Architecture and Inventory modules depending on your environment:

	Environment::set('production', array('inventory' => false));
	Environment::set('development', array('inventory' => true));
	Environment::set('test', array('inventory' => true));

	Environment::set('production', array('architecture' => true));
	Environment::set('development', array('architecture' => true));
	Environment::set('test', array('architecture' => true));

If you are setting up a production site, add this line to your Apache virtual host file:

	SetEnv LITHIUM_ENVIRONMENT "production"

To set up your filesystem, it is enough to:

	cp app/config/bootstrap/filesystems-sample.php app/config/bootstrap/filesystems.php

To set up the database, I prefer to use the original ruckusing migrations instead of the Lithium console command. First edit app/libraries/ruckusing-migrations/config/database.inc.php and add the details for the databse you want to use. Then:

	cp app/config/db/migrate/* app/libraries/ruckusing_migrations/db/migrate
	cd app/libraries/ruckusing_migrations
	php main.php db:setup
	php main.php db:migrate

Finally, navigate to the /login page of your new site. You will be prompted to create a new admin account.

Happy Archiving!

### Customizations

There are some customization options that are set via environment variables.

The most important and recommened customization is to add the Hmac strategy to the cookie adapter. Edit the `app/config/bootstrap/connections.php` file with the following:

	$session = array('default' => array(
		'adapter' => 'Cookie',
		'strategies' => array('Hmac' => array('secret' => 'YOUR_SECRET')),
		'name' => 'Archive',
	));

	Environment::set('production', compact('session'));

Other session variables are:

	'inventory =>       true/false
	'architecture' =>   true/false
	
	'artworks' => array(
			'classifications' => array(
				'Artwork Classification Name' => array(
					'class' => 'two-d three-d four-d' //to be able to enter height/width, depth, time, respectively
				)
			),
			'artist' => array(
				'default' => 'Default Artist Name'
			)
	)	
