<?php

$this->title('Home');

?>

<div class="hero-unit" style="margin-bottom: 14px;">
	<h1>Welcome to the Archive.</h1>
	<p>All of our Artworks, Architectural projects, and Exhibitions are collected here. Use the sidebar to navigate through the archive.</p>
	<p><a class="btn btn-inverse btn-large" href="<?=$this->url(array('Albums::index')); ?>">Browse the Albums »</a></p>
</div>

<?php foreach ($notices as $notice): ?>
	
	<div class="alert alert-block">
		<h4><?=$notice->subject ?> <small class="meta"><?=$notice->date_modified ?></small></h4>
		<p><?=$notice->body ?></p>
	</div>

<?php endforeach; ?>
