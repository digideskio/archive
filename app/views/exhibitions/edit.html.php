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
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($collection->title,'/exhibitions/view/'.$collection->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/exhibitions/view/'.$collection->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>


<div class="well">
<?=$this->form->create($collection); ?>
    <?=$this->form->field('title');?>
    <?=$this->form->field('curator', array('value' => $collection->exhibition->curator));?>
    <?=$this->form->field('venue', array('value' => $collection->exhibition->venue));?>
    <?=$this->form->field('city', array('value' => $collection->exhibition->city));?>
    <?=$this->form->field('country', array('value' => $collection->exhibition->country));?>
	<?=$this->form->field('start', array(
		'label' => 'Opening Date',
		'value' => $collection->date->start
	));?>
	<?=$this->form->field('end', array(
		'label' => 'Closing Date',
		'value' => $collection->date->end
	));?>
    <?=$this->form->label('Show Type');?>
    <select name="type">
    	<option value="Solo" <?php if ($collection->exhibition->type == "Solo") { echo "selected"; }?>>Solo</option>
    	<option value="Group" <?php if ($collection->exhibition->type == "Group") { echo "selected"; }?>>Group</option>
    </select>
    <?=$this->form->field('remarks', array(
    	'type' => 'textarea',
    	'value' => $collection->exhibition->remarks
    ));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/exhibitions/view/'.$collection->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


		
<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Exhibition
	</a>

</div>




<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$collection->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Exhibition from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($collection, array('url' => "/exhibitions/delete/$collection->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
