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

$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$hasblocks = empty($PAGE->layout_options['noblocks']);

$maincolumn = $hasblocks ? 'col-sm-8' : 'col-sm-12';

$knownregionpre = $PAGE->blocks->is_known_region('side-pre');
$knownregionpost = $PAGE->blocks->is_known_region('side-post');

require(theme_material_include('head'));

?>

<div id="page" class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">Home</span>
            <div class="mdl-layout-spacer"></div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
                    <i class="material-icons">search</i>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <input class="mdl-textfield__input" type="text" id="search">
                    <label class="mdl-textfield__label" for="search">Enter your query...</label>
                </div>
            </div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
                <li class="mdl-menu__item">About</li>
                <li class="mdl-menu__item">Contact</li>
                <li class="mdl-menu__item">Legal information</li>
            </ul>
        </div>
    </header>
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
            <img src="images/user.jpg" class="demo-avatar">
            <div class="demo-avatar-dropdown">
                <span>hello@example.com</span>
                <div class="mdl-layout-spacer"></div>
                <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                    <i class="material-icons" role="presentation">arrow_drop_down</i>
                    <span class="visuallyhidden">Accounts</span>
                </button>
                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                    <li class="mdl-menu__item">hello@example.com</li>
                    <li class="mdl-menu__item">info@example.com</li>
                    <li class="mdl-menu__item"><i class="material-icons">add</i>Add another account...</li>
                </ul>
            </div>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Home</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">inbox</i>Inbox</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">delete</i>Trash</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">report</i>Spam</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">forum</i>Forums</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">flag</i>Updates</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">local_offer</i>Promos</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">shopping_cart</i>Purchases</a>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">people</i>Social</a>
            <div class="mdl-layout-spacer"></div>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>
    </div>
    <main id="page-content2" class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid">
            <div class="mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--8-col">
                <?php echo $coursecontentheader; ?>
                <?php echo $OUTPUT->main_content() ?>
                <?php echo $coursecontentfooter; ?>
            </div>
            <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
                <?php if ($hassidepre): ?>
                    <?php echo $OUTPUT->blocks('side-pre'); ?>
                <?php endif; ?>

                <?php if ($hassidepre): ?>
                    <?php echo $OUTPUT->blocks('side-post'); ?>
                <?php endif; ?>
            </div>

        <?php if (false) if ($hasheading || $hasnavbar || !empty($courseheader)) { ?>
            <div id="page-header">
                <?php if ($hasheading) { ?>
                    <h1 class="headermain"><?php echo $PAGE->heading ?></h1>
                    <div class="headermenu"><?php
                    echo $OUTPUT->user_menu();
                    if (!empty($PAGE->layout_options['langmenu'])) {
                        echo $OUTPUT->lang_menu();
                    }
                    echo $PAGE->headingmenu
                    ?></div><?php } ?>
                <?php if (!empty($courseheader)) { ?>
                    <div id="course-header"><?php echo $courseheader; ?></div>
                <?php } ?>
                <?php if ($hascustommenu) { ?>
                    <div id="custommenu"><?php echo $custommenu; ?></div>
                <?php } ?>
                <?php if ($hasnavbar) { ?>
                    <div class="navbar clearfix">
                        <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                        <div class="navbutton"> <?php echo $PAGE->button; ?></div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- END OF HEADER -->

        <!-- START OF FOOTER -->
        <?php if (!empty($coursefooter)) { ?>
            <div id="course-footer"><?php echo $coursefooter; ?></div>
        <?php } ?>
        <?php if ($hasfooter) { ?>
            <div id="page-footer" class="clearfix">
                <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
                <?php
                echo $OUTPUT->login_info();
                echo $OUTPUT->home_link();
                echo $OUTPUT->standard_footer_html();
                ?>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    </main>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>