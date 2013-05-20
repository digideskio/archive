<?php

$this->title('Artists');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Artists
	</li>

	</ul>

</div>
</span><div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li class="active">
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

		<li>
			<?=$this->html->link('Classifications','/works/classifications'); ?>
		</li>

		<?php if($inventory): ?>

			<li>
				<?=$this->html->link('Locations','/works/locations'); ?>
			</li>
		
		<?php endif; ?>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>

	<?php if ($artists && sizeof($artists) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($artists as $artist): ?>

		<?php $query = urlencode($artist['name']); ?>
		<p><?=$this->html->link($artist['name'], "/works/search?condition=artist&query=$query"); ?> <span class="badge"><?=$artist['works'] ?></span></p>

	<?php endforeach; ?>

	</div>
</div>
