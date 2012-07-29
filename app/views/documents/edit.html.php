<?php

$this->title($document->title);

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
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($document->title,'/documents/view/'.$document->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>


<div class="well">
<?=$this->form->create($document); ?>
	<legend>Info</legend>
	
	<?=$this->form->field('title');?>
	<?=$this->form->field('slug', array('label' => 'Permalink', 'disabled' => 'disabled'));?>
	<?=$this->form->field('file_date');?>
	<?=$this->form->field('repository', array('label' => 'Image Repository'));?>
	<?=$this->form->field('credit', array('label' => 'Photo Credit'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/documents/view/' . $document->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
