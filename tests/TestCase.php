<?php

namespace TerraGaming\Ads\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TerraGaming\Ads\TgmServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [TgmServiceProvider::class];
    }
}
