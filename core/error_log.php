<?php
  Class ErrorLog {

    public static function Exception( $error ) {

      $error = $error. " " .date('m/d/Y h:i:s a', time()). PHP_EOL;
      $file_path = ROOTPATH . '/tmp/logs/error_log.txt';
      $handle = fopen($file_path, 'a') or die("Hit sudo chmod 666 " .$file_path. " command on the terminal to get the error logs.");
      fwrite($handle, $error); 
      fclose($handle);

    }
  }
?>
