<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>RSA | Generate Policy</title>

  <?php include("includes/head.php"); ?>

</head>

<body id="page-top">

  <!-- Navigation -->
  <?php include("includes/header.php"); ?>

  <!-- Banner Image -->
  <header class="text-white header">
    <div class="overlay"></div>
    <div class="container text-center">
      <h1>RoadSide Assistance</h1>
      <p class="lead">Roadside Assistance is a 24x7 emergency service  <br>which is offered in case of breakdown of a vehicle</p>
    </div>
  </header>

  <!-- Content -->
  <section>
    <div class="container">
      <div class="content mb-4">
        <h3 class="mb-3">Search customer by vehicle's Engine No. if it exists in our Database</h3>
          <div class="row">
              <div class="col-md-9 form-group">
                  <input type="text" class="form-control" name="vehicle_detail" id="vehicle_detail" placeholder="Enter customer’s Vehicle’s Engine No." value="">
              </div>
              <div class="col-md-3 form-group">
                  <button class="btn btn-secondary w-100" type="button" id="search_button">Search <i class="fa fa-arrow-circle-right"></i></button>                  
              </div>    
          </div>
      </div>
    </div>
    <div class="container">
      <div class="content">
        <h4 class="sectionhead mb-3"><span>Vehicle Info</span></h3>
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Engine Number</label>
              <input type="text" class="form-control" id="" placeholder="Engine number">
              <p id="" class="mb-0">
                <span class="mp-error-msg toparrow">Put error text here</span>
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Chassis  Number</label>
              <input type="text" class="form-control" id="" placeholder="Chassis number">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Manufacturer </label>
              <select class="form-control" id="">
                <option>Select Manufacturer</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Model</label>
              <input type="text" class="form-control" id="" placeholder="Model">
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group required mb-0">
              <label class="control-label">Registration No.</label>
            </div>
            <div class="required input-group">
              <div class="row">
                <div class="col-md-2">
                  <input type="text" class="form-control" id="" placeholder="MH">
                </div>
                <div class="col-md-2">
                  <input type="text" class="form-control" id="" placeholder="01.">
                </div>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="" placeholder="AB">
                </div>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="" placeholder="1234">
                </div>
              </div>
            </div>
          </div>
        </div>
        <h4 class="sectionhead mb-3"><span>Personal Info</span></h3>
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">First Name </label>
              <input type="text" class="form-control" id="" placeholder="First name">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Last Name</label>
              <input type="text" class="form-control" id="" placeholder="Last Name">
              <p id="" class="mb-0">
                <span class="mp-error-msg toparrow">Put error text here</span>
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Email</label>
              <input type="text" class="form-control" id="" placeholder="Email">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Mobile Number</label>
              <input type="text" class="form-control" id="" placeholder="Mobile Number">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Gender</label>
              <input type="text" class="form-control" id="" placeholder="Gender">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">DOB </label>
              <input type="text" class="form-control" id="" placeholder="DOB">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Address 1</label>
              <input type="text" class="form-control" id="" placeholder="Address 1">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Address 2</label>
              <input type="text" class="form-control" id="" placeholder="Address 1">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Pin</label>
              <input type="text" class="form-control" id="" placeholder="Pin">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">City</label>
              <input type="text" class="form-control" id="" placeholder="City">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">State</label>
              <input type="text" class="form-control" id="" placeholder="State">
            </div>
          </div>
        </div>          
        <h4 class="sectionhead mb-3"><span>Nominee Details</span></h3>
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Nominee Full Name</label>
              <input type="text" class="form-control" id="" placeholder="Nominee Full Name">
            </div>
          </div>          
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Relation </label>
              <select class="form-control" id="">
                <option value="">Select Relationship</option>
                <option value="father">Father</option>
                <option value="mother">Mother</option>
                <option value="brother">Brother</option>
                <option value="sister">Sister</option>
                <option value="spouse">Spouse</option>
                <option value="son">Son</option>
                <option value="daughter">Daughter</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group required">
              <label class="control-label">Age</label>
              <input type="text" class="form-control" id="" placeholder="Age">
            </div>
          </div>
        </div>
        <h4 class="sectionhead mb-3"><span>Plan Details</span></h3>
        <div class="row mb-3 plandetails-wrap">
          <div class="col-md-4">
            <div class="chooseplan">
              <input type="radio" id="control_01" name="select" value="1">
              <label for="control_01">
                <h5 class="mb-0"><i class="fa fa-square-o mr-2"></i> NEW RSA</h5>
              </label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="chooseplan">
              <input type="radio" id="control_02" name="select" value="2">
              <label for="control_02">
                <h5 class="mb-0"><i class="fa fa-square-o mr-2"></i> EXTENDED RSA</h5>
              </label>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-striped text-center">
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>Plan Name</th>
                    <th>RSA Tenure</th>
                    <th>RSA Covered Kms</th>
                    <th>PA Tenure</th>
                    <th>PA Sum Insured</th>
                    <th>PA RSD * </th>
                    <th>Policy Price *</th>
                    <th>Select Plan</th>
                  </tr>
                </thead>
                 <tbody>
                  <tr>
                    <th>1</th>
                    <td>Sapphire</td>
                    <td>2 Years</td>
                    <td>50</td>
                    <td>1 Year</td>
                    <td>15 lakh</td>
                    <td>Current</td>
                    <td>₹ 471</td>
                    <td>
                      <label class="radio-container">
                        <input type="radio" checked="checked" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>2</th>
                    <td>Platinum</td>
                    <td>1 Year</td>
                    <td>50</td>
                    <td>1 Year</td>
                    <td>15 lakh</td>
                    <td>Current</td>
                    <td>₹ 441</td>
                    <td>
                      <label class="radio-container">
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>3</th>
                    <td>Gold</td>
                    <td>1 Year</td>
                    <td>45</td>
                    <td>1 Year</td>
                    <td>10 lakh</td>
                    <td>Current</td>
                    <td>₹ 350</td>
                    <td>
                      <label class="radio-container">
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>4</th>
                    <td>Silver</td>
                    <td>1 Year</td>
                    <td>40</td>
                    <td>1 Year</td>
                    <td>5 lakh</td>
                    <td>Current</td>
                    <td>₹ 251</td>
                    <td>
                      <label class="radio-container">
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <h4 class="sectionhead mb-3"><span>Note:</span></h3>
        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>RSD:</strong> Risk Start Date.</p>
            <p><strong>Policy Price:</strong> Policy Price Inclusive GST.</p>
          </div>          
          <div class="col-md-6 text-right text-sm-center">
            <button type="button" class="btn btn-secondary btn-lg mb-3"><i class="fa fa-thumbs-up"></i> Generate Policy </button>
            <button type="button" class="btn btn-red btn-lg mb-3"><i class="fa fa-times"></i> Reset</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include("includes/footer.php"); ?>
  
</body>

</html>
