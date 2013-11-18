<div class="well">
	<legend>Albums</legend>
	<table class="table">

		<?php foreach($albums as $album): ?>
		<?php $component = $album->components->first(); ?>
			<tr>
				<td>
					<?=$this->html->link($album->archive->name, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
				</td>
				<td align="right" style="text-align:right">
		<?=$this->form->create($component, array('url' => $this->url(array('Components::delete', 'id' => $component->id)), 'method' => 'post')); ?>
		<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
		<?=$this->form->end(); ?>
				</td>
			</tr>

		<?php endforeach; ?>

		<?php if(sizeof($other_albums) > 0): ?>

		<tr>
			<td></td>
			<td align="right" style="text-align:right">
				<a data-toggle="modal" href="#albumModal" class="btn btn-mini btn-inverse">Add an Album</a>
			</td>
		</tr>

		<?php endif; ?>

		</table>

</div>

<div class="modal fade hide" id="albumModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Record to an Album</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_albums as $oc): ?>
				<tr>
					<td>
						<strong>
							<?=$this->html->link($oc->archive->name, $this->url(array('Albums::view', 'slug' => $oc->archive->slug))); ?>
						</strong><br/>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oc, array('url' => $this->url(array('Components::add')), 'method' => 'post')); ?>
			<input type="hidden" name="archive_id1" value="<?=$oc->id ?>" />
			<input type="hidden" name="archive_id2" value="<?=$archive->id ?>" />
			<input type="hidden" name="type" value="<?=$component_type ?>" />
			<?=$this->form->submit('Add', array('class' => 'btn btn-mini btn-success')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</div>

