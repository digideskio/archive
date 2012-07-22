<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Archive &gt; <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('bootstrap.min.css', 'bootstrap-responsive.min.css', 'app.css')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	
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

	<?php echo $this->html->script(array('jquery.min.js', 'bootstrap.min.js')); ?>
	
</body>
</html>
