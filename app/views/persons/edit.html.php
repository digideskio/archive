<?php

$this->title($person->archive->name);

$this->form->config(
    array(
		'label' => array(
			'class' => 'control-label',
		),
		'field' => array(
			'wrap' => array('class' => 'control-group'),
			'template' => '<div{:wrap}>{:label}<div class="controls control-row">{:input}{:error}</div></div>',
			'style' => 'max-width:100%'
		),
		'select' => array(
			'style' => 'max-width:100%'
		),
		'checkbox' => array(
			'wrap' => array('class' => 'control-group'),
		),
        'templates' => array(
            'error' => '<div class="help-inline">{:content}</div>'
        )
    )
);

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array(
			'title' => 'Artists',
			'url' => $this->url(array('Persons::index'))
		),
		array(
			'title' => $person->archive->name,
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			))
		),
		array(
			'title' => 'Edit',
			'active' => true
		)
	)
)); ?>

<div class="actions">
<?=$this->partial->navtabs(array(
	'tabs' => array(
		array(
			'title' => 'View',
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'view',
				'slug' => $person->archive->slug
			))
		),
		array(
			'title' => 'Edit',
			'url' => $this->url(array(
				'controller' => 'persons',
				'action' => 'edit',
				'slug' => $person->archive->slug
			)),
			'active' => true
		)
	)
)); ?>
	<div class="btn-toolbar">
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Artist
			</a>
	</div>
</div>

<div class="row">
<?=$this->form->create(compact('archive', 'person'), array('id' => 'PersonsForm', 'class' => 'form-horizontal')); ?>

	<div class="span5">
		<div class="well">
			<legend>Artist Names</legend>
    		<?=$this->form->field('person.given_name', array('label' => 'First', 'autocomplete' => 'off'));?>
    		<?=$this->form->field('person.family_name', array('label' => 'Last', 'autocomplete' => 'off'));?>
    		<?=$this->form->field('archive.name', array('label' => 'Full Name', 'autocomplete' => 'off'));?>
		</div>
		<div class="well">
			<legend>Artist Info</legend>
			<div class="control-group">
				<?=$this->form->label('archive.classification', 'Classification', array('class' => 'control-label')); ?>
				<div class="controls">
					<?=$this->form->select(
						'archive.classification',
						array_merge(
							array('' => 'Choose one...'),
							array_combine($classifications, $classifications)
						),
						array('value' => $archive->classification)
					); ?>
				</div>
			</div>
			<div class="control-group" style="margin-bottom: 0">
				<label class="control-label">Published</label>

				<div class="controls">
					<label class="checkbox">
						<?=$this->form->checkbox('archive.published');?>
					</label>
				</div>
			</div>
		</div>
		<div class="well">
			<?=$this->form->submit('Save', array('class' => 'btn btn-large btn-block btn-primary')); ?>
		</div>
	</div>

	<div class="span5">
		<div class="well">
			<legend>Artist Names (Native Language)</legend>
    		<?=$this->form->field('person.native_given_name', array('label' => 'First', 'autocomplete' => 'off'));?>
    		<?=$this->form->field('person.native_family_name', array('label' => 'Last', 'autocomplete' => 'off'));?>
			<?=$this->form->field('archive.native_name', array('label' => 'Full Name', 'autocomplete' => 'off'));?>
		</div>
	</div>


<?=$this->form->end(); ?>
</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artist</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$person->archive->name; ?></strong>?</p>

			<p>By selecting <code>Delete</code>, you will remove this Artist and all of their Artwork from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $work->archive->slug; ?>
			<?=$this->form->create($work, array('url' => $this->url(array("Persons::delete", 'slug' => $person->archive->slug)), 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
