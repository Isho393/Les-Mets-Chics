<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StorageSecurityTest extends TestCase
{
    public function test_existing_private_file_is_served(): void
    {
        $dir = storage_path('app/private/tests');
        File::ensureDirectoryExists($dir);
        File::put($dir.'/hello.txt', 'ok');

        try {
            $this->get('/api/storage/tests/hello.txt')->assertOk();
        } finally {
            File::deleteDirectory($dir);
        }
    }

    public function test_path_traversal_cannot_read_files_outside_storage(): void
    {
        $this->get('/api/storage/..%2F..%2F..%2F.env.example')->assertNotFound();
        $this->get('/api/storage/../../../composer.json')->assertNotFound();
    }

    public function test_missing_file_returns_404(): void
    {
        $this->get('/api/storage/inexistant.jpg')->assertNotFound();
    }
}
