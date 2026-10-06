<?php

namespace TerraGaming\Ads;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TerraGaming\Ads\View\Components\Ad;
use TerraGaming\Ads\View\Components\SiteTag;

/**
 * TerraGaming Media ads for Laravel: config/tgm.php, `<x-tgm-site-tag />` / `@tgmSiteTag` and
 * `<x-tgm-ad unit="…" />` / `@tgmAd('…')`. Stateless (safe under Octane).
 */
class TgmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tgm.php', 'tgm');
        $this->app->bind(Tags::class, fn ($app) => new Tags($app['config']));
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/tgm.php' => config_path('tgm.php')], 'tgm-config');

        Blade::component('tgm-site-tag', SiteTag::class);
        Blade::component('tgm-ad', Ad::class);
        Blade::directive('tgmSiteTag', fn ($nonce) => '<?php echo app(\\TerraGaming\\Ads\\Tags::class)->siteTag('.($nonce !== '' ? $nonce : 'null').'); ?>');
        Blade::directive('tgmAd', fn ($expression) => '<?php echo app(\\TerraGaming\\Ads\\Tags::class)->ad('.$expression.'); ?>');
    }
}
