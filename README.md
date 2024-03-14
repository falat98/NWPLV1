Za pokretanje zadatka potrebno je imati instaliran XAMPP u kojem su pokrenuti Apache i MySQL.
Kreiranje baze podataka odradio sam na phpmyadmin sucelju koristeci slijedece naredbe
CREATE TABLE diplomski_radovi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    text TEXT,
    link VARCHAR(255),
    oib VARCHAR(11)
);
