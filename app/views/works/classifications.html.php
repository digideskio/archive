<?php

$this->title('Classifications');

$authority_can_edit = $this->authority->canEdit();

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::add'))),
		array('title' => 'Classifications', 'active' => true)
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications')), 'active' => true),
		array('title' => 'Locations', 'url' => $this->url(array('Works::locations'))),
		array('title' => 'History', 'url' => $this->url(array('Works::histories'))),
		array('title' => 'Search', 'url' => $this->url(array('Works::search'))),
	)
)); ?>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Works::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>

		<?php endif; ?>

	</div>

</div>

	<ul class="thumbnails">

	<?php foreach ($classifications as $classification): ?>

		<?php
			$classification_name = $classification['name'];
			$query = urlencode($classification['name']);
			$thumbnail = $classification['thumbnail'];
		?>

		<li class="span3">
			<a href="/works/search?condition=Archives.classification&query=<?=$query ?>" class="thumbnail" title="<?=$classification_name ?>">
				<span class="label label-info" style="font-weight: normal; font-size:1.1em; padding: 3px 8px 4px; text-transform: uppercase; letter-spacing: 0.2em;"><?=$classification_name ?></span>
				<?php if($thumbnail): ?>
                    <img src="<?=$thumbnail ?>" alt="<?=$classification_name ?>">
				<?php endif; ?>
			</a>
		</li>

	<?php endforeach; ?>

	</ul>
</div>
