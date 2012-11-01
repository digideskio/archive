<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Archive &gt; <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('bootstrap.min.css', 'bootstrap-responsive.min.css', 'app.css')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	
	<?php echo $this->html->script(array('jquery.min.js')); ?>

	<?php echo $this->html->script(array(
		'jquery.flot.js',
		'jquery.flot.resize.js',
		'jquery.flot.pie.js'
	)); ?>
	
		<?php echo $this->html->script(array(
			'plupload.js',
			'plupload.gears.js',
			'plupload.silverlight.js',
			'plupload.flash.js',
			'plupload.browserplus.js',
			'plupload.html4.js',
			'plupload.html5.js'
		)); ?>
	
</head>
<body class="app">

	<?php echo $this->_render('element', 'navbar'); ?>

	<div id="container" class="container">
	
		<div id="main"  class="row" >
		
			<?php echo $this->_render('element', 'sidebar'); ?>
	
			<div id="content" class="span10">
				<?php echo $this->content(); ?>
			</div>
		
		</div>
	
	</div>

	<?php echo $this->html->script(array('bootstrap.min.js')); ?>
	
</body>
</html>
