<?php 

$this->title('Architecture');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index','/architectures'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/architectures/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/architectures/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/architectures/add"><i class="icon-plus-sign icon-white"></i> Add a Project</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if(sizeof($architectures) == 0): ?>

	<div class="alert alert-danger">There is no Architecture in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Project by clicking the <strong><?=$this->html->link('Add a Project','/architectures/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if(sizeof($architectures) > 0): ?>

	<?=$this->partial->architectures(compact('architectures')); ?>

	<div class="pagination">
		<ul>
		<?php $query = "?limit=$limit"; ?>
		<?php if($page > 1):?>
		 <?php $prev = $page - 1; ?>
		<li><?=$this->html->link('«', "/architectures/pages/$prev$query");?></li> 
		<?php endif;?> 
			<li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
		 <?php if($total > ($limit * $page)):?>
		 <?php $next = $page + 1; ?>
		 <li><?=$this->html->link('»', "/architectures/pages/$next$query");?></li>
		 <?php endif;?> 
		</ul>
	</div>


<?php endif; ?>
