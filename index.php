<?php
require __DIR__ . '/app/database.php';
require __DIR__ . '/app/Models/State.php';
require __DIR__ . '/app/Models/City.php';
require __DIR__ . '/app/Models/Address.php';

use App\Models\Address;
use App\Models\State;
use App\Models\City;

$addresses = Address::all();
foreach ($addresses as $address) {
    echo "Direccion {$address->id}: {$address->address_line}. Ciudad: {$address->city->name} <br>";
}