<?php
include('classes/XMLObject.php');
?><html>

  <head>
   <title>Database Test</title>
  </head>

  <body bgcolor="white">

<?php
  $dbParameters = new XMLObject();
  $dbParameters->loadXMLFromFile($_SERVER['DOCUMENT_ROOT']."/config/". $_SERVER["SERVER_NAME"] ."_database.xml");
?>
  <table border="1">
  <tr>
   <th>host</th>
   <th>dbname</th>
   <th>port</th>
   <th>user</th>
   <th>password</th>
  </tr>
   <th><?php echo $dbParameters->host;?></th>
   <th><?php echo $dbParameters->name;?></th>
   <th><?php echo $dbParameters->port;?></th>
   <th><?php echo $dbParameters->user;?></th>
   <th><?php echo $dbParameters->password;?></th>
  </tr>
  </table>
<?php
  $link = pg_Connect(
      "host=".$dbParameters->host.
      " dbname=".$dbParameters->name.
      " port=".$dbParameters->port.
      " user=".$dbParameters->user.
      " password=".$dbParameters->password .
      " connect_timeout=15000"
      );

  $result = pg_exec($link, "select * from usuarios");
  $numrows = pg_numrows($result);
  echo "<p>link = $link<br>
  result = $result<br>
  numrows = $numrows</p>
  ";
  ?>
  <table border="1">
  <tr>
   <th>ID</th>
   <th>CPF</th>
   <th>NOME</th>
  </tr>
  <?

   // Loop on rows in the result set.

   for($ri = 0; $ri < $numrows; $ri++) {
    echo "<tr>\n";
    $row = pg_fetch_array($result, $ri);
    echo " <td>", $row["id_usuario"], "</td>
   <td>", $row["cpf"], "</td>
   <td>", $row["nome"], "</td>
  </tr>
  ";
   }
   pg_close($link);
  ?>
  </table>

  </body>

  </html> 