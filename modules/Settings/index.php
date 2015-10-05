<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

   /**
    * Profile listing page
    */  
//header("Location: ".NavigationControl::getNavigationLink("Settings","profile_list"));
//exit;

$list_view_fields = array("lastname","firstname","email","title","idorganization","assigned_to");
$data = implode("','",$list_view_fields);
$qry = "select * from fields where idmodule = 4 and field_name in ('".$data."') order by field(field_name,'".$data."')";
echo $qry; exit;

    $str = array(
                            "required"=>true,
                            "minlength"=>2,
                            "maxlength"=>20,
                            "notEqual"=>"Pick One"
                          
                );
    $j = json_encode($str);
    //echo $j;
    //echo '<br />';

    
    //print_r($fields_array);
    
    $do_import  = new Import();
    $data = $do_import->parse_import_file("test.csv",3);
    print_r($data);
    
    function getformValidation(){
      $f1 = '{"required":true,"minlength":2,"maxlength":20}';
      $f2 = '{"required":true,"minlength":2,"maxlength":20}';
      $f3 = '{"required":true}';
      $f4 = '{"required":true}';
      $f5 = '{"required":true,"notEqual":"Pick One"}'; 
      $f6 = '{"required":true}';

      $fields_array = array("f1"=>json_decode($f1,true),"f2"=>json_decode($f2,true),
        "f3"=>json_decode($f3,true),"idrole"=>json_decode($f4,true),"f5"=>json_decode($f5,true),"idcontact"=>json_decode($f6,true));
      //print_r($fields_array);
      $js = '';
      $js .= '$(\'#test\').validate({' ."\n";
      $js .= 'ignore:"",'."\n";// To allow hidden field validate
      $js .=  'rules : {';
      foreach($fields_array as $fieldname=>$validations){
        $js .=    $fieldname.' : {' ."\n";
        foreach($validations as $rule=>$val){
          if($rule == 'notEqual'){
            $js .=   $rule.' :"'.$val.'",'."\n";
          }else{
            $js .=   $rule.' :'.$val.','."\n";
          }
        }
        $js .=    '},'."\n";
      }
      $js .= '},'."\n";

      $js .= 'highlight: function(label) {
                $(label).closest(\'.control-group\').addClass(\'error\');
            },
            success: function(label) {
                label
                        .text(\'OK!\').addClass(\'valid\')
                        .closest(\'.control-group\').addClass(\'success\');
            }';
      $js .= '});'."\n";
      return $js ;
    }

//$chk = $_GET["chk"];
//print_r($chk);
//echo date("Y-m-d",strtotime("03-01-2013"));
function price_to_float($s){
  $s = str_replace(',','.',$s);
  $s= preg_replace("/[^0-9\.pot_to_grp_rel]/","",$s);
  $has_decimal_val = (substr($s,-3,1) == '.');
  $s = str_replace('.','',$s);
  if($has_decimal_val){
    $s = substr($s,0,-2).'.'.substr($s,-2);
  }
  return (float)$s;
}
echo $_POST["demo4"].'<br />';
echo price_to_float($_POST["demo4"]).'<br />';


?>
<script type="text/javascript" src="/js/plugins/jquery.maskMoney.js"></script>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12" style="margin-left:3px;">
      <div class="row-fluid">
        <div class="datadisplay-outer">
          <form class="form-horizontal" id="test" name="test" action="" method="post">
          <table>
              <tr>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="f1"><?php echo _('Field 1')?></label>  
                        <div class="controls">  
                          <input type="text" class="input-xlarge-100" id="f1" name="f1"> 
                        </div>
                      </div>
                  </td>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="f2"><?php echo _('Field 2')?></label>  
                        <div class="controls">  
                          <input type="text" class="input-xlarge-100" id="f2" name="f2"> 
                        </div>
                      </div>
                  </td>
               </tr>
               <tr>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="f3"><?php echo _('Field 3')?></label>  
                        <div class="controls">  
                          <input type="text" class="input-xlarge-100" id="f3" name="f3"> 
                        </div>
                      </div>
                  </td>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="f4"><?php echo _('Field 4')?></label>  
                        <div class="controls">  
                          <input type="text" class="input-xlarge-100" id="f4" name="f4"> 
                        </div>
                      </div>
                  </td>
               </tr>
               <tr>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="idvendor"><?php echo _('Vendor')?></label>  
                        <div class="controls">  
                          <?php
                              echo FieldType166::display_field('idproduct');
                          ?>
                        </div>
                      </div>
                  </td>
                   <td>
                      <div class="control-group">  
                        <label class="control-label" for="f5"><?php echo _('Dropdown')?></label>  
                        <div class="controls">  
                          <select name="f5" id="f5">
                              <option value="Pick One">Pick One</option>
                              <option value="One">One</option>
                              <option value="Two">Two</option>
                              <option value="Three">Three</option>
                              <option value="Four">Four</option>
                          </select>
                        </div>
                      </div>
                  </td>
               </tr>
               <tr>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="idrole"><?php echo _('Related to')?></label>  
                        <div class="controls">  
                          <?php
                              echo FieldType131::display_field('idorganization');
                          ?>
                        </div>
                      </div>
                  </td>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="assigned_to"><?php echo _('Assigned To')?></label>  
                        <div class="controls">  
                          <?php
                              echo FieldType142::display_field('idcontact');
                          ?>
                        </div>
                      </div>
                  </td>
               </tr>
               <tr>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="idrole"><?php echo _('Received Date')?></label>  
                        <div class="controls">  
                          <?php
                              echo FieldType9::display_field('rec_date','2013-03-21');
                          ?>
                        </div>
                      </div>
                  </td>
                  <td>
                      <div class="control-group">  
                        <label class="control-label" for="idrole"><?php echo _('Note')?></label>  
                        <div class="controls">  
                          <?php
                              echo FieldType21::display_field('note');
                          ?>
                        </div>
                      </div>
                  </td>
               </tr>
              <tr>
                <td>
                  <input type="text" id="demo4" name="demo4">
                  <script type="text/javascript">$("#demo4").maskMoney({ thousands:' ', decimal:',',precision:2});</script>
                </td>
              </tr>
              
           </table>
           
						<?php require("view/add_view_line_items.php"); ?>		
							
          <div class="form-actions">  
            <input type="submit" class="btn btn-primary" value="<?php echo _('Add');?>"/>
          </div>
        </form>
      </div>
     </div><!--/row-->
    </div><!--/span-->
  </div><!--/row-->
</div>
<script>
<?php /*echo getformValidation();*/?>
$.validator.addMethod("notEqual", function(value,element,param){
    return this.optional(element) || value != param;
  },"Please select a value "
);
/*$(document).ready(function(){
  // Update Profile form
            $('#test').validate({
            rules: {
              f1: {
                minlength: 2,
                maxlength:20,
                required: true
              },
              f2: {
                minlength: 2,
                maxlength:20,
                required: true
              },
              f3: {
                minlength: 2,
                maxlength:20,
                required: true
              }
            },
            highlight: function(label) {
                $(label).closest('.control-group').addClass('error');
            },
            success: function(label) {
                label
                        .text('OK!').addClass('valid')
                        .closest('.control-group').addClass('success');
            }
          });
});*/ // end document.ready
      
</script>