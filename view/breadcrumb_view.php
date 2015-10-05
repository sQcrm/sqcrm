<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* breadcrumb view  
* @author Abhik Chakraborty
*/  
          
?>
<div id="breadcrumb_loader"></div>
<script>
$(document).ready(function() {
  $.ajax({
    type: "GET",
    <?php
      $e_load_breadcrumb = new Event("CRMEntityRecentlyViewed->eventAjaxLoadRecentlyViewed");
      $e_load_breadcrumb->setEventControler("/ajax_evctl.php");
      $e_load_breadcrumb->setSecure(false);
    ?>
    url: "<?php echo $e_load_breadcrumb->getUrl(); ?>",
    beforeSubmit: function() {
     $('#breadcrumb_loader').html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
    },
    success:  function(html) {
     $('#breadcrumb_loader').html(html);
    }
  });
});
</script>