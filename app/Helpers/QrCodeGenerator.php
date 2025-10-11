<?php

namespace App\Helpers;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeGenerator
{
    public function generate(string $content): string
    {
        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: $content,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10,
        );

        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
