<?php
require __DIR__.'/vendor/autoload.php';

if (class_exists(App\Models\Supplier::class)) {
    echo "SUCCESS: Class App\Models\Supplier found!";
} else {
    echo "FAILURE: Class App\Models\Supplier NOT found.";
}
