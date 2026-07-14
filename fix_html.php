<?php

$content = file_get_contents('C:\Users\hp\.gemini\antigravity-ide\brain\d0f3a8b8-1ba8-4b43-b3ec-14284325468e\.system_generated\steps\241\content.md');

// Use regex to extract the body content between <body> and </body>
preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches);
if (empty($matches)) {
    die("No body found");
}

$bodyContent = $matches[1];

// We need to fix unclosed tags for Vue. 
// The easiest way is to load it into DOMDocument and save as XML, but that might break other things.
// Instead, let's just do some basic regex replacements for common unclosed tags in Bootstrap
$bodyContent = preg_replace('/<img([^>]*[^\/])>/is', '<img$1 />', $bodyContent);
$bodyContent = preg_replace('/<input([^>]*[^\/])>/is', '<input$1 />', $bodyContent);
$bodyContent = preg_replace('/<br>/i', '<br />', $bodyContent);
$bodyContent = preg_replace('/<hr>/i', '<hr />', $bodyContent);
$bodyContent = preg_replace('/<link([^>]*[^\/])>/is', '<link$1 />', $bodyContent);
$bodyContent = preg_replace('/<meta([^>]*[^\/])>/is', '<meta$1 />', $bodyContent);

// Remove scripts to avoid Vue errors
$bodyContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $bodyContent);

// Replace Ryans with Electronics Dokan
$bodyContent = str_replace('Ryans', 'Electronics Dokan', $bodyContent);
$bodyContent = str_replace('RYANS', 'ELECTRONICS DOKAN', $bodyContent);
$bodyContent = str_replace('ryans.com', 'electronicsdokan.com', $bodyContent);

// Split into Layout (Header/Footer) and Welcome (Main Content)
// Header usually ends before the main banner.
// Let's find the end of the header. It's usually after the `<nav id="navbar_main"` block.
// Or we can just save the whole thing and let the user see it.
file_put_contents('ryans_fixed.html', $bodyContent);
echo "HTML fixed and saved to ryans_fixed.html\n";

