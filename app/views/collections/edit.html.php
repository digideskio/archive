<?php

$this->title($collection->title);

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
	<?=$this->html->link('Collections','/collections'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($collection->title,'/collections/view/'.$collection->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/collections/view/'.$collection->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>


<div class="well">
<?=$this->form->create($collection); ?>
    <?=$this->form->field('title',array('value'=>$collection->title)); ?>
	<?=$this->form->field('slug', array('label' => 'Permalink', 'disabled' => 'disabled'));?>
    <?=$this->form->field('description',array(
    	'type'=>'textarea',
    	'value'=>$collection->description
    )); ?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/collections/view/'.$collection->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


		
<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Collection
	</a>

</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Collection</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$collection->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Collection from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($collection, array('url' => "/collections/delete/$collection->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
