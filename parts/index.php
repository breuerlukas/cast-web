<?php
  $page = "home";
  if(isset($_GET["page"])) {
    $page = $_GET["page"];
  }
  $hasSubPage = str_contains($page, "/");
  if (!$hasSubPage) {
    $pageName = $page;
    $pageFile = $page;
    $pagePath = array(0 => $page);
  } else {
    $pagePath = explode("/", $page);
    array_pop($pagePath);
    $pathSize = count($pagePath);
    $pageName = $pagePath[$pathSize - 1];
    if ($pathSize >= 2) {
      array_pop($pagePath);
    }
    array_push($pagePath, $pagePath[count($pagePath) - 1]);
    $pageFile = implode("/", $pagePath);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@400;700&display=swap" rel="stylesheet">
  <link rel=icon type="image/png" href="img/logo.png" sizes="32x32">
  <link rel="stylesheet" href="../css/bootstrap.css" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/<?php echo $pagePath[count($pagePath) - 1];?>.css">
  <?php
    if ($hasSubPage) {
      echo '<link rel="stylesheet" href="../../css/'. $pageName . '.css">';
    }
  ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="js/cookie.js"></script>
  <script src="js/httprequest.js"></script>
  <script src="js/dom.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/4.1.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <title>Cast - <?php echo ucfirst($pageName);?></title>
</head>
<body>
<?php
  include("header.php");
  if(file_exists("parts/$pageFile.php")) {
    include("parts/$pageFile.php");
  } else {
    echo "Die Seite konnte nicht gefunden werden.";
  }
  include("footer.php");
?>
</body>
</html>

<script>
  var expirationTime = Cookie.find("tokenExpiration");
  setTimeout(function(){
    location.reload();
  }, expirationTime - +new Date());
</script>