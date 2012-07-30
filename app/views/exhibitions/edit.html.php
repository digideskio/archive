<?php

$this->title($exhibition->title);

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
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>


<div class="well">
<?=$this->form->create($exhibition); ?>
    <?=$this->form->field('title');?>
    <?=$this->form->field('curator');?>
    <?=$this->form->field('venue');?>
    <?=$this->form->field('city');?>
    <?=$this->form->field('country');?>
	<?=$this->form->field('earliest_date', array('label' => 'Opening Date'));?>
	<?=$this->form->field('latest_date', array('label' => 'Closing Date'));?>
    <?=$this->form->label('Show Type');?>
    <select name="type">
    	<option value="Solo" <?php if ($exhibition->type == "Solo") { echo "selected"; }?>>Solo</option>
    	<option value="Group" <?php if ($exhibition->type == "Group") { echo "selected"; }?>>Group</option>
    </select>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/exhibitions/view/'.$exhibition->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
