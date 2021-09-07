<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">



<div class="page">

    <div class="container">
        <div class="page-header" style="padding-right: 0px; padding-left: 0px;">
            <h3><center>Motor Insurance</center></h3>
            <div class="text-center blue-grey-800 m-0 mt-50 radio-btn">

                <ul class="list-inline font-size-14">
                    <li class="list-inline-item">
                        <label><input type="radio" id="today" name="temp_radio" checked>&nbsp;&nbsp;Today</label>
                    </li>
                    <li class="list-inline-item ml-20">
                        <label><input type="radio" id="month" name="temp_radio">&nbsp;&nbsp;Month</label>
                    </li>
                    <li class="list-inline-item ml-20">
                        <label><input type="radio" id="quarter" name="temp_radio">&nbsp;&nbsp;Quarter</label>
                    </li>
                    <li class="list-inline-item ml-20">
                        <label><input type="radio" id="year" name="temp_radio">&nbsp;&nbsp;Year</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row" data-plugin="matchHeight" data-by-row="true" style="margin: 0;">
            <div class="col-xxl-12">
                <div class="row h-full">

                    <div class="col-xs-12 col-lg-4">
                        <!-- Panel Overall Sales -->
                        <div class="card card-shadow card-inverse bg-blue-600 white">
                            <div class="card-block p-10">
                                <div class="counter counter-lg counter-inverse text-center">
                                    <div class="counter-label mb-20">
                                        <div class="counter-number"><h4>Policy Premium</h4></div>
                                    </div>
                                    <div class="counter-number-group mb-15">
                                        <span class="counter-number-related" >Rs.</span>
                                        <span class="counter-number" id="premium"></span>
                                    </div>
                                    <div class="counter-label">

                                        <div class="counter counter-sm text-center">
                                            <div class="counter-number-group">
<!--                                                <span class="counter-number font-size-14">42%</span>
                                                <span class="counter-number-related font-size-14">HIGHER THAN YESTERDAY</span>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Panel Overall Sales -->
                    </div>
                    <div class="col-xs-12 col-lg-4">
                        <!-- Panel Today's Sales -->
                        <div class="card card-shadow card-inverse bg-pink-600 white">
                            <div class="card-block p-10">
                                <div class="counter counter-lg counter-inverse text-center">
                                    <div class="counter-label mb-20">
                                        <div class="counter-number"><h4>No Of Policy</h4></div>
                                    </div>
                                    <div class="counter-number-group mb-15">
                                        <span class="counter-number-related"></span>
                                        <span class="counter-number" id="policies"></span>
                                    </div>
                                    <div class="counter-label">

                                        <div class="counter counter-sm text-center">
                                            <div class="counter-number-group">
<!--                                                <span class="counter-number font-size-14">20</span>
                                                <span class="counter-number-related font-size-14">NEW POLICIES SOLD TODAY</span>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Panel Today's Sales -->
                    </div>

                    <div class="col-xs-12 col-lg-4">
                        <!-- Panel Today's Sales -->
                        <div class="card card-shadow card-inverse bg-blue-600 white">
                            <div class="card-block p-10">
                                <div class="counter counter-lg counter-inverse text-center">
                                    <div class="counter-label mb-20">
                                        <div class="counter-number"><h4>Revenue</h4></div>
                                    </div>
                                    <div class="counter-number-group mb-15">
                                        <span class="counter-number-related">Rs.</span>
                                        <span class="counter-number" id="payout"></span>
                                    </div>
                                    <div class="counter-label">

                                        <div class="counter counter-sm text-center">
                                            <div class="counter-number-group">
<!--                                                <span class="counter-number font-size-14">2%</span>
                                                <span class="counter-number-related font-size-14">HIGHER THAN YESTERDAY</span>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Panel Today's Sales -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dashboard.js"></script>