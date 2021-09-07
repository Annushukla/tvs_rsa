@extends(activeTemplate() .'layouts.app')
@section('style')

@stop
@section('content')
<div class="container-fluid">

        <div class="animated fadeIn">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                    {{-- <div class="card"> 
                         <div class="card-body">
                            <form action="/Dashboard/changemobile?class=form-horizontal" method="post" novalidate="novalidate">                                <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label" for="text-input">Current Mobile No.</label>
                                                               
                                                                <div class="col-sm-3">
                                                                    <div class="Dropdown">
                                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">All
                                                                        <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu">
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">CSS</a></li>
                                                                          <li><a href="#">JavaScript</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                          <li><a href="#">HTML</a></li>
                                                                        </ul>
                                                                      
                                                                    
                                            
                                                                    </div>
                                                                
                                                                
                                                                </div>
                                                                <div class="col-sm-3">
                                                                        <button type="submit" class="btn btn-sm btn-success">Serach
                                                                                
                                                                            </button>
                            
                                                                </div>
                                                                <div class="col-sm-3">
                                                                        <button type="submit" class="btn btn-sm btn-success">Add Campaign
                                                                                
                                                                            </button>
                            
                                                                </div>
                                                                
                                                            </div>
                            </form>                        </div>
                            
                                                   
                                                      
                                                </div> --}}

                                               

                                                <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="card">
                                                             <div class="campaign-large-block">
                                                                 <div class="row">
                                                                       <div class="col-lg-4">
                                                                        <img src="{{ get_image(config('constants.logoIcon.path') .'/thank-you.jpg') }}"
                                                                        alt="logo-image" style="height:270px;" />
                                                                       </div>
                                                                       <div class="col-lg-8">
                                                                        <div class="campaign-content">
                                                                       <h4>Help me raise funds (FDG9GM8S)</h4>
                                                                       <p><span>Created On</span> : 01-Aug-2016 03:05:40 <span>Last Updated On</span> : 15-May-2019 05:28:49 <span>Status</span> : Published</p>
                                                                       <p><span>Fund : ₹ 206,180.00 INR of ₹ 5,000,000.00 INR</span></p>
                                                                       <p><span>Campaign URL</span> : <span class="link">https://onlinesensor.com/mishika</span></p>
                                                                       <div class="btn-group">
                                                                       <button type="button" class="btn btn-default">Edit</button>
                                                                       <button type="button" class="btn btn-default">View</button>
                                                                       </div>
                                                                       </div>
                                                                       </div>
                                                                 </div>
                                                                </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                
                        
                       
                    <!-- end card -->
                    <!-- end card -->
                </div>

            </div>
            <!--/.row-->
        </div>
    </div>


    






            @endsection