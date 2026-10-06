<?php

namespace TerraGaming\Ads;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Vite;

/**
 * Renders the site tag and ad unit placements from config/tgm.php. Values are validated; invalid
 * ones are left out, and an invalid host or unit id renders nothing.
 */
class Tags
{
    private const HOST = '/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)+$/';

    private const PROPERTY = '/^PROP-\d+-\d+$/';

    private const PUBLISHER = '/^PUB-\d+$/';

    private const UNIT = '/^TGM-[A-Z0-9]{3}-[A-Z0-9]{2,12}$/';

    private const SELECTOR = '/^[^<>"\'`\\\\{};]{1,200}$/';

    public function __construct(private readonly Repository $config) {}

    /** The site tag's `<script>`, or '' when disabled or without a valid host. */
    public function siteTag(?string $nonce = null): string
    {
        if (! $this->config->get('tgm.enabled', true)) {
            return '';
        }
        $host = $this->host((string) $this->config->get('tgm.host', ''));
        if ($host === '') {
            return '';
        }
        $attrs = ['src' => "https://{$host}/tag.js"];
        $fields = [
            'property' => ['data-tgm-property', self::PROPERTY],
            'publisher' => ['data-tgm-publisher', self::PUBLISHER],
            'in_article_unit' => ['data-tgm-in-article', self::UNIT],
            'article_selector' => ['data-tgm-article-selector', self::SELECTOR],
        ];
        foreach ($fields as $key => [$attr, $pattern]) {
            $value = trim((string) $this->config->get("tgm.{$key}", ''));
            if ($value !== '' && preg_match($pattern, $value)) {
                $attrs[$attr] = $value;
            }
        }
        if ($this->config->get('tgm.spa') === 'manual') {
            $attrs['data-tgm-spa'] = 'manual';
        }
        $nonce ??= $this->viteNonce();
        if ($nonce !== null && $nonce !== '') {
            $attrs['nonce'] = $nonce;
        }
        $html = '<script async';
        foreach ($attrs as $name => $value) {
            $html .= ' '.$name.'="'.e($value).'"';
        }

        return $html.'></script>';
    }

    /** One ad unit placement, or '' for an invalid unit id. */
    public function ad(?string $unit, ?string $slot = null, ?string $class = null): string
    {
        $unit = strtoupper(trim((string) $unit));
        if (! preg_match(self::UNIT, $unit)) {
            return '';
        }
        $classes = trim('tgm-ad '.($class ?? ''));
        $slotAttr = $slot === 'floating' ? ' data-tgm-slot="floating"' : '';

        return '<div class="'.e($classes).'" data-tgm-unit="'.e($unit).'"'.$slotAttr.'></div>';
    }

    private function host(string $host): string
    {
        $host = strtolower(trim($host));
        $host = rtrim((string) preg_replace('#^https?://#', '', $host), '/');

        return preg_match(self::HOST, $host) ? $host : '';
    }

    /** Laravel's Vite CSP nonce, else spatie/laravel-csp's. */
    private function viteNonce(): ?string
    {
        if (app()->bound(Vite::class)) {
            $nonce = app(Vite::class)->cspNonce();
            if ($nonce) {
                return $nonce;
            }
        }

        return function_exists('csp_nonce') ? (string) csp_nonce() : null;
    }
}
