# Welcome to the Archive

The Archive tracks Artworks, Architectural Projects, Exhibitions, and Publications for one or more artists.

### Requirements

* Apache
* PHP 5.3+
* MySQL
* ImageMagick
* imagick and mod\_xsendfile
* Ghostscript
* exiftool

### Installation

First, clone this repository into your public webroot:

    git clone https://github.com/Fabricatorz/archive.git .
	git submodule init
	git submodule update

After you install ImageMagick for your platform, you also need the imagick extension.

	sudo apt-get install php5-dev libmagick9-dev
	sudo pecl channel-update pecl.php.net
	sudo pecl install Imagick

(In newer version of Ubuntu, `libmagick9-dev` has been replaced by `graphicsmagick-libmagick-dev-compat`.)

An alternative way to get the correct dependencies:

	sudo apt-get install php5-dev libmagickwand-dev
	sudo pecl channel-update pecl.php.net
	sudo pecl install imagick-beta

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

You must create at least one 'production' database for the site. You can also add 'development' and 'test' databases if you have the need. Create each database in MySQL shell as following:

	CREATE DATABASE production DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

Next, give your database user the necessary privileges:

	GRANT ALL PRIVILEGES ON production.* TO 'USERNAME'@'localhost';

Repeat the above MySQL commands for 'test' and 'development' databases.

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

If you are setting up a development site, add this line to your Apache virtual host file:

	SetEnv LITHIUM_ENVIRONMENT "development"

To set up your filesystem, first make a copy of the sample file, which in normal set-ups will not have to be edited:

	cp app/config/bootstrap/filesystems-sample.php app/config/bootstrap/filesystems.php

You must install mod\_xsendfile and then enable it in your Apache host file:

	XSendFile On

In addition, you must also tell XSendFile what directory it is allowed to send files from. The default location in `filesystems.php` is the `app/resources/archive` directory. Add a line to your Apache host file specifying the path, similar to this:

	XSendFilePath /srv/www/example.com/public/app/resources/archive

### Migrations

To set up the database, you need to run all migration files against it. Migrations scripts are stored in `app/config/db/migrate/`.

First, make a copy of the included ruckusing-migrations database file:

    cp app/config/db/database.inc.php ruckusing.conf.php

Edit the file, and fill in your database credentials again, and save it. Next, make a copy of the main ruckusing-migrations script:

	cp libraries/ruckusing-migrations/ruckus.php .

Use the script to setup the database and run the migrations:

	php ruckus.php db:setup ENV=production
	php ruckus.php db:migrate ENV=production

Finally, navigate to the /login page of your new site. You will be prompted to create a new admin account.

Happy Archiving!

### Customizations

There are some customization options that can be set in `app/config/bootstrap/connections.php`.

The most important customization is to include a "secret" which is used to sign browser cookies for the application:

	define('LITHIUM_APP_SECRET', 'YOUR_SECRET');

Other customizations are set via Environment variables, and can therefore be tuned for your development and production environments. For example, to enable the inventory feature in your production environment:

	Environment::set('production', array('inventory' => true));

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
				'default' => 'Default Artist Name' //can be set to false to disable this feature
			),
			'order' => '(CASE WHEN artist = \'\' THEN 1 ELSE 0 END), artist ASC, title, earliest_date DESC, materials', // SQL order by clause for artworks
			'filter' => array('location' => array('!=' => 'Out')), // exclude artworks in index views that match this condition
			'search' => array(
				'limit' => 'all' // Don't paginate search results; also accepts an integer
			),
	)

	'search' => array(
		'limit' => 'all' // Don't paginate search results; also accepts an integer
	)
