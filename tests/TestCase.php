<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Boot the application, refusing any database that is not dedicated to the tests.
     */
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $config = $app->make('config');
        $database = (string) $config->get('database.connections.'.$config->get('database.default').'.database');

        if (! str_ends_with($database, '_test')) {
            throw new RuntimeException(sprintf(
                'The test suite refuses to run on the "%s" database: its name must end with "_test".',
                $database,
            ));
        }

        return $app;
    }
}
