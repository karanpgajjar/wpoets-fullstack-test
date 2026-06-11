<?php

function inline_svg(string $path, string $class = '', string $alt = ''): string
{
    if (file_exists($path) && is_readable($path)) {
        $svg = file_get_contents($path);
        if ($class !== '') {
            $svg = preg_replace('/<svg\s/', '<svg class="' . htmlspecialchars($class) . '" ', $svg, 1);
        }
        return $svg;
    }

    return '<img src="' . htmlspecialchars($path) . '" alt="' . htmlspecialchars($alt) . '"'
        . ($class ? ' class="' . htmlspecialchars($class) . '"' : '') . '>';
}


function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function css_class(array $classes): string
{
    return implode(' ', array_filter($classes));
}
