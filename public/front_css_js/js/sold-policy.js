
    $(document).ready(function() {
        var dataTable = $('#privatecar').dataTable({
          "scrollX": true,
          "processing": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'info'        : true,
          'autoWidth'   : false,
          'ordering'    : true,
          "order": [[ 0, "asc" ]],
          'dom': 'Bfrtip',
          //"sDom": '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url +"front/myaccount/Dashboard/SoldPolicy_data", 
              type: "post",
          }        
        });
    });