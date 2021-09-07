<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
    .colored_tbl { margin-bottom: 10px; }
    .colored_tbl th{ color: #fff; background-color: #7f868b; } 
    .colored_tbl td{background-color: #ffebe3; } 
    .colored_tbl>tbody>tr>td, .colored_tbl>tbody>tr>th, .colored_tbl>tfoot>tr>td, .colored_tbl>tfoot>tr>th, .colored_tbl>thead>tr>td, .colored_tbl>thead>tr>th { padding: 5px; }
    .table-bordered.colored_tbl>thead>tr>th, .table-bordered.colored_tbl>tbody>tr>th, .table-bordered.colored_tbl>tfoot>tr>th, .table-bordered.colored_tbl>thead>tr>td, .table-bordered.colored_tbl>tbody>tr>td, .table-bordered.colored_tbl>tfoot>tr>td { border-color: #7c2a0b; }
    .colored_tbl>caption+thead>tr:first-child>td, .colored_tbl>caption+thead>tr:first-child>th, .colored_tbl>colgroup+thead>tr:first-child>td, .colored_tbl>colgroup+thead>tr:first-child>th, .colored_tbl>thead:first-child>tr:first-child>td, .colored_tbl>thead:first-child>tr:first-child>th {border-top-width: 1px}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>RM Master</h1>
<br><br>

<?php $failed_message = $this->session->flashdata('failed');
    $success_message = $this->session->flashdata('success');
     $admin_session = $this->session->userdata('admin_session');
if(!empty($failed_message)){
    ?>
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-warning" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
    <h4><?php echo isset($failed_message) ? $failed_message : '';?></h4>
  </div>
</div>

<?php } 
 if(!empty($success_message)) {?>
<div class="row form-group">
    <div class="col-md-4 form-group alert alert-success" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>
<?php }?>
<br><br>
 <div class="row form-group">
    <div class="col-md-2">
        <label>Start Date :</label>
        <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
    </div>
    <div class="col-md-2">
        <label>End Date :</label>
        <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
        <span id="err_end_date" style="color:red;"></span>
    </div>
    <div class="col-md-2">
        <label> &nbsp;</label>
        <button type="button" class="form-control btn btn-primary" name="submit" id="date_filter" value="Submit">Submit</button>
    </div>
</div>
    <br><br>
    <?php if(($admin_session['admin_role_id'] == 1 && $admin_session['admin_role'] == 'admin_master') || ($admin_session['admin_role_id'] == 2 && $admin_session['admin_role'] == 'opreation_admin')) { ?> 
     <div class="row form-group">
      <div class="col-md-2 pull-right">
         <a href="<?php echo base_url('admin/add_rm');?>" class="btn btn-primary">Add RM</a>
      </div>
     </div>
 <?php }?>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="rm_datatable">
              <thead>
                <th>Sr.no</th>
                <th>RM Id</th>
                <th>RM Name</th>
                <th>Contact No.</th>
                <th>RM Email</th>
                <th>Created Date</th>
                <th>Add</th>
                <th>Edit</th>
              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div>    


   <div class="modal fade" id="assign_dealer_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    <form action="<?php echo base_url('Report/assignrmdealer');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Assign Dealer</h2>
          <h2>RM : <span id="rmname"></span> </h2>
        </div>
    <div class="modal-body" id="dealer_assign_body">
        <div class="row form-group">
          <div class="col-md-6">
            Dealers : <select id="dealers" name="dealers" class="form-control company_type" data-message="Dealers" required>
                  <option value="">Select Dealers</option>
            </select>
            <input type="hidden" name="rm_id" id="rm_id">
            <input type="hidden" name="dealer_name" id="dealer_name">
            <input type="hidden" name="rm_name" id="rm_name">
            <input type="hidden" name="sap_ad_code" id="sap_ad_code">
          </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;
        <button type="submit" class="btn btn-success" >Submit</button>
    </div>
</div>
  </form>
</div>
 </div>  

 <div class="modal fade" id="edit_dealer_modal" role="dialog">
    <div class="modal-dialog modal-lg">
  <form action="<?php echo base_url('Report/editrmdealer');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Edit Dealer</h2>
          <h2>RM : <span id="editrmname"></span> </h2>
        </div>

        <div class="modal-body" id="dealer_edit_body">
            
            <div class="row form-group">
              <div class="col-md-6">

              </div>
            </div>
        </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;
          <button type="submit" class="btn btn-success" >Submit</button>
      </div>
    </div>
  </form>
</div>
 </div>  

</section>

</div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

  table = $('#rm_datatable').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'admin/rm_ajax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
                console.log(jsonData);
                    
                      return jsonData.data;
              }
          },
   
          //Set column definition initialisation properties.
          "columnDefs": [
          { 
              "targets": [ 0 ], //first column / numbering column
              "orderable": false, //set not orderable
          },
          ],
   
      });
  $('#date_filter').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#rm_datatable').DataTable({
                'paging': true,
                'destroy': true,
                "ajax": {
                    'url': base_url + 'admin/rm_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date},
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

                "scrollX": true,
                "processing": true,
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'dom': 'Bfrtip',
                "buttons": ['excel', 'csv', 'pdf', 'print'],
                // Load data for the table's content from an Ajax source

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });

    $(document).on("change",".dealercheck",function(){
      var element_id=$(this).attr('id');
      var offset = element_id.split('_')[1];
     //console.log('O '+offset);
     $('#mapping_value_'+offset).val(($(this).is(":checked") ? "1" : "0"));
    });
  });

  function addrmdealer(rm_id,rm_name){
    if(rm_id!=""){
      $.ajax({
        url : base_url+'Report/getDealers',
        type : 'post',
        data : {rm_id},
        dataType : 'JSON',
        success : function(response){
          console.log(response);
            $.each(response,function(key, value) 
            {
              //console.log(value.dealer_name);
              $('#dealers').append('<option value=' + value.id + ' data-sap_ad_code=' + value.sap_ad_code +' data-dealername="' + value.dealer_name + '">' + value.ad_name + '(' + value.dealer_name + ')</option>');
            });
            $('#rm_id').val(rm_id);
            $('#rmname').text(rm_name);
            $('#rm_name').val(rm_name);
         }
      });
      $('#assign_dealer_modal').modal();
    }
  }

  $('#dealers').change(function(){
    //console.log('Dealer '+$('option:selected',this).data('dealer'));
    $('#dealer_name').val($(this).find(':selected').data('dealername'));
    $('#sap_ad_code').val($(this).find(':selected').data('sap_ad_code'));
  });

  function editrmdealer(rm_id,rm_name){
    if(rm_id!=""){
      $.ajax({
        url : base_url+'Report/getrmDealers',
        type : 'post',
        data : {rm_id},
        dataType : 'JSON',
        success : function(response){
         // console.log(response);
            $('#dealer_edit_body').empty();
            $.each(response,function(key, value) 
            {
              $('#dealer_edit_body').append('<input type=checkbox name=dealercheck[] class=dealercheck  id=dealercheck_'+key+' value='+ value.id +'>'+ value.ad_name +'('+ value.dealer_name +')<br>');
              $('#dealercheck_'+key).prop("checked", value.is_active == '1');
              $('#dealer_edit_body').append('<input type=hidden name=mapping[] class=mapping  id=mapping'+key+' value='+ value.id +'>');
              $('#dealer_edit_body').append('<input type=hidden name=mapping_value[] class=mapping_value  id=mapping_value_'+key+' value='+value.is_active+'>');
            });
            $('#rm_id').val(rm_id);
            $('#editrmname').text(rm_name);
        }
      });
      $('#edit_dealer_modal').modal();
    }
  }

  console.log('Checkbox ' + $(".dealercheck").val())
</script>