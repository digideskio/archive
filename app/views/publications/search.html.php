<?php 

$this->title('Publications');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">

		<li>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($publications_types as $pt): ?>
			<li>
				<?=$this->html->link($pt,'/publications?type='.$pt); ?> 
			</li>
		<?php endforeach; ?>
			</ul>
		</li>

		<li class="active">
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
		
		<?php endif; ?>

	</div>
<div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline')); ?>
		<legend>Search Publications</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…">

		<?php $selected = 'selected="selected"'; ?>

		<select name="conditions">
			<option value='title'>Title</option>
			<option value='author' <?php if ($condition == 'author') { echo $selected; } ?>>Author</option>
			<option value='publisher' <?php if ($condition == 'publisher') { echo $selected; } ?>>Publisher</option>
			<option value='editor' <?php if ($condition == 'editor') { echo $selected; } ?>>Editor</option>
			<option value='year' <?php if ($condition == 'year') { echo $selected; } ?>>Year</option>
			<option value='subject' <?php if ($condition == 'subject') { echo $selected; } ?>>Subject</option>
			<option value='language' <?php if ($condition == 'language') { echo $selected; } ?>>Language</option>
			<option value='storage_location' <?php if ($condition == 'storage_location') { echo $selected; } ?>>Storage Location</option>
			<option value='storage_number' <?php if ($condition == 'storage_number') { echo $selected; } ?>>Storage Number</option>
			<option value='publication_number' <?php if ($condition == 'publication_number') { echo $selected; } ?>>Publication Number</option>
		</select>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?=$this->partial->publications(compact('publications')); ?>
