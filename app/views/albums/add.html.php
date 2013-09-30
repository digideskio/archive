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

<?=$this->form->create(compact('album', 'archives')); ?>
<div class="well">
	<legend>Album Info</legend>
    <?=$this->form->field('album.title', array('autocomplete' => 'off'));?>
    <?=$this->form->field('album.remarks', array('label' => 'Description', 'type' => 'textarea'));?>
</div>

<?php if (!empty($archives)): ?>
<div class="well">
	<legend>Album Components</legend>
	<?php foreach ($archives as $archive): ?>
		<label class="checkbox">	
		<?=$this->form->checkbox('archives[]', array('id' => "Archive-$archive->id", 'value' => $archive->id, 'hidden' => false, 'checked' => 'checked'));?>
		<?=$archive->name ?>
		<?php if ($archive->years()): ?>
			(<?=$archive->years(); ?>)
		<?php endif; ?>
		</label>
	
	<?php endforeach; ?>

</div>
<?php endif; ?>

<div class="well">
	<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
</div>
<?=$this->form->end(); ?>
