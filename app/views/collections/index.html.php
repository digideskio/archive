<?php 

$this->title('Collections');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Collections','/collections'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/collections/add/">
			<i class="icon-plus-sign icon-white"></i> Add Collection
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($collections) == 0): ?>

	<div class="alert alert-danger">There are no Collections in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Collection by clicking the <strong><?=$this->html->link('Add a Collection','/collections/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php foreach($collections as $collection): ?>
<article>
	<div class="alert">
    <h1><?=$this->html->link($collection->title,'/collections/view/'.$collection->slug); ?></h1>
    <p><?=$collection->description ?></p>
    </div>
</article>
<?php endforeach; ?>
