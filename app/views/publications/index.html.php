<?php 

$classification = isset($options['classification']) ? $options['classification'] : NULL;

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

		<li <?php if (!$classification) { echo 'class="active"'; } ?>>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

		<li class="dropdown <?php if ($classification) { echo 'active'; } ?>">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li <?php if ($pc == $classification) { echo 'class="active"'; } ?>>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?> 
			</li>
		<?php endforeach; ?>
			</ul>
		</li>

		<li>
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if($total == 0 && !$classification): ?>

	<div class="alert alert-danger">There are no Publications in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Publication by clicking the <strong><?=$this->html->link('Add a Publication','/publications/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->publications(compact('publications')); ?>

<div class="pagination">
    <ul>
	<?php $query = $classification ? "?classification=$classification" : ''; ?>
    <?php if($page > 1):?>
	 <?php $prev = $page - 1; ?>
    <li><?=$this->html->link('«', "/publications/pages/$prev$query");?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
	 <?php $next = $page + 1; ?>
     <li><?=$this->html->link('»', "/publications/pages/$next$query");?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
