<?php

namespace App\Support\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Secure MIME type validation rule.
 *
 * Validates both the file extension AND the actual binary MIME type
 * to prevent file upload attacks where malicious files are renamed.
 *
 * Usage:
 *   'file' => ['required', 'file', new SecureMimeType(['application/pdf'])]
 */
class SecureMimeType implements ValidationRule
{
    protected array $allowedMimeTypes;

    protected string $failureMessage = 'The :attribute must be a valid file type.';

    /**
     * Create a new rule instance.
     *
     * @param  array  $allowedMimeTypes  Array of allowed MIME types (e.g., ['application/pdf', 'image/jpeg'])
     */
    public function __construct(array $allowedMimeTypes)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('The :attribute must be a file.');

            return;
        }

        // Check if file upload was successful
        if (! $value->isValid()) {
            $fail('The :attribute upload failed.');

            return;
        }

        // Get client-reported MIME type
        $clientMimeType = $value->getMimeType();

        // Get actual binary MIME type using finfo
        $actualMimeType = $this->getActualMimeType($value->getRealPath());

        // Validate client-reported MIME type
        if (! in_array($clientMimeType, $this->allowedMimeTypes)) {
            $this->failureMessage = "The :attribute type ({$clientMimeType}) is not allowed.";
            $fail($this->failureMessage);

            return;
        }

        // Validate actual binary MIME type
        if (! in_array($actualMimeType, $this->allowedMimeTypes)) {
            $this->failureMessage = "The :attribute file content does not match the allowed type. Detected: {$actualMimeType}";
            $fail($this->failureMessage);

            return;
        }

        // Extra check: ensure client and actual MIME types match
        if ($clientMimeType !== $actualMimeType) {
            $this->failureMessage = 'The :attribute file type mismatch detected. This may be a security risk.';
            $fail($this->failureMessage);

            return;
        }
    }

    /**
     * Get the actual MIME type from file binary content.
     */
    protected function getActualMimeType(string $filePath): string|false
    {
        if (! file_exists($filePath)) {
            return false;
        }

        // Use finfo to detect MIME type from binary content
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return false;
        }

        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType;
    }

    /**
     * Set custom failure message.
     *
     * @return $this
     */
    public function message(string $message): self
    {
        $this->failureMessage = $message;

        return $this;
    }
}
