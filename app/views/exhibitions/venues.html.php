<?php 

$this->title('Exhibition Venues');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Venues
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/exhibitions/add"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>
		
		<?php endif; ?>
	</div>
</div>


	<?php if ($venues): ?>
	<h3>Venues</h3>

	<?php if (sizeof($venues) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3; -moz-column-gap:40px; -webkit-column-gap:40px; column-gap: 40px;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($venues as $venue): ?>

		<?php $query = urlencode($venue['name']); ?>
		<p><?=$this->html->link($venue['name'], "/exhibitions/search?condition=venue&query=$query"); ?>&nbsp;<span class="badge"><?=$venue['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
		<hr/>
	<?php endif; ?>

	<?php if ($cities): ?>

		<h3>Cities</h5>

	<?php if (sizeof($cities) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3; -moz-column-gap:40px; -webkit-column-gap:40px; column-gap: 40px;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($cities as $city): ?>

		<?php $query = urlencode($city['name']); ?>
		<p><?=$this->html->link($city['name'], "/exhibitions/search?condition=city&query=$query"); ?>&nbsp;<span class="badge"><?=$city['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
		<hr/>
	<?php endif; ?>

	<?php if ($countries): ?>

		<h3>Countries</h5>

	<?php if (sizeof($countries) > 20): ?>
		<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3; -moz-column-gap:40px; -webkit-column-gap:40px; column-gap: 40px;">
	<?php else: ?>
		<div>
	<?php endif; ?>

	<?php foreach ($countries as $country): ?>

		<?php $query = urlencode($country['name']); ?>
		<p><?=$this->html->link($country['name'], "/exhibitions/search?condition=country&query=$query"); ?>&nbsp;<span class="badge"><?=$country['count'] ?></span></p>

	<?php endforeach; ?>

		</div>
		<hr/>
	<?php endif; ?>
