<?php 

$this->title('Artwork History');

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
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

		<li class="active">
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>
</div>

<table class="table">

	<thead>
		<tr>
			<th>User</th>
			<th style="min-width:150px">Date</th>
			<th>Title</th>
		<tr>
	</thead>
	<tbody>

	<?php if (sizeof($archives_histories) > 0 ): ?>

	<?php foreach( $archives_histories as $ah ): ?>

		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}
			$start_date_display = $start_date_time->format("Y-m-d H:i:s");
		?>

		<tr>
			<td style="text-align:center">
				<?php if( $ah->user->id ): ?>
				<strong style="font-size: smaller;">
					<?=$this->html->link($ah->user->initials(),'/users/view/'.$ah->user->username); ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$start_date_display ?></td>
			<td>
				<?php if ($ah->archive->id): ?>
					<?=$this->html->link($ah->name,'/works/history/'.$ah->slug); ?>
				<?php else: ?>
					<?=$ah->name ?> <span class="meta muted text-error">&mdash; Deleted</span>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>

	<?php endif; ?>

	</tbody>

</table>


<div class="pagination">
    <ul>
    <?php if($page > 1):?>
    <li><?=$this->html->link('«', array('Works::histories', 'page'=> $page - 1));?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
     <li><?=$this->html->link('»', array('Works::histories', 'page'=> $page + 1));?></li>
     <?php endif;?> 
    </ul>
</div>
