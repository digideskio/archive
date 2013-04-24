
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

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li class="active">
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

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

	<?php foreach ($artists as $artist): ?>

		<?php $query = urlencode($artist['name']); ?>
		<p><?=$this->html->link($artist['name'], "/works/search?conditions=artist&query=$query"); ?> (<?=$artist['works'] ?>)</p>

	<?php endforeach; ?>
</div>
