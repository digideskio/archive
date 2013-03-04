<?php 

$this->title('Add an Album');

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
	<?=$this->html->link('Albums',$this->url('Albums::index')); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Add
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index',$this->url('Albums::index')); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add an Album</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($album); ?>
	<legend>Album Info</legend>
    <?=$this->form->field('title');?>
    <?=$this->form->field('remarks', array('label' => 'Description', 'type' => 'textarea'));?>
    <?=$this->form->hidden('class'); ?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel',$this->url('Albums::index'), array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
