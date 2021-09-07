<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<style type="text/css">
/*-----------------
	Table
-----------------------*/
table, th, td {	border: 0;}
.table {
	color: #333;
}
.table.table-white {
	background-color: var(--white);
}
.table thead th {
	font-weight: 500;
    letter-spacing: 0.05em;
}
.table-striped > tbody > tr:nth-of-type(2n+1) {
	background-color: #f6f6f6;
}
table.table td h2 {
	display: inline-block;
	font-size: inherit;
	font-weight: 400;
	margin: 0;
	padding: 0;
	vertical-align: middle;
}
table.table td h2.table-avatar {
    align-items: center;
    display: inline-flex;
    font-size: inherit;
    font-weight: 400;
    margin: 0;
    padding: 0;
    vertical-align: middle;
	white-space: nowrap;
}
table.table td h2.table-avatar.blue-link a {
	color: #007bff;
}
table.table td h2 a {
	color: #333;
}
table.table td h2 a:hover {
	color: #ff9b44;
}
table.table td h2 span {
	color: #888;
	display: block;
	font-size: 12px;
	margin-top: 3px;
}
table.dataTable {
	margin-bottom: 15px !important;
	margin-top: 15px !important;
}
.table-nowrap td,
.table-nowrap th {
	white-space: nowrap
}
.table-hover tbody tr:hover {
    background-color: #f7f7f7;
    color: #212529;
}
table.dataTable thead > tr > th.sorting_asc, 
table.dataTable thead > tr > th.sorting_desc, 
table.dataTable thead > tr > th.sorting, 
table.dataTable thead > tr > td.sorting_asc, 
table.dataTable thead > tr > td.sorting_desc, 
table.dataTable thead > tr > td.sorting {
	padding-right: 30px !important;
}
table.dataTable thead > tr > th {
	font-weight: 600;
	background-color: #183883;
	color: #fff;
}
.custom-table tr {
	background-color: #fff;
	box-shadow: 0 0 3px #e5e5e5;
}
.table.custom-table > tbody > tr > td,
.table.custom-table > tbody > tr > th,
.table.custom-table > tfoot > tr > td,
.table.custom-table > tfoot > tr > th,
.table.custom-table > thead > tr > td,
.table.custom-table > thead > tr > th {
	padding: 10px 8px;
	vertical-align: middle;
}
.table.custom-table > thead > tr > th,
.table.custom-table > tbody > tr > td {	
	border: 0;
    text-align: initial;
    border-radius: 0;    
    font-size: 13px;
}
.table.custom-table > tbody > tr > td {
	padding: 6px 8px;
	background-color: transparent;
}
.table.custom-table > tbody > tr > td:first-child,
.table.custom-table > thead > tr > th:first-child {
	padding-left: 15px;
}
.table.custom-table > tbody > tr > td:last-child,
.table.custom-table > thead > tr > th:last-child {
	padding-right: 15px;
}
#renewal_policy_datatable_wrapper .dt-buttons {
	width: 50%;
    float: left;
}
</style>
<div class="page main-ewnow">
    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
                <ul>
                    <li class=""><a href="<?php echo base_url('dealer_request_data');?>">Dealer Request Data</a></li>
                    <li class=""><a href="<?php echo base_url('summary_page');?>">Summary</a></li>
                    <li class=""><a href="<?php echo base_url('myDashboardSection');?>"> Dealer Bank Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class=""><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class=""><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('renewal_policy');?>"> Policy Renewal </a></li>
                    <li class="active"><a href="<?php echo base_url('expired_policies');?>">Expired Policies </a></li>
                </ul>   
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
              
                <h3 class="adminpage-pagehead">Expired Policies</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="col-md-6 form-group form-inline text-left">
                        	  <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>                           
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="engine_no" id="engine_no" placeholder="Engine/Chassis/Policy No.">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="button" class="form-control btn btn-primary" name="" id="expired_policy_btn" value="submit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" style="border-top: 1px solid #ddd; padding-top: 15px;">
                                <table class="table table-striped custom-table datatable" id="expired_policies_tbl" border="0">
                                    <thead>
                                    	<tr>
	                                        <th>Sr.No.</th>
	                                        <th>Policy No</th>
	                                        <th>Customer Name</th>
	                                        <th>IC NAME</th>
	                                        <th>Engine No</th> 
	                                        <th>Chassis No</th>
	                                        <th>Policy Start Date</th>
	                                        <th>Policy End Date</th>
	                                        <th>Created Date</th>
	                                        <th>Action</th>
	                                    </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>

                                </table>
                            </div>
                        </div>
                            <div class="col-md-6 pull-right" id="image_loader">
                            <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
                          </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            
        </div>
    </div>    
</div>
<script type="text/javascript">
  $("#image_loader").css("display","none");
 $(document).ajaxStart(function() {
        // show loader on start
        $("#image_loader").css("display","block");
    }).ajaxSuccess(function() {
        // hide image_loader on success
        $("#image_loader").css("display","none");
    }); 
</script>