<?php

$this->title('Artists');

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

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
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>

	<?php if ($artists && sizeof($artists) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($artists as $artist): ?>

		<p>
		<?php if ($artist['name']): ?>
			<?php $query = urlencode($artist['name']); ?>
			<?=$this->html->link($artist['name'], "/works/search?condition=artist&query=$query"); ?>
		<?php endif; ?>

		<?php if ($artist['native_name']): ?>
			<?php $query = urlencode($artist['native_name']); ?>
			<?=$this->html->link($artist['native_name'], "/works/search?condition=artist&query=$query"); ?>
		<?php endif; ?>

		<?php if ($artist['works']): ?>
			<span class="badge"><?=$artist['works'] ?></span></p>
		<?php endif; ?>

	<?php endforeach; ?>

	</div>
</div>
