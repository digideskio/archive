<?php

$this->title('Search Artwork');

$conditions_list = array(
	'' => 'Search by...',
	'title' => 'Title',
	'artist' => 'Artist',
	'classification' => 'Classification',
	'earliest_date' => 'Year',
	'materials' => 'Materials',
	'remarks' => 'Remarks',
	'creation_number' => 'Artwork ID',
	'annotation' => 'Annotation',
);

if ($auth->role->name == 'Admin') {
	$conditions_list['location'] = 'Location';	
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Search
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li>
			<?=$this->html->link('Artists','/works/artists'); ?>
		</li>

		<li>
			<?=$this->html->link('Classifications','/works/classifications'); ?>
		</li>

		<?php if($auth->role->name == 'Admin'): ?>

			<li>
				<?=$this->html->link('Locations','/works/locations'); ?>
			</li>
		
		<?php endif; ?>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search')); ?>
		<legend>Search Artwork</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

			<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?php if($total > 0): ?>

<?=$this->partial->works(compact('works')); ?>

<div class="pagination">
    <ul>
	<?php $query = "?condition=$condition&query=$query"; ?>
    <?php if($page > 1):?>
	 <?php $prev = $page - 1; ?>
    <li><?=$this->html->link('«', "/works/search/$prev$query");?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
	 <?php $next = $page + 1; ?>
     <li><?=$this->html->link('»', "/works/search/$next$query");?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
