<?php
require __DIR__ . '/app/database.php';
require __DIR__ . '/app/Models/State.php';
require __DIR__ . '/app/Models/City.php';
require __DIR__ . '/app/Models/Address.php';

use App\Models\Address;
use App\Models\City;

// 1. Obtener todas las ciudades de la DB origen para crear el mapeo
$addresses = Address::with('city')->get();
$cityMap = [];
foreach ($addresses as $address) {
    if ($address->city) {
        $cityMap[$address->id] = (string)$address->city->name; // Mapeo de ID a nombre de ciudad
    }
}

// 2. Generar el script para Tinker
$tinkerScript = "<?php\n";
$tinkerScript .= "// Script generado automáticamente el " . date('Y-m-d H:i:s') . "\n";
$tinkerScript .= "\$cityMap = [\n";

foreach ($cityMap as $name => $id) {
     $tinkerScript .= "    $name => \"" . addslashes($id) . "\",\n"; // Escapar comillas
}

$tinkerScript .= "];\n\n";

$tinkerScript .= <<<'EOD'
// Obtener direcciones con city_id null y sus ciudades relacionadas (si existen)
$addresses = \App\Models\Address::with('city')->whereNull('city_id')->get();

foreach ($addresses as $address) {
    // Si la dirección tiene una ciudad relacionada (aunque city_id sea null)
    if ($address->city && isset($cityMap[$address->city->name])) {
        $address->city_id = $cityMap[$address->city->name];
        $address->save();
        echo "Actualizada dirección {$address->id} con ciudad ID {$address->city_id}\n";
    } else {
        echo "Dirección {$address->id} no tiene ciudad asignable\n";
    }
}
EOD;

// 3. Guardar en archivo
file_put_contents('update_city_ids.php', $tinkerScript);

echo "Script generado: update_city_ids.php";