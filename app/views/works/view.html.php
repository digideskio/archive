<?php 

$this->title($work->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		<?=$work->title ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/works/edit/'.$work->slug); ?></li>
	
	<?php endif; ?>

		<li><?=$this->html->link('History','/works/history/'.$work->slug); ?></li>

</ul>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
			
				<?php
					$num_thumbs = sizeof($work_documents);
					$span = 'span3';// $span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = 'thumb';// $size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($work_documents as $wd): ?>
			
				<li class="<?=$span?>">
					<a href="/documents/view/<?=$wd->document->slug?>" class="thumbnail">
						
						<?php if ($wd->document->published): ?>
							<span class="label label-success">Published</span>
						<?php endif; ?>
						<?php if (!$wd->document->published): ?>
							<span class="label label-important">Private</span>
						<?php endif; ?>

						<img src="/files/<?=$wd->document->view(array('action' => $size)); ?>" alt="<?=$wd->document->title ?>">
					</a>
				</li>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($work_documents) == 0): ?>
				<li class="<?=$span?>">
				<div class="thumbnail">
				<span class="label label-warning">No Image</span>
				</div>
				</li>
			<?php endif; ?>
		
		</ul>
		
	</div>
	
	<div class="span4">
	
		<?php if($work->annotation): ?>
		
		<div class="popover" style="display:block; position: static; margin-bottom:18px; width:100%">
		<div class="popover-inner">
			<div class="popover-title">
			<strong><?=$work->title ?></strong>
			</div>
			<div class="popover-content">
			<p><?=$work->annotation ?></p>
			</div>
		</div>
		</div>
		
		<?php endif; ?>
	
		<div class="alert alert-block">
    	<p>
    		<?php echo $work->caption(); ?>
    	</p>
		</div>
	
		<table class="table">
			<thead>
				<tr>
					<th><i class="icon-picture"></i></th>
					<th class="meta"></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Artwork ID</td>
					<td>
						<?php 
						
						if($work->creation_number) {
							echo $work->creation_number;
						} else {
							echo '<span class="label label-warning">Missing</span>';
						}
						
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-tag"></i></td>
					<td class="meta">Classification</td>
					<td><?=$work->classification ?></td>
				</tr>
				<tr>
					<td><i class=" icon-info-sign"></i></td>
					<td class="meta">Info</td>
					<td>
						<?php 
						
						echo $work->notes();
						
						?>
					
				</tr>
			</tbody>
		
		</table>

		<?php 
			$hasCollections = sizeof($collections) > 0;
			$hasExhibitions = sizeof($exhibitions) > 0;
			$hasDocuments = sizeof($work_documents) > 0;
		?>

		<?php if ($hasCollections || $hasExhibitions || $hasDocuments) : ?>

			<table class="table">
				<thead>
					<tr>
						<th><i class="icon-random"></i></th>
						<th class="meta"></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				
					<?php if ($hasCollections) : ?>
					<tr>
						<td><i class="icon-book"></i></td>
						<td class="meta">Collections</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($collections as $collection): ?>
								<li><strong><?=$this->html->link(
									$collection->title,
									'/collections/view/'.$collection->slug
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					<?php endif; ?>

					<?php if ($hasExhibitions) : ?>
					<tr>
						<td><i class="icon-eye-open"></i></td>
						<td class="meta">Exhibitions</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
							
								<?php foreach($exhibitions as $exhibition): ?>
								<li><strong><?=$this->html->link(
									$exhibition->title,
									'/exhibitions/view/'.$exhibition->slug
								);?></strong></li>
								<?php endforeach; ?>
							
							</ul>
						</td>
					</tr>
					<?php endif; ?>

					<?php if ($hasDocuments) : ?>
					<tr>
						<td><i class="icon-folder-open"></i></td>
						<td class="meta">Documents</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
						
			
						<?php foreach($work_documents as $wd): ?>
				
								<li><a href="/documents/view/<?=$wd->document->slug?>">
									<strong><?=$wd->document->slug?>.<?=$wd->format->extension?></strong>
								</a></li>
				
						<?php endforeach; ?>
						</ul>
						<td>
					</tr>
					<?php endif; ?>
					
				</tbody>
			</table>
		<?php endif; ?>	
	</div>
</div>
