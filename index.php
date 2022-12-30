<?php
  include("curlconnection.php");
  if(!isset($_COOKIE["token"]) || !isset($_COOKIE["tokenExpiration"])) {
    setcookie("token", "", time() - 3600, "/");
    setcookie("tokenExpiration", "", time() - 3600, "/");
    include("verification/login.php");
    return;
  }
  $connection = new CurlConnection();
  $callback = function($data, $info) {
    $response = json_decode($data);
      $isValidKey = "isValid";
      if (strcmp($response->$isValidKey, "true")) {
        unset($_COOKIE["token"]);
        setcookie("token", "", time() - 3600, "/");
        setcookie("tokenExpiration", "", time() - 3600, "/");
        header("Refresh:0");
        return;
      }
      include("parts/index.php");
  };
  $connection->process("http://lukasbreuer.de:8080/verification/isValid", json_encode(array("token" => $_COOKIE["token"])), array(), true, $callback);
?>