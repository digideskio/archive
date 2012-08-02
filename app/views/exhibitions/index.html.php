<?php 

$this->title('Exhibitions');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/exhibitions/add/">
			<i class="icon-plus-sign icon-white"></i> Add Exhibition
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($exhibitions) == 0): ?>

	<div class="alert alert-danger">There are no Exhibitions in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can create the first Exhibition by clicking the <strong><?=$this->html->link('Add a Exhibition','/exhibitions/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php foreach($exhibitions as $exhibition): ?>
<article>
	<div class="alert">
	<h1><?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$opening_date = ($exhibition->earliest_date == '0000-00-00 00:00:00') ? '' : date('d M Y', strtotime($exhibition->earliest_date));
		$closing_date = ($exhibition->latest_date == '0000-00-00 00:00:00') ? '' : date('d M Y', strtotime($exhibition->latest_date));
	?>
	
	<?php if($exhibition->venue) echo "<p><strong>$exhibition->venue</strong></p>"; ?>
	<?php if($exhibition->city) echo "<p>$exhibition->city</p>"; ?>
	<?php if($exhibition->country) echo "<p>$exhibition->country</p>"; ?>
	<?php if($opening_date) echo "<p>Opening Date: $opening_date</p>"; ?>
	<?php if($closing_date) echo "<p>Closing Date: $closing_date</p>"; ?>
	<?php if($exhibition->curator) echo "<p>$exhibition-curator, Curator</p>"; ?>
	
	<p><?=$exhibition->remarks ?></p>
	</div>
</article>
<?php endforeach; ?>
