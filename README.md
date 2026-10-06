# terragaming/laravel-ads

[TerraGaming Media](https://terragamingmedia.com) ads for Laravel: Blade components for the site
tag and ad units.

```bash
composer require terragaming/laravel-ads
```

```bash
# .env
TGM_HOST=tgmads.example.com
```

```blade
{{-- your layout's <head>: the site tag, once --}}
<x-tgm-site-tag />

{{-- wherever an ad goes (a unit may repeat on a page) --}}
<x-tgm-ad unit="TGM-ABC-HRS01" />
<x-tgm-ad unit="TGM-ABC-GMA01" slot="floating" />
```

Directives: `@tgmSiteTag` and `@tgmAd('TGM-ABC-HRS01')`. In-article ads are automatic.

- **Before your CNAME is verified**, also set `TGM_PROPERTY`, `TGM_PUBLISHER`,
  `TGM_IN_ARTICLE_UNIT` (and optionally `TGM_ARTICLE_SELECTOR`) from the portal.
- **CSP:** the site tag carries `Vite::cspNonce()` (or spatie/laravel-csp's nonce), or pass
  `<x-tgm-site-tag nonce="…" />`.
- **Inertia:** keep `<x-tgm-site-tag />` in `app.blade.php` and place units with
  `@terragamingmedia/ads-react` or `@terragamingmedia/ads-vue`.
- **Livewire `wire:navigate` / Turbo:** navigation is detected by the tag; wrap the floating unit in
  `@persist('tgm-floating')`.
- `TGM_ENABLED=false` renders nothing; `TGM_SPA=manual` leaves page views to you.
- Publish the config: `php artisan vendor:publish --tag=tgm-config`.

Guide: https://help.terragamingmedia.com/publishers/install/laravel/

## Development

```bash
composer install
vendor/bin/phpunit
```

CI runs the tests on Laravel 12 (PHP 8.2 and 8.3) and 13 (PHP 8.4). Laravel 11 is no longer
supported: its security support has ended and every release has open security advisories. Releases: push a `v<x.y.z>` tag; Packagist picks it up
from this repository.

## Licence

MIT — see [LICENSE](LICENSE).
