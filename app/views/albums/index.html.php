<?php 

$this->title('Albums');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums',$this->url(array('Albums::index'))); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index',$this->url(array('Albums::index'))); ?>
		</li>
		<li>
			<?=$this->html->link('Packages',$this->url(array('Albums::packages'))); ?>
		</li>
	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="<?=$this->url(array('Albums::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add an Album</a>
		
		<?php endif; ?>
	</div>
</div>

<?php if(sizeof($albums) == 0): ?>

	<div class="alert alert-danger">There are no Albums in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Album by clicking the <strong><?=$this->html->link('Add an Album',$this->url(array('Albums::add'))); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php foreach($albums as $album): ?>
<article>
	<div class="alert">
    <h1><?=$this->html->link($album->title,$this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?></h1>
    <p><?=$album->remarks ?></p>
    </div>
</article>
<?php endforeach; ?>
