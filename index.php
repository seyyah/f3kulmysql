<?php

/**
	This application is not meant for production use. Its only purpose is to
	serve as a sample code to help you get acquainted with the Fat-Free
	Framework. Padding this blog software with a lot of bells and whistles
	would be nice, but it would also make the entire learning process
	lengthier and more complicated.
**/

// Use the Fat-Free Framework
require_once 'lib/F3.php';

/**
	Setting the Fat-Free global variable RELEASE to TRUE will suppress stack
	trace in HTML error page. Web server's error log will still contain
	complete stack trace.

	If you're writing your own application and still debugging it, you might
	want to set this Fat-Free variable to FALSE. The stack trace can help
	a lot in program testing.
**/
F3::set('RELEASE',FALSE);

// Use custom 404 page
F3::set('E404','layout.htm');

// Path to our Fat-Free import files
F3::set('IMPORTS','inc/');

// Path to our CAPTCHA font file
F3::set('FONTS','fonts/');

// Path to our templates
F3::set('GUI','gui/');

// Another way of assigning values to framework variables
F3::mset(
	array(
		'site'=>'Kullanıcı Veritabanı',
		'data'=>'db/demo.db'
	)
);

// Common inline Javascript
F3::set('extlink','window.open(this.href); return false;');

/**
	Create database connection; The demo database is within our Web
	directory but for production use, a non-Web accessible path is highly
	recommended for better security.

	Fat-Free allows you to use any database engine - you just need the DSN
	so the framework knows how to communicate with it. Migrating to another
	engine should be next to easy. If you stick to the standard SQL92
	command set (no engine-specific extensions), you just have to change the
	next line. For this demo, we'll use the SQLite engine, so there's no
	need to install MySQL on your server.
**/
F3::set('DB',
	array(
		'dsn'=>'mysql:host=localhost;port=3306;dbname=f3',
		'user'=>'f3',
		'password'=>'secret'
	)
);
F3::call(':db');

/*F3::set('DB',array('dsn'=>'sqlite:{@data}'));
if (!file_exists(F3::get('data')))
	// SQLite database doesn't exist; create it programmatically
	// Call db.php inside the inc/ folder
	F3::call(':db');*/

// Define our main menu; this appears in all our pages
F3::set('menu',
	array_merge(
		array(
			'Ana sayfa'=>'/'
		),
		// Toggle login/logout menu option
		F3::get('SESSION.user')?
			array(
				'Hakkında'=>'/about',
				'Çıkış'=>'/logout'
			):
			array(
				'Giriş'=>'/login'
			)
	)
);

/**
	Let's define our routes (HTTP method and URI) and route handlers;
	Unlike other frameworks, Fat-Free's code elegance makes it easy for
	novices and experts alike to understand what these lines do!
**/
F3::route('GET /',':showkul');

// Minify CSS; and cache page for 60 minutes
F3::route('GET /min',':minified',3600);

// Cache the "about" page for 60 minutes; read the full documentation to
// understand the possible unwanted side-effects of the cache at the
// client-side if your application is not designed properly
F3::route('GET /about',':about',3600);

// This is where we display the login page
F3::route('GET /login',':login',3600);
	// This route is called when user submits login credentials
	F3::route('POST /login',':auth');

// New blog entry
F3::route('GET /create',':createkul');
	// Submission of blog entry
	F3::route('POST /create',':savekul');

// Edit blog entry
F3::route('GET /edit/@tc',':editkul');
	// Update blog entry
	F3::route('POST /edit/@tc',':updatekul');

// Delete blog entry
F3::route('GET /delete/@tc',':erasekul');

// Logout
F3::route('GET /logout',':logout');

// RSS feed
F3::route('GET /rss',':rss');

// Generate CAPTCHA image
F3::route('GET /captcha',':captcha');

// Execute application
F3::run();

/**
	The function below could have been saved as an import file (render.php)
	loaded by the F3::route method like the other route handlers; but let's
	embed it here so you can see how you can mix and match MVC functions
	and import files.

	Although allowed by Fat-Free, functions like these are not recommended
	because they pollute the global namespace, specially when it's defined
	in the main controller. In addition, the separation of the controller
	component and the business logic becomes blurred when we do this - not
	good MVC practice.

	It's all right to define the function here if you're still figuring out
	the structural layout of your application, but don't trade off coding
	convenience for good programming habits.
**/
function render() {
	// layout.htm is located in the directory pointed to by the Fat-Free
	// GUI global variable
	echo F3::serve('layout.htm');
}

?>
