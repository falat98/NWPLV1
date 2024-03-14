<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    // Ukljucivanje datoteke DataManager.php
    include("DataManager.php");

    // Postavljanje URL-a
    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/";

    // Stvaranje instance klase DataManager s URL-om kao argumentom konstruktora
    $dm = new DataManager($url);

    // Pozivanje metode fetchData koja dohvaca podatke
    $dm->fetchData();

    // Ispisivanje podataka dobivenih preko metode read u obliku JSON-a
    echo $dm->getDiplomskiRadovi()->read();

    // Pozivanje metode finish koja zavrsava rad s podacima
    $dm->getDiplomskiRadovi()->finish();
    ?>
</body>
</html>
