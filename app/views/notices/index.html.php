<?php

$this->title('Notices');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Notices','/notices'); ?>
	</li>

	</ul>

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

				<p><small style="font-size: smaller;">2012-10-29 23:44:21</small></p>
				<?=$notice->body ?>

			</div>

		</div>

		</div>
	<?php endforeach; ?>

</div>
