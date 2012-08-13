<?php 

$this->title($publication->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/publications/edit/'.$publication->slug); ?></li>
	
	<?php endif; ?>

</ul>

<div class="row">
	<div class="span4">
	
		<ul class="thumbnails">
			<li class="span4">
			<div class="thumbnail">
			<span class="label label-warning">No Image</span>
			</a>
			</li>
		</ul>
		
	</div>
	
	<div class="span6">
	
		<div class="alert alert-block">
    	<p>
    		<?php echo $publication->citation(); ?>
    	</p>
		</div>
	
		<table class="table">
			<tbody>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Publication&nbsp;ID</td>
					<td>
						<?php 
						
						if($publication->publication_number) {
							echo $publication->publication_number;
						} else {
							echo '<span class="label label-important">Missing</span>';
						}
						
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-globe"></i></td>
					<td class="meta">Location</td>
					<td>
						<?php 
							if($publication->location_code) {
								echo "<span class='label label-success'>$publication->location_code</span>\n";
							}
							if($publication->location) {
								echo "<span class='label'>$publication->location</span>";
							}
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-tag"></i></td>
					<td class="meta">Subjects</td>
					<td><?=$publication->subject ?></td>
				</tr>
				<tr>
					<td><i class="icon-flag"></i></td>
					<td class="meta">Language</td>
					<td><?=$publication->language ?></td>
				</tr>
				<tr>
					<td><i class="icon-comment"></i></td>
					<td class="meta">Remarks</td>
					<td><?=$publication->remarks ?></td>
				</tr>
			</tbody>
		
		</table>
	
	</div>
</div>
