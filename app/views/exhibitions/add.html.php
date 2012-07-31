<?php 

$this->title('Add an Exhibition');

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
	
	<li class="active">
		Add
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
    	<option value="Solo">Solo</option>
    	<option value="Group">Group</option>
    </select>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/exhibitions', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>