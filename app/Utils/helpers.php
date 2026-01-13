<?php
if (!function_exists('igShortcodeToId')) {
    function igShortcodeToId(string $shortcode): int
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $id = 0;
        foreach (str_split($shortcode) as $c) {
            $id = $id * 64 + strpos($alphabet, $c);
        }
        return $id;
    }
}
if (!function_exists('getIgShortCodeFromUrl')) {
    function getIgShortCodeFromUrl(string $url): string
    {
        return explode('/', $url)[4];
    }
}
?>
