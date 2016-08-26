<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* detail view invoice payment cpanel
* @author Abhik Chakraborty
*/  
?>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<div class="box_content">
	<div class="alert alert-info">
	<?php
		echo _('Payment details');
		echo '&nbsp;&nbsp;&nbsp;';
		echo _('Due Amount : ').'<span id="due_amount">'.FieldType30::display_value($due_amount).'<span>';
	?>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="datadisplay dt-responsive" id="invoice_payments" cellspacing="0" width="100%">  
				<thead>  
					<tr>  
						<th width="10%"><?php echo _('Date');?></th>  
						<th width="18%"><?php echo _('Amount');?></th>  
						<th width="17%"><?php echo _('Ref No.')?></th> 
						<th width="10%"><?php echo _('Mode')?></th>
						<th width="15%"><?php echo _('Transaction Type')?></th> 
						<th width="30%"><?php echo _('Note')?></th>
					</tr> 
				</thead>
				<tbody id="payment_details_tbody">
				<?php
				if (count($payments) > 0) {
					foreach ($payments as $key=>$val) {
						echo '<tr>';
						echo '<td>'.FieldType9::display_value($val['date_added']).'</td>';
						echo '<td>'.FieldType30::display_value($val['amount']).'</td>';
						echo '<td>'.FieldType1::display_value($val['ref_num']).'</td>';
						echo '<td>'.FieldType1::display_value($val['mode_name']).'</td>';
						echo '<td>'.FieldType1::display_value($val['transaction_type']).'</td>';
						echo '<td>'.nl2br($val['additional_note']).'</td>';
						echo '</tr>';
					}
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	oTable = $('#invoice_payments').dataTable({
		responsive: true,
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'Bfrtip',
		"bAutoWidth": false
	});    
});
</script>