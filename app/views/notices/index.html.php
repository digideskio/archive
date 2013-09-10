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

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/notices'); ?>
		</li>
	</ul>

	<div class="btn-toolbar">
		<?php if($this->authority->isAdmin()): ?>

				<a class="btn btn-inverse" href="/notices/add"><i class="icon-plus-sign icon-white"></i> Write a Notice</a>
		
		<?php endif; ?>
	</div>
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

				<p>
					<span style="text-transform: uppercase; color:#D14;"><?=$notice->path ?></span>
					<small style="font-size: smaller;"><?=$notice->date_modified ?></small>

					<?php if($this->authority->isAdmin()): ?>

					<a href="/notices/edit/<?=$notice->id ?>" title="Edit Notice"><i class="icon icon-edit"></i></a>
			
					<?php endif; ?>
				</p>
				<?=$notice->body ?>

			</div>

		</div>

		</div>
	<?php endforeach; ?>

</div>
