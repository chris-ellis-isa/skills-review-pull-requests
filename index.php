<?php

$var = $_POST['var'];

$host = '127.0.0.1';
$db   = 'test';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$sql = "SELECT DISTINCT employees.empID,
                            employees.surname                                AS empSurname,
                            employees.given                                  AS empGiven,
                            CONCAT(employees.surname, ', ', employees.given) AS employee_name
            FROM   employees
                   INNER JOIN mpf_contracts
                           ON employees.empID = mpf_contracts.empID
            WHERE  employees.empID NOT IN (SELECT empID
                                           FROM   eofy_payroll_link
                                           WHERE  active = 1)
            ORDER  BY empSurname,
                      empGiven";

$stmt = $con->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
