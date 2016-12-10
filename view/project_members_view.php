<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project members view page
* @author Abhik Chakraborty
*/  
?>
<ul class="list-group" id="project_members">	
	
</ul>


<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="remove_confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to perform this operation ?');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// load the project member section 
		$.ajax({
			type: "GET",
			url: "/modules/Project/project_members",
			data : "ajaxreq="+true+"&rand="+generateRandonString(10)+"&sqrecord=<?php echo $sqcrm_record_id;?>",
			success: function(result) { 
				$('#project_members').html(result) ;
			},
			beforeSend: function() {
				$('#project_members').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
			}
		});
		
		//
		$('#project_members').on('click', '#add-project-members', function(e) {
			var memberToAdd = $('#project_members #project-member-selector').val();
			if (!memberToAdd) {
				display_js_error(NO_MEMBER_TO_ADD_PROJECT,'js_errors');
				return false;
			}
			var memberData = memberToAdd.split('::');
			
			var pendingUserHtml = '<div class="col-xs-3" style="margin-top:14px;" id="pending-member-'+memberData[1]+'">';
			if (memberData[6] == '-') {
				var initials = memberData[3].charAt(0)+''+memberData[4].charAt(0);
				pendingUserHtml += '<div style="float:left;background-color:'+memberData[7]+'" data-profile-initials= "'+initials.toUpperCase()+'" class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')">';
				pendingUserHtml += '<a href="#" onclick="removePendingRequest(\''+memberData[0]+'\',\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
			} else {
				pendingUserHtml += '<div class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')" style="float:left;background-image:url(\''+memberData[6]+'\')">';
				pendingUserHtml += '<a href="#" onclick="removePendingRequest(\''+memberData[0]+'\',\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
			}
			pendingUserHtml += '</div>';
			pendingUserHtml += '</div>';
			
			$('#project_members #add-project-members-button').html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			var qry_string = '&idproject='+memberData[0]+'&iduser='+memberData[1]+'&email='+memberData[5];
			$.ajax({
				type: "POST",
				<?php
				$e_save = new Event("Project->eventAddProjectMember");
				$e_save->setEventControler("/ajax_evctl.php");
				$e_save->setSecure(false);
				?>
				url: "<?php echo $e_save->getUrl(); ?>"+qry_string,
				success:  function(html) {
					if (html.trim() == '1') {
						$('#project_members #project-pending-requests-title').show();
						$('#project_members #project-pending-requests-row').append(pendingUserHtml);
						$('#project_members #project-member-selector option:selected').remove();
						$('#project_members #add-project-members-button').html('<input type="button" class="btn btn-primary" id="add-project-members" value="'+ADD_LW+'"/>');
					} else {
						display_js_error(html,'js_errors');
					}
				}
			});
		});
	});
	
	function removeProjectMember(idproject,iduser) {
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			var qry_string = '&idproject='+idproject+'&iduser='+iduser+'&type=accepted';
			$.ajax({
				type: "POST",
				<?php
				$e_del = new Event("Project->eventRemoveProjectMember");
				$e_del->setEventControler("/ajax_evctl.php");
				$e_del->setSecure(false);
				?>
				url: "<?php echo $e_del->getUrl(); ?>"+qry_string,
				success:  function(html) {
					if (html.trim() == '1') {
						var elementToBeRemoved = 'existing-member-'+iduser;
						$('#'+elementToBeRemoved).remove();
						// if the permission tab is loaded and removed user is a part of permission changer remove from there as well
						if ($('#detail_view_section #existing-permission-changer-'+iduser).length) {
							$('#detail_view_section #existing-permission-changer-'+iduser).remove();
						}
					} else {
						display_js_error(html,'js_errors');
					}
				}
			});
		});
	}
	
	function removePendingRequest(idproject,iduser) {
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			var qry_string = '&idproject='+idproject+'&iduser='+iduser+'&type=pending';
			$.ajax({
				type: "POST",
				<?php
				$e_del = new Event("Project->eventRemoveProjectMember");
				$e_del->setEventControler("/ajax_evctl.php");
				$e_del->setSecure(false);
				?>
				url: "<?php echo $e_del->getUrl(); ?>"+qry_string,
				success:  function(html) {
					if (html.trim() == '1') {
						var elementToBeRemoved = 'pending-member-'+iduser;
						$('#'+elementToBeRemoved).remove();
					} else {
						display_js_error(html,'js_errors');
					}
				}
			});
		});
	}
	
	function removeRejectedRequest(idproject,iduser) {
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			var qry_string = '&idproject='+idproject+'&iduser='+iduser+'&type=rejected';
			$.ajax({
				type: "POST",
				<?php
				$e_del = new Event("Project->eventRemoveProjectMember");
				$e_del->setEventControler("/ajax_evctl.php");
				$e_del->setSecure(false);
				?>
				url: "<?php echo $e_del->getUrl(); ?>"+qry_string,
				success:  function(html) {
					if (html.trim() == '1') {
						var elementToBeRemoved = 'rejected-member-'+iduser;
						$('#'+elementToBeRemoved).remove();
					} else {
						display_js_error(html,'js_errors');
					}
				}
			});
		});
	}
</script>