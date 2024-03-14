<?php

namespace cookbook\pdfPreview;

use Exception;
use Kirby\Image\Darkroom\ImageMagick;

class PdfPreview extends ImageMagick
{
    public static array $types = [];

    /**
     * Set the colorspace
     *
     * @param string $file
     * @param array $options
     * @return string
     */
    protected function colorspace(string $file, array $options)
    {
        if ($options['colorspace'] !== false) {
            return '-colorspace ' . $options['colorspace'];
        }
    }

    /**
     * Creates the convert command with the right path to the binary file
     *
     * @param string $file
     * @param array $options
     * @return string
     */
    protected function convert(string $file, array $options): string
    {
        $file = isset($options['page']) ? sprintf('%s[%s]', $file, $options['page']) : $file;
        return sprintf($options['bin'] . $this->density($file, $options) . ' "%s"', $file);
    }

    /**
     * Returns additional default parameters for imagemagick
     *
     * @return array
     */
    protected function defaults(): array
    {
        return [
            'bin'        => kirby()->option('thumbs.bin'),
            'colorspace' => false,
            'density'    => 150,
            'flatten'    => true,
            'format'     => 'jpg',
            'quality'    => 90,
            'grayscale'  => false,
            'resize'     => false,
        ];
    }

    /**
     * Set density
     * Note: the density option must be set before the filename as it determines at what resolution the PDF is rendered
     *
     * @param string $file
     * @param array $options
     * @return string
     */
    protected function density(string $file, array $options)
    {
        if ($options['density'] !== false) {
            return ' -density ' . $options['density'];
        }
    }

    /**
     * Flatten output
     *
     * @param string $file
     * @param array $options
     * @return string
     */
    protected function flatten(string $file, array $options)
    {
        if ($options['flatten'] !== false) {
            return ' -flatten ';
        }
    }

    protected function options(array $options = []): array{
        return array_merge($this->settings, $options);
    }

    /**
     * Creates and runs the full imagemagick command
     * to process the image
     *
     * @param string $file
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function process(string $file, array $options = []): array
    {
        $options   = $this->options($options);
        $command   = [];
        $command[] = $this->convert($file, $options);
        $command[] = $this->colorspace($file, $options);
        $command[] = $this->quality($file, $options);
        $command[] = $this->flatten($file, $options);
        $command[] = $this->save($file, $options);

        // remove all null values and join the parts
        $command = implode(' ', array_filter($command));

        // try to execute the command
        exec($command . ' 2>&1', $output, $return);

        // log broken commands
        if ($return !== 0) {
            $error = implode("\n", $output);
            throw new Exception('The imagemagick convert command could not be executed: ' . $command . "\nError: " . $error);
        }

        return $options;
    }
}