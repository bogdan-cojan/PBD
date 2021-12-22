<?php

try{
  $pdo = new PDO('sqlite:database.db');

  $statement = $pdo->query("SELECT * FROM client");

  $service = "service";
  $service_auto = $pdo->query("SELECT * FROM $service");

}catch(PDOException $e){
  echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
  <header>
    <title>Parc Auto</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  </header>
  <head>
    <div class="header-data"> 
      <h2>Clienti</h2>
      <form action="/index.php">
        <input class="button" type="submit" name="refresh-btn" value="Refresh Tabele"/>
      </form>
    </div>
  </head>
  <body>
    <table style="width: 100%">
      <tr>
        <th>Sofer</th>
        <th>Numar</th>
        <th>Marca</th>
        <th>An fabricatie</th>
      </tr>
        <?php
          foreach($statement as $row){
              print "<tr>
                <td>"
                .$row['sofer'].
                "</td>";
                print "<td>"
                .$row['numar'].
                "</td>";
                print "<td>"
                .$row['marca'].
                "</td>";
                print "<td>"
                .$row['an_fabricatie'].
                "</td></tr>";
          }
        ?>
    </table>
          <br />
          <h2>Service</h2>
          <br/>
    <table style="width: 100%">
      <tr>
        <th>Sofer</th>
        <th>Numar</th>
        <th>Carburant</th>
        <th>Data Alimentarii</th>
        <th>Kilometraj</th>
      </tr>
        <?php
          foreach($service_auto as $row){
              print "<tr>
                <td>"
                .$row['sofer'].
                "</td>";
                print "<td>"
                .$row['numar'].
                "</td>";
                print "<td>"
                .$row['carburant'].
                "</td>";
                print "<td>"
                .$row['data_alimentarii'].
                "</td>";
                print "<td>"
                .$row['kilometraj'].
                "</td></tr>";
          }
        ?>
    </table>

    <form method="get">
    <div class="btn-group">
      <input class="button" type="submit" name="raport-btn" value="Raport"/>
      <input class="button" type="submit" name="raport-detaliat-btn" value="Raport Detaliat"/>
      <input class="button" type="submit" name="procedura" value="Alimentare Masina"/>
      <input class="button" type="submit" name="consum-mediu" value="Consum mediu"/>
      <input class="button" type="submit" name="top-alimentari" value="Top Alimentari"/>
      <input class="button" type="submit" name="top-consum" value="Top Consum"/>
    </div>
    </form>

    <?php
      if(isset($_GET['raport-btn'])){
        $raport = $pdo->query("SELECT service.numar , client.an_fabricatie , AVG(service.carburant) AS consum_mediu
        FROM client , service
        WHERE client.numar=service.numar
        GROUP BY service.numar , client.an_fabricatie
        ORDER BY service.numar;");

        print "<table style='width: 100%'><tr><th>Numar</th><th>An fabricatie</th><th>Consum mediu</th></tr>";
        foreach($raport as $row){
          print "<tr>
          <td>"
          .$row['numar'].
          "</td>";
          print "<td>"
        .$row['an_fabricatie'].
          "</td>";
          print "<td>"
          .$row['consum_mediu'].
          "</td></tr>";
          }
          print "</table>";
      }

      if(isset($_GET['raport-detaliat-btn'])){
        $raport_detaliat = $pdo->query("SELECT  a.numar , an_fabricatie , data_alimentarii , a.sofer , SUM(carburant)*100/SUM(kilometraj) AS consum
        FROM client a , service b
        WHERE a.numar=b.numar
        GROUP BY a.numar , an_fabricatie , data_alimentarii , a.sofer
        ORDER BY  a.numar;");

        print "<table style='width: 100%'><tr><th>Numar</th><th>An fabricatie</th><th>Data alimentarii</th><th>Sofer</th><th>Consum</th></tr>";
        foreach($raport_detaliat as $row){
          print "<tr>
          <td>"
          .$row['numar'].
          "</td>";
          print "<td>"
        .$row['an_fabricatie'].
          "</td>";
          print "<td>"
          .$row['data_alimentarii'].
          "</td>";
          print "<td>"
          .$row['sofer'].
          "</td>";
          print "<td>"
          .$row['consum'].
          "</td></tr>";
          }
          print "</table>";
        }

        if(isset($_GET['procedura'])){
          print "<div style='display:flex; flex-direction:column; align-items:center'>
          <h3>Alimentarea unei masini</h3>
          <div class='container' style='width:50%'>
            <form action='/index.php'>
              <label for='numar'>Numar</label>
              <input type='text' id='numar' name='Numar' placeholder='Numar...'>
          
              <label for='carburant'>Carburant</label>
              <input type='number' id='carburant' name='Carburant' placeholder='Carburant...' min='0'>
          
              <label for='data'>Data</label>
              <input type='date' id='data' name='Data' placeholder='Data...'>

              <label for='kilometraj'>Kilometraj</label>
              <input type='number' id='kilometraj' name='Kilometraj' placeholder='Kilometraj...' min='0'>

              <label for='sofer'>Sofer</label>
              <input type='text' id='sofer' name='Sofer' placeholder='Sofer...'>

              <input type='submit' value='Submit'>
            </form>
          </div>
          </div>";
        }

        if (isset($_GET['Numar']) && isset($_GET['Carburant']) && isset($_GET['Data']) && isset($_GET['Kilometraj']) && isset($_GET['Sofer'])){
          $x = $pdo->query("SELECT COUNT(sofer) FROM client WHERE client.sofer='".$_GET["Sofer"]."'");
          $y = $pdo->query("SELECT COUNT(numar) FROM client WHERE client.numar='".$_GET["Numar"]."'");
          if(strlen($_GET["Numar"]) != 7){
            $z = 0;
          }else{
            $z = 1;
          }
          if($x==0 || $y==0 || $z==0){
            if($x==0){
              print "<br/><center><b>Nu se poate alimenta, clientul nu este in baza de date !</b></center><br/>";
            }
            if($y==0){
              print "<br/><center><b>Nu se poate alimenta, masina nu este in baza de date !</b></center><br/>";
            }
            if($z==0){
              print "<br/><center><b>Nu se poate alimenta, nr. de inmatriculare nu este valid !</b></center><br/>";
            }
          }else{
            if($_GET["Carburant"] <= 60){

              $a = $pdo->query("SELECT COUNT(numar) as count_numar FROM service WHERE numar='".$_GET['Numar']."'");
              $a = $a->fetch(PDO::FETCH_ASSOC);
              $a = $a['count_numar'];
              $b = $pdo->query("SELECT SUM(carburant) as suma_carburant FROM service WHERE numar='".$_GET['Numar']."'");
              $b = $b->fetch(PDO::FETCH_ASSOC);
              $b = $b['suma_carburant'];
              $c = $pdo->query("SELECT SUM(kilometraj) as suma_kilometraj FROM service WHERE numar='".$_GET['Numar']."'");
              $c = $c->fetch(PDO::FETCH_ASSOC);
              $c = $c['suma_kilometraj'];

              if(3*((int)$_GET['Carburant']+$b)*100/(($c+(int)$_GET['Kilometraj'])*($a+1)) < $b*100/($c*$a)){
                print "<center><b>NU S-A ALIMENTAT , CONSUM MEDIU PREA MIC</b></center>";
              }else{
                $pdo->exec("INSERT INTO $service(sofer, numar, carburant, data_alimentarii, kilometraj) VALUES('".$_GET['Sofer']."', '".$_GET['Numar']."', '".$_GET['Carburant']."', '".$_GET['Data']."', '".$_GET['Kilometraj']."')");
              }
            }else{
              print "<br/><center><b>Depasire capacitate rezervor !</b></center><br/>";
            }
          }
        }

        if(isset($_GET['consum-mediu'])){
          print "<div style='display:flex; flex-direction:column; align-items:center'>
          <h3>Consum Mediu</h3>
          <div class='container' style='width:50%'>
            <form action='/index.php'>
              <label for='sofer'>Sofer</label>
              <input type='text' id='sofer' name='Sofer' placeholder='Sofer...'>

              <input type='submit' value='Submit'>
            </form>
          </div>
          </div>";
        }

        if(isset($_GET['Sofer']) && !isset($_GET['Numar']) && !isset($_GET['Carburant']) && !isset($_GET['Data']) && !isset($_GET['Kilometraj'])){
          $query = $pdo->query("SELECT COUNT(sofer) as nrsofer FROM $service WHERE $service.sofer='".$_GET["Sofer"]."'");
          $x = $query->fetch(PDO::FETCH_ASSOC);
          $x = $x['nrsofer'];
          $query = $pdo->query("SELECT SUM(carburant) as carb FROM $service WHERE $service.sofer='".$_GET["Sofer"]."'");
          $y = $query->fetch(PDO::FETCH_ASSOC);
          $y = $y['carb'];
          $query = $pdo->query("SELECT SUM(kilometraj) as km FROM $service WHERE $service.sofer='".$_GET["Sofer"]."'");
          $z = $query->fetch(PDO::FETCH_ASSOC);
          $z = $z['km'];

          if($x!=0){
            $consum=($y*100)/($z*$x);
            print "<br/><center><table style='width: 50%'><tr><th>Consum mediu</th>
            </tr><tr><td>".$consum."</td></tr></table></center><br/>";
          }else{
            print "<br/><center><b>CLIENTUL NU ESTE IN BAZA DE DATE !</b></center><br/>";
          }
        }

        if(isset($_GET['top-alimentari'])){
          
            $top_masini = $pdo->query("SELECT service.numar , client.an_fabricatie , marca , service.carburant, service.data_alimentarii, COUNT(service.numar) as numar_service
            FROM client , service
            WHERE client.numar=service.numar
            GROUP BY service.numar , client.an_fabricatie
            ORDER BY numar_service DESC");

// HAVING round((julianday(Date(MAX(data_alimentarii))) - julianday(Date(MIN(data_alimentarii))))/30)<=24 

          $i=0;
          print "<br/><center><b>Top 3 masini cu cele mai multe alimentari in 2 ani consecutivi</b></center><br/>";
          print "<center><table style='width: 50%'><tr><th>Numar</th><th>An fabricatie</th><th>Marca</th><th>Carburant</th><th>Data alimentarii</th></tr>";
          while($row = $top_masini->fetch(PDO::FETCH_ASSOC)){
            $i++;
            
            print "<tr>
            <td>"
            .$row['numar'].
            "</td>";
            print "<td>"
          .$row['an_fabricatie'].
            "</td>";
            print "<td>"
            .$row['marca'].
            "</td>";
            print "<td>"
            .$row['carburant'].
            "</td>";
            print "<td>"
            .$row['data_alimentarii'].
            "</td></tr>";
            if($i % 3 == 0){
              print "</table></center>";
              break;
            }
            }
          }

          if(isset($_GET['top-consum'])){
            $top_consum = $pdo->query("SELECT  a.sofer , a.numar , SUM(carburant)*100/SUM(kilometraj) AS consum,  SUM(kilometraj) AS pondereKM
            FROM client a , service b
            WHERE a.sofer=b.sofer
            GROUP BY a.sofer
            ORDER BY consum DESC");

            $i=0;
            print "<br/><center><b>Sofer cu cel mai mare consum</b></center><br/>";
            print "<center><table style='width: 50%'><tr><th>Sofer</th><th>Numar</th><th>Consum</th><th>Pondere Kilometraj</th></tr>";
            while($row = $top_consum->fetch(PDO::FETCH_ASSOC)){
              $i++;
              
              print "<tr>
              <td>"
              .$row['sofer'].
              "</td>";
              print "<td>"
            .$row['numar'].
              "</td>";
              print "<td>"
              .$row['consum'].
              "</td>";
              print "<td>"
              .$row['pondereKM'].
              "</td></tr>";
              if($i % 1 == 0){
                print "</table></center>";
                break;
              }
          }
        }
      ?>
  </body>
</html>