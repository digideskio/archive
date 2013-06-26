<?php

$this->title('Classifications');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Classifications
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li>
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

		<li class="active">
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

	<ul class="thumbnails">

	<?php foreach ($classifications as $classification): ?>

		<?php
			$classification_name = $classification['name'];
			$query = urlencode($classification['name']);
			$document_slug = $classification['document'];
		?>

		<li class="span3">
			<a href="/works/search?condition=classification&query=<?=$query ?>" class="thumbnail" title="<?=$classification_name ?>">
				<span class="label label-info"><?=$classification_name ?></span>
				<?php if($document_slug): ?>
					<img src="/files/thumb/<?=$document_slug ?>.jpeg" alt="<?=$classification_name ?>">
				<?php endif; ?>
			</a>
		</li>

	<?php endforeach; ?>

	</ul>
</div>
