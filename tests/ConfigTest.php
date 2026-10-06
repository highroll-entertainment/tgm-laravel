<?php

/**
 * configuration: `config/tgm.php` reads the TGM_* environment variables and can be
 * published with `php artisan vendor:publish --tag=tgm-config`.
 */

namespace TerraGaming\Ads\Tests;

use Illuminate\Support\ServiceProvider;
use TerraGaming\Ads\TgmServiceProvider;

final class ConfigTest extends TestCase
{
    public function test_defaults(): void
    {
        $this->assertTrue(config('tgm.enabled'));
        $this->assertSame('', config('tgm.host'));
        $this->assertSame('auto', config('tgm.spa'));
    }

    public function test_publishable_config(): void
    {
        $paths = ServiceProvider::pathsToPublish(TgmServiceProvider::class, 'tgm-config');
        $this->assertCount(1, $paths);
        $this->assertStringEndsWith('tgm.php', array_values($paths)[0]);
    }
}
