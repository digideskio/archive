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

Finally, downlaod [[http://www.tcpdf.org/ TCPDF]] and extract it into app/libraries.

### Setup

Make the resources folder writeable:

	chmod -R 0777 app/resources

Set up three databases: test, dev, and production

Create a new file <code>app/config/bootstrap/connections.php</code>:

	<?php

	Connections::add('default', array(
		'development' => array(
			'type' => 'database',
			'adapter' => 'MySql',
			'host' => 'localhost',
			'login' => 'USER'
			'password' => 'PASSWORD',
			'database' => 'dev',
			'encoding' => 'UTF-8'
		), 
		'test' => array(
			'type' => 'database',
			'adapter' => 'MySql',
			'host' => 'localhost',
			'login' => 'USER',
			'password' => 'PASSWORD',
			'database' => 'test',
			'encoding' => 'UTF-8'
		),
		'production' => array(
			'type' => 'database',
			'adapter' => 'MySql',
			'host' => 'localhost',
			'login' => 'USER',
			'password' => 'PASSWORD',
			'database' => 'production',
			'encoding' => 'UTF-8'
		)
	));

	?>

If you are setting up a production site, add this line to your Apache virtal host file:

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
