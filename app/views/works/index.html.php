<?php 

$this->title('Artwork');

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

$inventory = (\lithium\core\Environment::get('inventory') && ($authority_is_admin));

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::index')), 'active' => true),
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index')), 'active' => true),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications'))),
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

<?php if($total == 0): ?>

	<div class="alert alert-danger">There is no Artwork in the Archive.</div>

	<?php if($authority_can_edit): ?>

		<div class="alert alert-success">You can add the first Artwork by clicking the <strong><?=$this->html->link('Add Artwork','/works/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->works(compact('works')); ?>

<?=$this->pagination->pager('works', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>

<?php endif; ?>
