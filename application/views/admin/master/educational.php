<div id="myModalAdd" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Education</h4>
      </div>
      <div class="modal-body">
         <form action="<?php echo base_url();?>admin/educational_action"  method="POST" id="">
            <div class="box-body">
               <div class="row">
              <div class="col-sm-12">
                <label for="">Name</label>
                <input type="hidden" name="id">
                <input type="text" value="" class="form-control input-sm " name="name" >
              </div>
               <div class="col-sm-12">
                <br>
              <button type="submit" class="btn btn-info">Add</button>
              </div>                 
               </div>
            </div>
      </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Edit -->
<div id="modal_edit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
                <h4 class="modal-title"></h4>

        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form action="<?php echo base_url();?>admin/educational_action"  method="POST" id="">
            <div class="box-body">
               <div class="row">
              <div class="col-sm-12">
                <label for="">Name</label>
                <input type="hidden" name="id">
                <input type="text" value="" class="form-control input-sm " name="name" >
              </div>
               <div class="col-sm-12">
                <br>
              <button type="submit" class="btn btn-info">Update</button>
              </div>                 
               </div>
            </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




<div class="wrapper">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
  
       </section>
    <!-- Main content -->
    <section class="content">

      <div class="box">

        <button type="button" class="btn btn-info" id="" style="margin-top: 7px;" onclick="add(50 )">Add</button>
            <!-- /.box-header -->
            <div class="box-body">
                
                      <table id="educational" class="table table-bordered table-striped" width="100%">
                <thead>

                                        <tr>
                                            <th>Sr.No</th>
                                            <th> Name</th>
                                            <th>Action                                           
                                            </th> 
                                        </tr>
                                    </thead>
                <tbody>
              

               
                </tbody>
              </table>

             
            </div>



            <!-- /.box-body -->
          </div>
        
          <!-- /.box -->
        
      <!-- /.row -->


    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>




<!-- ./wrapper -->

<script>

  $(document).ready(function() {

    var dataTable = $('#educational').dataTable({
      "scrollX": true,
      "processing": true,
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      "sDom": '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
      "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
      "ajax": {
        url: "<?php echo base_url(); ?>admin/educational_ajax", 
        type: "post",
      }        
    });
    
  });  

  function add()
{
  $("#myModalAdd").modal("show");
}

function Edit(id)
{
  //$('#form_edit')[0].reset(); // reset form on modals
        //$('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url : base_url + "Master/educational_data_ajax/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="name"]').val(data.name);
                $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Education'); // Set title to Bootstrap modal title

                

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

}



function Remove(id)
{
        
 if(confirm("Are you sure want to delete?"))
    {
        $.ajax({
            url : base_url + "Master/Remove_educational_data_ajax/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
            alert(data.Message)     
            location.reload();
                

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

    }
    else
    {
        e.preventDefault();
    }

}




</script>


</script>