<?php 

$this->title('Add a Notice');

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$pages_list = array(
	'' => 'Choose one...',
	'alerts' => 'Alerts',
	'home' => 'Home',
	'updates' => 'Updates'
);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Notices','/Notices'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Write
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/notices'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Write a Notice</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($notice); ?>
	<legend>Notice</legend>
    <?=$this->form->field('subject', array('autocomplete' => 'off'));?>
    <?=$this->form->field('body', array('type' => 'textarea'));?>
	<?=$this->form->label('path', 'Page'); ?>
	<?=$this->form->select('path', $pages_list); ?>
	<fieldset>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/notices', array('class' => 'btn')); ?>
	</fieldset>
<?=$this->form->end(); ?>
</div>
