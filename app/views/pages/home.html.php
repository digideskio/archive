<?php

$this->title('Home');

?>

<div class="hero-unit">
	<h1>Welcome to the Archive.</h1>
	<p>All of our Artworks, Architectural projects, and Exhibitions are collected here. Use the sidebar to navigate through the archive.</p>
	<p><a class="btn btn-inverse btn-large" href="<?=$this->url(array('Collections::index')); ?>">Browse the Albums »</a></p>
</div>

<div class="accordion" id="notices">


	<?php $count = 0; ?>

	<?php foreach ($notices as $notice): ?>

		<?php $count++; ?>

		<div class="accordion-group">
		<div class="accordion-heading">

			<a class="accordion-toggle" data-toggle="collapse" data-parent="#notices" href="#collapse<?=$count ?>">
				<i class="icon icon-bell"></i>
				<?=$notice->subject ?>

			</a>
		</div>

		<div id="collapse<?=$count ?>" class="accordion-body collapse <?php if ($count==1) { echo 'in'; } ?>">

			<div class="accordion-inner">

				<p><small style="font-size: smaller;"><?=$notice->date_modified ?></small></p>
				<?=$notice->body ?>

			</div>

		</div>

		</div>
	<?php endforeach; ?>

</div>
