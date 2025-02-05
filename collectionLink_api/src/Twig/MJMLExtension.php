<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MJMLExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('mjml', [$this, 'convertMjmlToHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function convertMjmlToHtml(string $mjml): string
    {
        $process = proc_open(
            ['mjml', '-i'],
            [
                ['pipe', 'r'], // stdin
                ['pipe', 'w'], // stdout
                ['pipe', 'w'], // stderr
            ],
            $pipes
        );

        if (!is_resource($process)) {
            throw new \RuntimeException('Unable to execute MJML');
        }

        fwrite($pipes[0], $mjml);
        fclose($pipes[0]);

        $html = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($process);

        if (!empty($error)) {
            throw new \RuntimeException('MJML error: ' . $error);
        }

        return $html;
    }
}
