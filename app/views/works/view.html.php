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
	<?=$this->html->link($work->title,'/works/view/'.$work->slug); ?>
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

</ul>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
			
				<?php
					$num_thumbs = sizeof($work_documents);
					$span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($work_documents as $wd): ?>
			
				<li class="<?=$span?>">
					<a href="/documents/view/<?=$wd->document->slug?>" class="thumbnail">
						<img src="/files/<?=$size?>/<?=$wd->document->slug?>.jpeg" alt="<?=$wd->document->title ?>">
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
		
		<div class="popover" style="display:block; position: static; margin-bottom:18px;">
		<div class="popover-inner" style="width:100%">
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
			<tbody>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Artwork ID</td>
					<td>
						<?php 
						
						if($work->creation_number) {
							echo $work->creation_number;
						} else {
							echo '<span class="label label-important">Missing</span>';
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
				<tr>
					<td><i class=" icon-info-sign"></i></td>
					<td class="meta">Info</td>
					<td>
						<?php 
						
						echo $work->notes();
						
						?>
					
				</tr>
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
			</tbody>
		
		</table>
	
	</div>
</div>
