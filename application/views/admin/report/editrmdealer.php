<style>
.error{color:red;}
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit RM Dealer Mapping
      </h1><br>

      <h3>
        RM : <?php echo $dealer_data[0]['rm_name'];?><br><br>
      </h3>
      <?php if(!empty($dealer_data[0]['dealer_id']))
            {
      ?>
      <h3>
        Dealers :
      </h3>
  <form action="<?php echo base_url('Report/updatermdealer');?>" method="post">
      Select All Dealers <input type="checkbox" name="checkall" id="checkall"><br><br>
      <?php foreach($dealer_data as $dealer){
              $checked=($dealer['is_active'])?'checked':'';
      ?>
       
        <input type="checkbox" name="dealercheck[]" class="dealercheck"  id="dealercheck_<?php echo $dealer['id']?>" value="<?php echo $dealer['id']?>" <?php echo $checked?>><?php echo $dealer['ad_name'].'('.$dealer['dealer_name'].')'?>
        <input type="hidden" name="mapping[]" id="mapping" value="<?php echo $dealer['id']?>">
        <input type="hidden" name="active[]" id="active_<?php echo $dealer['id']?>" value="<?php echo $dealer['is_active']?>" class="active">
        <br>

      <?php } ?>  
     <br><br>

     <button type="submit" class="btn btn-success" >Submit</button>

  <?php } else{?>
      <h3> No Dealers assigned yet for <?php echo $dealer_data[0]['rm_name']; ?></h3>
<?php  } ?>   
  </form>

        
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
   $(document).on("click", "#checkall", function () {
    $('.dealercheck').prop('checked',$(this).prop('checked'));
    if($(this).prop('checked')){
      $('.active').val("1");
    }
    else{
      $('.active').val("0");
    }
  });

  $(document).on("change", ".dealercheck", function () {
    var element=$(this).attr('id');
    var offset=element.split('_')[1];
    if(this.checked){
      $('#active_'+offset).val("1");
    }
    else{
      $('#active_'+offset).val("0");
      $('#checkall').prop("checked",false); 
    }  
  });
});
</script>