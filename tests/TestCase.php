<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = storage_path('framework/testing/views/' . uniqid('blade_', true));

        if (!File::exists($compiledPath)) {
            File::makeDirectory($compiledPath, 0755, true);
        }

        config()->set('view.compiled', $compiledPath);
    }
}
