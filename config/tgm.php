<?php

/*
| TerraGaming Media ads (https://help.terragamingmedia.com/publishers/install/laravel/).
| Once your tgmads.<domain> host is verified, TGM_HOST is all you need: the tag reads your
| property's settings from it. On the shared host, also set the ids from the portal.
*/

return [
    // Set to false to render nothing (local environments, staging without ads).
    'enabled' => (bool) env('TGM_ENABLED', true),

    // Your tag host: tgmads.<your domain>, or the shared host the portal shows until it is verified.
    'host' => (string) env('TGM_HOST', ''),

    // Only needed on the shared host (Ad Units & Tags → Install site tag shows them).
    'property' => env('TGM_PROPERTY'),
    'publisher' => env('TGM_PUBLISHER'),
    'in_article_unit' => env('TGM_IN_ARTICLE_UNIT'),

    // The CSS selector of your article body; empty uses the one set in the portal.
    'article_selector' => env('TGM_ARTICLE_SELECTOR'),

    // "auto": the tag follows client-side navigation (Livewire wire:navigate, Turbo, Inertia).
    // "manual": call window.tgm.pageview() yourself after each navigation.
    'spa' => env('TGM_SPA', 'auto'),
];
