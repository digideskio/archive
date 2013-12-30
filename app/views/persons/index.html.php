<?php 

$this->title('Artists');

$authority_can_edit = $this->authority->canEdit();
$authority_is_admin = $this->authority->isAdmin();

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artists', 'url' => $this->url(array('Persons::add')), 'active' => true),
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Persons::index')), 'active' => true),
	)
)); ?>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Persons::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artist</a>
		
		<?php endif; ?>

	</div>

</div>

<?php if ($persons->count() > 20): ?>
	<div style="-moz-column-count:3; -webkit-column-count:3; column-count:3;">
<?php else: ?>
	<div>
<?php endif; ?>

<?php foreach ($persons as $person): ?>

	<p>
		<?=$this->html->link(
			$person->archive->name . ' ' . $person->archive->native_name,
			$this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			))
		); ?>
	</p>

<?php endforeach; ?>
</div>

<?=$this->pagination->pager('artists', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>
