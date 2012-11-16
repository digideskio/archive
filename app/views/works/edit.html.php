<?php

$this->title($work->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('History','/works/history/'.$work->slug); ?></li>
</ul>



<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($work); ?>
			<legend>Info</legend>
			<?=$this->form->field('artist');?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('classification');?>
			<?=$this->form->field('earliest_date', array('value' => $work->start_date_formatted()));?>
			<?=$this->form->field('latest_date', array('value' => $work->end_date_formatted()));?>
			<?=$this->form->field('creation_number', array('label' => 'Artwork ID'));?>
			<?=$this->form->field('materials', array('type' => 'textarea'));?>
			<?=$this->form->field('quantity');?>
			<?=$this->form->field('lender');?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('height', array(
				'label' => "Height (cm)"
			));?>
			<?=$this->form->field('width', array(
				'label' => "Width (cm)"
			));?>
			<?=$this->form->field('depth', array(
				'label' => "Depth (cm)"
			));?>
			<?=$this->form->field('diameter', array(
				'label' => "Diameter (cm)"
			));?>
			<?=$this->form->field('weight', array(
				'label' => "Weight (kg)"
			));?>
			<?=$this->form->field('running_time');?>
			<?=$this->form->field('measurement_remarks', array('type' => 'textarea'));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>
		
		<div class="well">
		
			<legend>Edit</legend>
		
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Artwork
			</a>
		
		</div>
		
		
	</div>
	
	<div class="span5">
	
	<div class="well">
		<legend>Annotation</legend>
		<?=$this->form->create($work); ?>
			<?=$this->form->field('annotation', array(
				'type' => 'textarea', 
				'rows' => '10', 
				'style' => 'width:90%;',
				'label' => ''
			));?>
		
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		
	</div>

	<div class="well">
		<legend>Links</legend>
		<table class="table">
			<?php foreach ($work_links as $wl): ?>
			
				<tr>
					<td>
						<?=$this->html->link($wl->link->url, $wl->link->url); ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($cw, array('url' => "/works_links/delete/$wl->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->html->link('Edit','/links/edit/'.$wl->link->id.'?work='.$work->slug, array('class' => 'btn btn-mini')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>

			<?php endforeach; ?>
		</table>
	</div>

	<div class="well">
		<legend>Collections</legend>
		<table class="table">
		
			<?php foreach($collections as $collection): ?>
			<?php $cw = $collection->collections_works[0]; ?> 
				<tr>
					<td>
						<a href="/collections/view/<?=$collection->slug ?>"><?=$collection->title ?></a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($cw, array('url' => "/collections_works/delete/$cw->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($other_collections) > 0): ?>
			
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#collectionModal" class="btn btn-mini btn-inverse">Add a Collection</a>
				</td>
			</tr>
			
			<?php endif; ?>
			
			</table>
		
	</div>
	
	
		
	<div class="well">
		<legend>Exhibitions</legend>
		<table class="table">
		
			<?php foreach($exhibitions as $exhibition): ?>
			<?php $ew = $exhibition->exhibitions_works[0]; ?>
				<tr>
					<td>
						<a href="/exhibitions/view/<?=$exhibition->slug ?>"><?=$exhibition->title ?></a> <strong></strong> 
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($ew, array('url' => "/exhibitions_works/delete/$ew->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($other_exhibitions) > 0): ?>			
			
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#exhibitionModal" class="btn btn-mini btn-inverse">Add an Exhibition</a>
				</td>
			</tr>
			
			<?php endif; ?>
			
			</table>
		
	</div>
	
	<div class="well">
		<legend>Images</legend>
		<table class="table">
		
			<?php foreach($work_documents as $wd): ?>
			
				<tr>
					<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
						<?php $px = '260'; ?>
						<a href="/documents/view/<?=$wd->document->slug ?>" title="<?=$wd->document->title ?>">
						<img width="125" height="125" src="/files/<?=$wd->document->view(); ?>" alt="<?=$wd->document->title ?>">
						</a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($wd, array('url' => "/works_documents/delete/$wd->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			</table>
		
		<?=$this->form->create(null, array('url' => "/works_documents/add/", 'method' => 'post')); ?>
			<legend>Add a Document</legend>
			<span class="help-block">Find the document you want to add, click the <code>Edit</code> button, copy the text in the <code>Permalink</code> field, and paste it here.</span>
			<?=$this->form->field('document_slug', array('label' => 'Document Permalink'));?>
			
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
		
		<?=$this->form->submit('Add Document', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
		
	</div>

     <div id="filelink"></div>

<table id="FileTypes" runat="server" class="list" cellspacing="2" cellpadding="2" width="100%" border="0">
	<tr>
	<th style="white-space: nowrap;">
	<asp:Literal ID="lblFilter" runat="server" />
	</th>
	<td width="100%">
	<asp:Literal ID="lblSupported" runat="server" />
	</td>
	</tr>
</table>

<div class="error alert alert-error" style="display:none;">
	<p id="errorMessage"></p>
	</div>
	<div class="uploadWrapper">
		<div id="FileContainer">

		<div id="dropArea">
		
		<legend>Upload a Document</legend>

			<h6 style="letter-spacing:.2em; text-align:center;">Drop Files Here</h6>
			
			<hr/>

		<div id="filelist"></div>


		</div>
		
		<h3 id="fileSelectMsg">Choose File(s) to Upload</h3>
		<a class="btn btn-inverse" id="pickfiles" href="#">Add Files</a>

	</div>
</div>

<script type="text/javascript">
            $(document).ready(function(){
                var uploader = new plupload.Uploader({
                    
                    runtimes : 'gears,html5,flash,silverlight,browserplus',
                    url:"/documents/upload?controller=works&id=<?=$work->id ?>",
                    browse_button : "pickfiles",
                    button_browse_hover : true,
                    drop_element : "dropArea",
                    autostart : true,
                    max_file_size: '250mb',
                    container: "FileContainer",
                    chunk_size: '1mb',
                    unique_names: false,
                    // Flash settings
                    flash_swf_url: "js/plupload.flash.swf",
                    // Silverlight settings
                    silverlight_xap_url: "js/plupload.silverlight.xap"
                });
                
                var fileTypes = '';
                var fileTypesFilter = 'allow';
                var $body = $("body");
                var $dropArea = $("#dropArea");

                $body.bind("dragenter", function(e){ 
                    $dropArea.addClass("draggingFile");
                    e.stopPropagation();
                    e.preventDefault();
                });

                $body.bind("dragleave", function(e){ $dropArea.removeClass("draggingFile"); });
                
                $body.bind("dragover", function(e){
                    $dropArea.addClass("draggingFile");
                    e.stopPropagation();
                    e.preventDefault();
                });

                $body.bind("drop", function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    $dropArea.removeClass();
                });

                $dropArea.bind("dragenter", function(e){
                    $dropArea.addClass("draggingFileHover");
                    e.stopPropagation();
                    e.preventDefault();
                });
                $dropArea.bind("dragleave", function(e){ $dropArea.removeClass("draggingFileHover"); });
                $dropArea.bind("dragover", function(e){
                    $dropArea.addClass("draggingFileHover");
                    e.stopPropagation();
                    e.preventDefault();
                });
                
                //Checks to make sure the browser supports drag and drop uploads
                uploader.bind('Init', function(up, params){
                    if(window.FileReader && $.browser.webkit && !( (params.runtime == "flash") || (params.runtime == "silverlight") ) )
                    {
                        $("#dropArea").show();
                        $("#fileSelectMsg").hide();
                    }
                });

                uploader.init();
                uploader.bind('FilesAdded', function(up, files) {
                    $dropArea.removeClass();
                    $.each(files, function(i, file) {
                        
                        //Checks a comma delimted list for allowable file types set file types to allow for all
                        
                        var fileExtension = file.name.substring(file.name.lastIndexOf(".")+1, file.name.length).toLowerCase();
                        var supportedExtensions = fileTypes.split(",");
                        
                        var supportedFileExtension = ($.inArray(fileExtension, supportedExtensions) >= 0);
                        if(fileTypesFilter == "allow")
                        {
                            supportedFileExtension = !supportedFileExtension
                        }

                        if( (fileTypes == "all") || supportedFileExtension)
                        {
                            var filename = file.name;
                            if(filename.length > 50)
                            {
                                filename = filename.substring(0,50)+"...";       
                            }
                                
                             $('#filelist').append(
	                            '<div id="' + file.id + '" class="fileItem"><div class="filename data"><p>' + 
	                            filename + '</p></div><div class="sizing data"><p>' + plupload.formatSize(file.size) +
	                            ' / ' + '<span class="percentComplete">0%</span></p></div>' + 
	                            '<div class="plupload_progress"><div class="progress progress-striped active">' +
	                            '<div class="bar"></div></div></div><hr>'
                            );
                                
                                

                    
                            //Fire Upload Event
                            up.refresh(); // Reposition Flash/Silverlight
                            uploader.start();

                            //Bind cancel click event
                            $('#cancel'+file.id).click(function(){
                                $fileItem = $('#' + file.id);
                                $fileItem.addClass("cancelled");
                                uploader.removeFile(file);
                                currentStorage -= ( (file.size)/(1024*1024) );
                                $(this).remove();
                            });
                        }
                        else
                        {
                            //Not a supported file extension
                            $errorPanel = $('div.error:first');
                            $errorPanel.show().html('<p>The file you selected is not supported in this section.');
                        }
                    });
                });

                uploader.bind('UploadProgress', function(up, file) {
                    var  $fileWrapper = $('#' + file.id);
                    $fileWrapper.find(".plupload_progress").show();
                    $fileWrapper.find(".bar").attr("style", "width:"+ file.percent + "%");
                    $fileWrapper.find(".percentComplete").html(file.percent+"%");
                    $fileWrapper.find('#cancel'+file.id).addClass('hide');
                });
                

                uploader.bind('Error', function(up, err) {
                    $errorPanel = $("div.error:first");
                    //-600 means the file is larger than the max allowable file size on the uploader thats set in the options above.
                    if(err.code == "-600")
                    {
                        $errorPanel.show().html('<p>The file you are trying to upload exceeds the single file size limit of 250MB</p>');
                    }
                    else
                    {
                        $errorPanel.show().html('<p>There was an error uploading your file '+ err.file.name + '.</p>');
                    }

                    $('#' + err.file.id).addClass("cancelled");
                    uploader.stop();
                    uploader.refresh(); // Reposition Flash/Silverlight
                });

                uploader.bind('FileUploaded', function(up, file, response) {
                    $fileItem = $('#' + file.id);
                    $fileItem.find('.progress').removeClass('active progress-striped');
                    $fileItem.find('.progress').addClass('progress-success');
                    $('#cancel'+file.id).remove();
                    
                    //$('#filelink').html('<div class="alert">Your finished uploads will be available on the <a href="/documents">Documents</a> page.</div>');
                    
                     response = jQuery.parseJSON( response.response );
                     
                	 location.reload(true); 
                });
                
               
            });
          
    </script>
	</div>
</div>

<div class="modal fade hide" id="collectionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Artwork to a Collection</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_collections as $oc): ?>
				<tr>
					<td>
						<a href="/collections/view/<?=$oc->slug ?>">
							<strong><?=$oc->title ?></a></strong><br/>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oc, array('url' => "/collections_works/add", 'method' => 'post')); ?>
			<input type="hidden" name="collection_id" value="<?=$oc->id ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
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

<div class="modal fade hide" id="exhibitionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Artwork to an Exhibition</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_exhibitions as $oe): ?>
				<tr>
					<td>
						<a href="/exhibitions/view/<?=$oe->slug ?>">
							<strong><?=$oe->title ?></a></strong><br/>
							<?=$oe->venue ?><br/>
							<?=$oe->dates() ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oe, array('url' => "/exhibitions_works/add", 'method' => 'post')); ?>
			<input type="hidden" name="exhibition_id" value="<?=$oe->id ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
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

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artwork</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$work->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Artwork from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($work, array('url' => "/works/delete/$work->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
