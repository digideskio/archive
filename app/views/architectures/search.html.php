<?php 

$this->title('Search Architecture');

$conditions_list = array(
	'' => 'Search by...',
	'title' => 'Title',
	'client' => 'Client',
	'project_lead' => 'Project Lead',
	'earliest_date' => 'Year',
	'status' => 'Status',
	'location' => 'Location',
	'city' => 'City',
	'country' => 'Country',
	'remarks' => 'Remarks',
);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
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
			<?=$this->html->link('Index','/architectures'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/architectures/histories'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search','/architectures/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/architectures/add"><i class="icon-plus-sign icon-white"></i> Add a Project</a>
		
		<?php endif; ?>

	</div>
<div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search')); ?>
		<legend>Search Architecture</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

		<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?php if($total > 0): ?>

<?=$this->partial->architectures(compact('architectures')); ?>

<div class="pagination">
    <ul>
	<?php $query = "?conditions=$condition&query=$query"; ?>
    <?php if($page > 1):?>
	 <?php $prev = $page - 1; ?>
    <li><?=$this->html->link('«', "/architectures/search/$prev$query");?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
	 <?php $next = $page + 1; ?>
     <li><?=$this->html->link('»', "/architectures/search/$next$query");?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
