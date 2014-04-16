<?php

$this->title('Locations');

$authority_can_edit = $this->authority->canEdit();

$authority_can_inventory = $this->authority->canInventory();
$inventory = (\lithium\core\Environment::get('inventory') && ($authority_can_inventory));

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::add'))),
		array('title' => 'Locations', 'active' => true)
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications'))),
		array('title' => 'Locations', 'url' => $this->url(array('Works::locations')), 'active' => true),
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

    <?php if($authority_can_inventory): ?>
	<?php foreach ($locations as $location): ?>

		<?php $query = urlencode($location['name']); ?>
		<p><?=$this->html->link($location['name'], "/works/search?condition=Works.location&query=$query"); ?> <span class="badge"><?=$location['works'] ?></span></p>

	<?php endforeach; ?>
    <?php endif; ?>
</div>
