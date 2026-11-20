<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Lets a migration pin a mode by writing an env var to the project's `.env.local`. The using
 * class provides the {@see Filesystem} as `$filesystem` and the project root as `$projectDir`.
 */
trait WritesEnvLocalTrait
{
    /**
     * Sets `$strVar=$strValue` in `.env.local`, replacing an existing assignment (any value,
     * optionally `export`ed) or appending one, and returns the file path.
     *
     * The in-process env is populated too, so a subsequent shouldRun() call inside the same
     * `contao:migrate` invocation observes the new value. Without it, Symfony's Dotenv only
     * re-reads on the next PHP process start and the migration loop would keep firing.
     */
    private function writeEnvLocal(string $strVar, string $strValue): string
    {
        $envFile = $this->projectDir . '/.env.local';
        $newLine = $strVar . '=' . $strValue;

        $content = '';

        if ($this->filesystem->exists($envFile)) {
            $content = @file_get_contents($envFile);

            // Writing back an unread file would erase every other setting in it.
            if (false === $content) {
                throw new \RuntimeException(sprintf('Could not read "%s", so "%s" was not written. Set it manually.', $envFile, $newLine));
            }
        }

        $pattern = '/^[ \t]*(export[ \t]+)?' . preg_quote($strVar, '/') . '=.*$/m';

        if (preg_match($pattern, $content) === 1) {
            $content = (string) preg_replace($pattern, $newLine, $content, 1);
        } else {
            if ($content !== '' && !str_ends_with($content, "\n")) {
                $content .= "\n";
            }
            $content .= $newLine . "\n";
        }

        $this->filesystem->dumpFile($envFile, $content);

        $_ENV[$strVar] = $strValue;

        return $envFile;
    }
}
