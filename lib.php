<?php


/**
 * Return path to include file
 *
 * @param string $file Name of file
 * @return string
 */
function theme_material_include($file) {
    global $CFG;

    $filepath = sprintf('%s/theme/material/layout/include/%s.php', $CFG->dirroot, $file);

    if (!file_exists($filepath)) {
        throw new \LogicException('Included layout file not found: ' . $file);
    }

    return $filepath;
}