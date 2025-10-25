<?php
if (!function_exists('app_base')) {
  function app_base(): string {
    $docRoot = rtrim(str_replace('\\','/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $projRoot = str_replace('\\','/', realpath(__DIR__ . '/..'));
    if ($projRoot === false) { return ''; }
    $projRoot = rtrim($projRoot, '/');
    $rel = str_replace($docRoot, '', $projRoot);
    if ($rel === $projRoot) { // document root not found, fallback
      // Try using SCRIPT_NAME and remove trailing path segments to reach project root
      $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
      // Best effort fallback
      return $scriptDir === '/' ? '' : $scriptDir;
    }
    return $rel === '' ? '' : $rel;
  }
}
