<?php 

$this->title('Albums');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums',$this->url(array('Collections::index'))); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index',$this->url(array('Collections::index'))); ?>
		</li>
	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="<?=$this->url(array('Collections::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add an Album</a>
		
		<?php endif; ?>
	</div>
</div>

<?php if(sizeof($collections) == 0): ?>

	<div class="alert alert-danger">There are no Albums in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Album by clicking the <strong><?=$this->html->link('Add an Album',$this->url(array('Collections::add'))); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php foreach($collections as $collection): ?>
<article>
	<div class="alert">
    <h1><?=$this->html->link($collection->title,$this->url(array('Collections::view', 'slug' => $collection->slug))); ?></h1>
    <p><?=$collection->description ?></p>
    </div>
</article>
<?php endforeach; ?>
