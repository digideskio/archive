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
