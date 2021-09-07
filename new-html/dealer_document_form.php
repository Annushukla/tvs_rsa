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
      <h1>Upload / DEALER Documents</h1>
      <p class="lead">Upload the Dealer's Document <br>To Start Punching Policy.</p>
    </div>
  </header>

  <!-- Content -->
  <section>
    <div class="container">
      <div class="content mb-4">
        <h4 class="sectionhead mb-3"><span>Upload / DEALER Documents</span></h3>
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="form-group required">
              <label class="control-label">Agreement Pdf</label>
              <input type="file" class="form-control" id="">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group required">
              <label class="control-label">Pan Card</label>
              <input type="file" class="form-control" id="">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group required">
              <label class="control-label">GST Certificate</label>
              <input type="file" class="form-control" id="">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group required">
              <label class="control-label">Cancel Cheque</label>
              <input type="file" class="form-control" id="">
            </div>
          </div>
        </div>
        <div class="row">         
          <div class="col-md-12 text-right text-sm-center">
            <button type="button" class="btn btn-secondary btn-lg mb-3"><i class="fa fa-thumbs-up"></i> Upload Documents </button>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="content">
        <h4 class="sectionhead mb-3"><span>Uploaded / DEALER Documents</span></h3>
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Agreement Pdf</label>
              <div class="img-box"><a href="#">Click Here</a></div>
            </div>
          </div>          
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Pan Card</label>
              <div class="img-box"><a href="#">Click Here</a></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">GST Certificate</label>
              <div class="img-box"><a href="#">Click Here</a></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Cancel Cheque</label>
              <div class="img-box"><a href="#">Click Here</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include("includes/footer.php"); ?>
  
</body>

</html>
