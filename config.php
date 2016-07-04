<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Material
 *
 * @package   theme_material
 * @copyright 2016 CourseBit LLC {@link http://www.coursebit.net}
 * @author    Joseph Conradt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'material';
$THEME->doctype = 'html5';
$THEME->yuicssmodules = array();
$THEME->parents = array('base');
$THEME->sheets = array(
    'moodle-material'
);
$THEME->layouts = array(
    'base' => array(
        'file' => 'base.php',
        'regions' => array('side-pre', 'side-post')
    ),
    'admin' => array(
        'file' => 'base.php',
        'regions' => array('side-pre', 'side-post')
    ),
    'mydashboard' => array(
        'file' => 'base.php',
        'regions' => array('side-pre', 'side-post')
    )
);
$THEME->javascripts_footer = array(
    'material'
);
$THEME->rendererfactory = 'theme_overridden_renderer_factory';