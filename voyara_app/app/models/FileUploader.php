<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class FileUploader {
    private string $error = '';

    public function upload(array $file, string $destDir, array $allowedTypes = [], int $maxBytes = 0): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = $this->uploadErrorMsg($file['error']);
            return null;
        }

        $maxBytes = $maxBytes ?: MAX_FILE_SIZE;
        if ($file['size'] > $maxBytes) {
            $this->error = 'File too large. Max ' . ($maxBytes / 1048576) . ' MB.';
            return null;
        }

        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowed  = $allowedTypes ?: ALLOWED_IMG_TYPES;

        if (!in_array($mimeType, $allowed, true)) {
            $this->error = 'Invalid file type.';
            return null;
        }

        $ext      = $this->extFromMime($mimeType);
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $destPath = rtrim($destDir, '/') . '/' . $filename;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $this->error = 'Failed to save file.';
            return null;
        }

        return $filename;
    }

    public function error(): string { return $this->error; }

    private function extFromMime(string $mime): string {
        return match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            default      => 'bin',
        };
    }

    private function uploadErrorMsg(int $code): string {
        return match($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File exceeds size limit.',
            UPLOAD_ERR_PARTIAL   => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE   => 'No file was uploaded.',
            default              => 'Upload error (code ' . $code . ').',
        };
    }

    public static function delete(string $path): void {
        if (file_exists($path)) @unlink($path);
    }
}
