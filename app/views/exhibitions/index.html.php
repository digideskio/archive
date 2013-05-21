<?php 

$this->title('Exhibitions');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li>
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

<?php if(sizeof($exhibitions) == 0): ?>

	<div class="alert alert-danger">There are no Exhibitions in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Exhibition by clicking the <strong><?=$this->html->link('Add a Exhibition','/exhibitions/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->exhibitions(compact('exhibitions')); ?>

<?=$this->pagination->pager('exhibitions', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>

<?php endif; ?>
