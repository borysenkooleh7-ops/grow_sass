<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem;

class WindowsFilesystem extends Filesystem
{
    /**
     * Write the contents of a file, replacing it atomically if it already exists.
     *
     * This method handles Windows file locking issues by implementing retry logic.
     *
     * @param  string  $path
     * @param  string  $content
     * @param  int|null  $mode
     * @return void
     */
    public function replace($path, $content, $mode = null)
    {
        // If the path already exists, we will try to delete it first
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        if (! is_null($mode)) {
            chmod($tempPath, $mode);
        } else {
            chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        // Implement retry logic for Windows file locking issues
        $maxAttempts = 5;
        $attempt = 0;
        $renamed = false;

        while ($attempt < $maxAttempts && !$renamed) {
            try {
                // On Windows, if the target file exists and is locked, we might need to wait
                if (file_exists($path)) {
                    // Try to ensure any file handles are released
                    clearstatcache(true, $path);

                    // Small delay to allow file locks to be released
                    if ($attempt > 0) {
                        usleep(100000 * $attempt); // 100ms, 200ms, 300ms, etc.
                    }
                }

                $renamed = @rename($tempPath, $path);

                if (!$renamed) {
                    // If rename failed, try an alternative approach for Windows
                    if (DIRECTORY_SEPARATOR === '\\' && file_exists($path)) {
                        // Try to delete the target file first
                        @unlink($path);
                        clearstatcache(true, $path);
                        usleep(50000); // 50ms delay
                        $renamed = @rename($tempPath, $path);
                    }
                }

                if ($renamed) {
                    break;
                }
            } catch (\Exception $e) {
                // Continue to next attempt
            }

            $attempt++;
        }

        // If all attempts failed, fall back to direct rename and let it throw an exception
        if (!$renamed) {
            // Clean up temp file before throwing
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }

            // One final attempt without suppressing errors
            $tempPath = tempnam(dirname($path), basename($path));
            chmod($tempPath, is_null($mode) ? (0777 - umask()) : $mode);
            file_put_contents($tempPath, $content);
            rename($tempPath, $path);
        }
    }
}
