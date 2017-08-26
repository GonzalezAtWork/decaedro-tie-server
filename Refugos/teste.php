<html>

  <head>
   <title>Test</title>
  </head>

  <body bgcolor="white">

  <?
  $link = pg_Connect("host=localhost dbname=zelaznog_kalitera user=zelaznog password=54NC7U5666");
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