<?php

use cookbook\pdfpreview\PdfPreview;
use Kirby\Cms\App as Kirby;
use Kirby\Cms\File;
use Kirby\Filesystem\F;
use Kirby\Exception\Exception;

load([
    'cookbook\\pdfpreview\\pdfpreview' => 'classes/PdfPreview.php'
], __DIR__);

Kirby::plugin('cookbook/pdfpreview', [
    'hooks' => [
        'file.create:after' => function ($file) {
            // only create preview for PDF files
            if ($file->mime() === 'application/pdf') {
                $options    = [
                    'quality'    => 100,
                    'page'       => 0,
                    'density'    => 300,
                ];
                $darkroom = new PdfPreview();
                $darkroom->process($file->root(), $options);
            }
        }
    ],
    'fileMethods' => []
]);