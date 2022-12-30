<?php
class CurlConnection {
  private $handle;

  function __construct() {
    $this->handle = curl_multi_init();
  }

  function process($url, $data, $header, $post, $callback) {
    $connection = curl_init();
    curl_setopt($connection, CURLOPT_URL, $url);
    curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
    if ($post) {
      curl_setopt($connection, CURLOPT_POST, 1);
      array_push($header, "Content-Type:application/json");
      curl_setopt($connection, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
    curl_multi_add_handle($this->handle, $connection);
    do {
      $mrc = curl_multi_exec($this->handle, $active);
      if ($state = curl_multi_info_read($this->handle)) {
        $info = curl_getinfo($state['handle']);
        $callback(curl_multi_getcontent($state['handle']), $info);
        curl_multi_remove_handle($this->handle, $state['handle']);
      }
      usleep(10000);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM || $active);
  }

  function __destruct() {
    curl_multi_close($this->handle);
  }
}
?>
