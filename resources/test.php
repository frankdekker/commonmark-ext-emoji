<?php
declare(strict_types=1);

// convert json to array

$data = json_decode(file_get_contents('./full.json'), true, 512, JSON_THROW_ON_ERROR);

$result = var_export($data, true);
file_put_contents('./full.php', $result);

