
<?php

$json = file_get_contents(__DIR__ . '/lista_de_ecopontos_teste.json');

if ($json === false) {
    die("File could not be read.\n");
}

echo "File size: " . strlen($json) . " bytes\n";
echo "Valid UTF-8: ";
var_dump(preg_match('//u', $json) === 1);

try {
    $data = json_decode(
        $json,
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    echo "JSON decoded successfully.\n";
    echo "Data type: " . gettype($data) . "\n";
} catch (JsonException $e) {
    echo "Detailed error: " . $e->getMessage() . "\n";
}
