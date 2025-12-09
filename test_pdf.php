<?php

require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

$parser = new Parser();
$pdf = $parser->parseFile('對帳單.pdf');
$text = $pdf->getText();

echo "Extracted text length: " . strlen($text) . "\n";
echo "First 1000 characters:\n";
echo substr($text, 0, 1000);
