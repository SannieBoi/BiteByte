<?php
// 1. Definisanje parametara baze
$host    = 'localhost';
$db      = 'bitebyte';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4'; // Omogućava ispravan prikaz svih karaktera (š, č, ć, itd.)

// 2. Kreiranje DSN (Data Source Name) stringa
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. Dodatne opcije za PDO konekciju
$options = [
    // Baca Exception ako se desi SQL greška (važno za debugging)
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    
    // Automatski pretvara rezultate u asocijativni niz (npr. $sat['model'])
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    
    // Isključuje emulaciju pripremljenih upita (sigurnije protiv SQL injection-a)
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 4. Uspostavljanje veze
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (PDOException $e) {
    // Ako veza ne uspe, ispiši grešku i prekini rad
    die("Greška pri povezivanju sa bazom: " . $e->getMessage());
}