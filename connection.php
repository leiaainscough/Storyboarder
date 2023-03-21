<?php
  //Initializes MySQLi
  $conn = mysqli_init();
  
  mysqli_ssl_set($conn,NULL,NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
  
  // Establish the connection
  mysqli_real_connect($conn, 'storyboard-serv.mysql.database.azure.com', 'storyboard_admin', 'Password2', 'storyboard-db', 3306, NULL, MYSQLI_CLIENT_SSL);
  
  //If connection failed, show the error
  if (mysqli_connect_errno())
  {
      die('Failed to connect to MySQL: '.mysqli_connect_error());
  }

?>