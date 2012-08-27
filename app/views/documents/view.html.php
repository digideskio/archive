<?php 

$this->title($document->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($document->title,'/documents/view/'.$document->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/documents/edit/'.$document->slug); ?></li>
	
	<?php endif; ?>

</ul>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
		<?php $span = 'span6'; ?>
		<li class="<?=$span?>" >
		<div class="thumbnail">
		<img src="/files/small/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
		</div>
		</li>
		</ul>
		
	</div>
	
	<div class="span4">
	
		<?php if($document->width && $document->height): ?>
		
		<div class="alert alert-block alert-success" style="font-family:monospace">
			<p><?=$document->resolution(); ?></p>
			<p><?=$document->size(); ?></p>
		</div>
		
		<?php endif; ?>
	
   		<?php foreach($works_documents as $wd): ?>
			<div class="alert alert-block alert-info">
				<p>
					<?php echo $wd->work->caption(); ?>
					
					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
					
				</p>
			</div>
		<?php endforeach; ?>
		
		<?php foreach($architectures_documents as $ad): ?>
	
			<div class="alert alert-block alert-info">
			<p>
				<?php echo $ad->architecture->caption(); ?>
					
					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
			</p>
			</div>
		
		<?php endforeach; ?>
	
		<table class="table">
			<tbody>
				
				<?php if (sizeof($works_documents) > 0) : ?> 
			
				<tr>
					<td><i class="icon-picture"></i></td>
					<td class="meta">Artwork</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
						
							<?php foreach($works_documents as $wd): ?>
							<li><strong><?=$this->html->link(
								$wd->work->title,
								'/works/view/'.$wd->work->slug
							);?></strong></li>
							<?php endforeach; ?>
						
						</ul>
					</td>
				</tr>
				
				<?php endif; ?>
				
				<?php if (sizeof($architectures_documents) > 0) : ?> 
				<tr>
					<td><i class="icon-road"></i></td>
					<td class="meta">Architecture</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
						
							<?php foreach($architectures_documents as $ad): ?>
							<li><strong><?=$this->html->link(
								$ad->architecture->title,
								'/architectures/view/'.$ad->architecture->slug
							);?></strong></li>
							<?php endforeach; ?>
						
						</ul>
					</td>
				</tr>
				<?php endif; ?>
				
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">File Type</td>
					<td>
						<span class="label"><?= $document->format->mime_type ?></span>
					</td>
				</tr>
				<tr>
					<td><i class="icon-bookmark"></i></td>
					<td class="meta">Title</td>
					<td><?=$document->title ?></td>
				</tr>
				<tr>
					<td><i class="icon-calendar"></i></td>
					<td class="meta">Date</td>
					<td><?=$document->file_date ?></td>
				</tr>
				<tr>
					<td><i class="icon-camera"></i></td>
					<td class="meta">PhotoCredit</td>
					<td>
					<?php
						if($document->credit) { 
							echo $document->credit;
						 } else {
						 	echo '<span class="label label-warning">Unknown</span>';
						 }
					?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-download-alt"></i></td>
					<td class="meta">Download</td>
					<td>
						<?=$this->html->link(
							$document->slug . '.' .$document->format->extension,
							'/files/download/'.$document->slug . '.' .$document->format->extension
						);?>
					</td>
				</tr>
				
				
				
				
			</tbody>
		
		</table>
	
	</div>
</div>
