<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Anton" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39+Text|Open+Sans|Rozha+One" rel="stylesheet">
  <title>Bauhaus Tutorials</title>
</head>
<body>

  <style>
	  .navbar-light .navbar-nav .active>.nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.open, .navbar-light .navbar-nav .open>.nav-link {
		  color: whitesmoke;}
	.navbar-light .navbar-nav .nav-link {
		color: whitesmoke;}

.bg-faded {
    background-color: #8B0000;}
   
    h1 {font-family: 'Rozha One', serif;
      padding-top: 1.5em;
        padding-bottom: 1.5em;}
.space { padding-left: 70px;}
	
 
  </style>


<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#"><img src="../images/logo.png" style="width:100px;height:70px;" alt="sample image"></a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../index.html">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.html">About </a>
      </li>
    
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<?php

if ( isset( $_POST["sendPhoto"] ) ) {
  processForm();
} else {
  displayForm();
}

function processForm() {
  if ( isset( $_FILES["photo"] ) and $_FILES["photo"]["error"] == UPLOAD_ERR_OK ) {
    if ( $_FILES["photo"]["type"] != "image/jpeg" ) {
      echo "<p>JPEG photos only, thanks!</p>";
		//create an empty folder so the uploaded files can go there 
    } elseif ( !move_uploaded_file( $_FILES["photo"]["tmp_name"], "photos/" . basename( $_FILES["photo"]["name"] ) ) ) {
      echo "<p>Sorry, there was a problem uploading that photo.</p>" . $_FILES["photo"]["error"] ;
    } else {
      displayThanks();
    }
  } else {
    switch( $_FILES["photo"]["error"] ) {
      case UPLOAD_ERR_INI_SIZE:
        $message = "The photo is larger than the server allows.";
        break;
      case UPLOAD_ERR_FORM_SIZE:
        $message = "The photo is larger than the script allows.";
        break;
      case UPLOAD_ERR_NO_FILE:
        $message = "No file was uploaded. Make sure you choose a file to upload.";
        break;
      default:
        $message = "Please contact your server administrator for help.";
    }
    echo "<p>Sorry, there was a problem uploading that photo. $message</p>";
  }
}

function displayForm() {
?>
    <h1>Uploading a Photo</h1>

    <p>Please enter your name and choose a photo to upload, then click Send Photo.</p>

    <form action="photo_upload.php" method="post" enctype="multipart/form-data" class="form">
      <div style="width: 30em;">
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />

        <label for="visitorName">Your name</label>
        <input type="text" name="visitorName" id="visitorName" value="" />

        <label for="photo">Your photo</label>
        <input type="file" name="photo" id="photo" value="" />

        <div style="clear: both;">
          <input type="submit" name="sendPhoto" value="Send Photo" />
        </div>

      </div>
    </form>
<?php
}

function displayThanks() {
	$myImage = imagecreatefromjpeg( 'photos/'.$_FILES["photo"]["name"] );
$myLogo = imagecreatefrompng( "logo.png" );
$myLogo = imagescale($myLogo, 48, 48);
$myColor = imagecolorallocate( $myImage, 255, 255, 255 );

$destWidth = imagesx( $myImage );
$destHeight = imagesy( $myImage );
$srcWidth = imagesx( $myLogo );
$srcHeight = imagesy( $myLogo );

// next 7 lines to crop the image
// are from  http://php.net/manual/en/function.imagecrop.php



// add white rectangles as borders make it look like polaroid (tm)
imagefilledrectangle( $myImage, 0, 0, $destWidth, 30, $myColor );
imagefilledrectangle( $myImage, 0, 0, 30, $destHeight, $myColor );
imagefilledrectangle( $myImage, $destWidth-30, 0, $destWidth, $destHeight, $myColor );

$destX = 0;
$destY = ($destHeight - $srcHeight);


$white = imagecolorexact( $myLogo, 255, 255, 255 );
//imagecolortransparent( $myLogo, $white );


imagefilledrectangle( $myImage, 0, $destY-$srcHeight, $destWidth, $destHeight, $myColor );


imagecopymerge( $myImage, $myLogo, $destX+10, $destY-1, 0, 0, $srcWidth, $srcHeight, 79 );

$black = imagecolorallocate( $myImage, 0, 0, 0 );
imagestring( $myImage, 4 , 285, $destY+0.5*$srcHeight, "image (c) B Tutorials", $black);


// (it could have been saved on the server instead)
imagejpeg( $myImage, 'changed/'.$_FILES["photo"]["name"] );

// Cleaning up
imagedestroy( $myImage );
imagedestroy( $myLogo );


?>
    <h1>Thank You</h1>
    <p>Thanks for uploading your photo<?php if ( $_POST["visitorName"] ) echo ", " . $_POST["visitorName"] ?>!</p>
    <p>Here's your photo:</p>
    <p><img src="changed/<?php echo $_FILES["photo"]["name"] ?>" alt="Photo" /></p>
<?php
}
?>
<script src="js/jquery.slim.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
		  <!--</section>-->
  </body>
</html>
