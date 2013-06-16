<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Archive &gt; <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('bootstrap.min.css', 'bootstrap-responsive.min.css', 'flat-ui.css')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container" class="container">
		<div id="content">
			<?php echo $this->content(); ?>
		</div>
	</div>
</body>
</html>
