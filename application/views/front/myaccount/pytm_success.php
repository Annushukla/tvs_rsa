

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">



<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<main class="section--lightGray main-ewnow" style="  font-family: 'Roboto';">
   <div class="container marT20 margnB5">
        <div class="row">
            <div class="col-md-12 custom-bg-dark text-center">
                <div class="col-md-12 white-bg" style="height:  auto;">
                    <div class="row">
                    	<div class="col-md-12">
                    		<h3 align="center" style="color: #333;">Congratulations</h3>
	                    	<h4 class="center" style="color: #333;">Policy is successfully generated.</h4>
	                    </div>
	                    <div class="col-md-12" style="margin: 20px 0; ">
							<!-- <div class="col-md-4">
								<a href="<?= base_url($url);?>" class="btn btn-info margnB5" role="button">Create Another Policy</a>
							</div> -->
							<div class="col-md-4">
							<a href="<?= base_url().$ic_pdf.'/'.$inserted_id?>" target="_blank" class="btn btn-info margnB5" role="button">Download Policy PDF</a>
							</div>

							<!-- <div class="col-md-4">
							<a href="<?= base_url('sold_rsa_policy');?>" class="btn btn-info margnB5" role="button">All Certificates</a>
							</div> -->
						</div>
                        <?php if($inserted_id!=""){?>
                        <div class="col-md-12">
                            <iframe src="https://www.myassistancenow.com/uat/tvs_rsa_new/rsavideo/?id=222&text=<?php echo $inserted_id;?>" style="width: 1024px; height: 700px; border:0;"></iframe>
                        </div>
                    <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/generate_pa_policy.js"></script>
