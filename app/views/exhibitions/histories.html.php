<?php 

$this->title('Exhibition History');

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
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
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li>
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/exhibitions/add"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>
		
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
					<?=$this->html->link($ah->name,'/exhibitions/history/'.$ah->slug); ?>
				<?php else: ?>
					<?=$ah->name ?> <span class="meta muted text-error">&mdash; Deleted</span>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>

	<?php endif; ?>

	</tbody>

</table>

<?=$this->pagination->pager('exhibitions', 'histories', $page, $total, $limit); ?>
