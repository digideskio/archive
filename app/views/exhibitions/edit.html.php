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
