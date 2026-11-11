<?php

use App\Models\Media;
use App\Models\WebsiteSetting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $v = WebsiteSetting::allCached()[$key] ?? null;

        return ($v === null || $v === '') ? $default : $v;
    }
}

if (! function_exists('setting_media')) {
    function setting_media(string $key): ?Media
    {
        static $cache = [];
        $id = setting($key);
        if (! $id) {
            return null;
        }

        return $cache[$id] ??= Media::find($id);
    }
}

if (! function_exists('clean_html')) {
    /** Allow a safe subset of HTML for admin-authored rich text. */
    function clean_html(?string $html): string
    {
        if (! $html) {
            return '';
        }
        if (strip_tags($html) === $html) {
            return nl2br(e($html));
        }
        $html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><blockquote><a><img><table><thead><tbody><tr><th><td><hr><span>');
        $html = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\']?)\s*(javascript|vbscript|data):[^"\'\s>]*/i', '$1=$2#', $html);

        return preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $html);
    }
}

if (! function_exists('icon_class')) {
    function icon_class(?string $icon): string
    {
        $icon = trim((string) $icon) ?: 'fa-circle';

        return str_contains($icon, ' ') ? $icon : 'fa-solid '.$icon;
    }
}

if (! function_exists('link_url')) {
    /** Turn an admin-entered link (/about-us, https://…, mailto:…) into a safe href. */
    function link_url(?string $u): string
    {
        $u = trim((string) $u);
        if ($u === '') {
            return '#';
        }
        if (trim($u, '/') === 'careers') {
            return setting('careers_url', 'https://jobs.kibokogroup.com');
        }
        if (preg_match('#^(https?:|mailto:|tel:|\#)#i', $u)) {
            return $u;
        }

        return preg_match('#^[a-z]+:#i', $u) ? '#' : url($u);
    }
}
