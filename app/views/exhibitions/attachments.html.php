<?php

$this->title($exhibition->title);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Attachments
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li><?=$this->html->link('View','/exhibitions/view/'.$exhibition->archive->slug); ?></li>
		<li><?=$this->html->link('Edit','/exhibitions/edit/'.$exhibition->archive->slug); ?></li>
		<li class="active">
			<a href="#">
				Attachments
			</a>
		</li>
		<li>
			<?=$this->html->link('History', $this->url(array('Exhibitions::history', 'slug' => $exhibition->archive->slug))); ?>
		</li>
	</ul>
</div>
