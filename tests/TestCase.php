<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    public function checkEnv($env)
    {
        if (config('app.env') != $env) {
            dd('Invalid environment.');
        }
    }

    public function dropColumn($table, $column)
    {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function ($tbl) use ($column) {
                $tbl->dropColumn($column);
            });
        }
    }

    public function getTestFile()
    {
        return new UploadedFile(
            public_path('storage\test\images\sample.png'),
            'sample.png',
            'images/png',
            null,
            true
        );
    }
}
