<?php

$this->title($album->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($album->title, $this->url(array('Albums::view', 'slug' => $album->slug))); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View', $this->url(array('Albums::view', 'slug' => $album->slug))); ?>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('History', $this->url(array('Albums::history', 'slug' => $album->slug))); ?></li>
	<li><?=$this->html->link('Packages', $this->url(array('Albums::package', 'slug' => $album->slug))); ?></li>
</ul>


<div class="well">
<?=$this->form->create($album); ?>
	<legend>Album Info</legend>
    <?=$this->form->field('title',array('value'=>$album->title)); ?>
	<?=$this->form->field('slug', array('label' => 'Permalink', 'disabled' => 'disabled'));?>
    <?=$this->form->field('description',array(
    	'type'=>'textarea',
    	'value'=>$album->description
    )); ?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel', $this->url(array('Albums::view', 'slug' => $album->slug)), array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


		
<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Album
	</a>

</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Album</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$album->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Album from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($album, array('url' => $this->url(array('Albums::delete', 'slug' => $album->slug)), 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
