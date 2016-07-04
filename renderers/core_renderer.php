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

defined('MOODLE_INTERNAL') || die();

/**
 * @package   theme_material
 * @copyright 2016 CourseBit LLC {@link http://www.coursebit.net}
 * @author    Joseph Conradt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_material_core_renderer extends core_renderer {

    /**
     * Prints a nice side block with an optional header.
     *
     * The content is described
     * by a {@link core_renderer::block_contents} object.
     *
     * <div id="inst{$instanceid}" class="block_{$blockname} block">
     *      <div class="header"></div>
     *      <div class="content">
     *          ...CONTENT...
     *          <div class="footer">
     *          </div>
     *      </div>
     *      <div class="annotation">
     *      </div>
     * </div>
     *
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if (!empty($bc->blockinstanceid)) {
            $bc->attributes['data-instanceid'] = $bc->blockinstanceid;
        }
        $skiptitle = strip_tags($bc->title);
        if ($bc->blockinstanceid && !empty($skiptitle)) {
            $bc->attributes['aria-labelledby'] = 'instance-'.$bc->blockinstanceid.'-header';
        } else if (!empty($bc->arialabel)) {
            $bc->attributes['aria-label'] = $bc->arialabel;
        }
        if ($bc->dockable) {
            $bc->attributes['data-dockable'] = 1;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }


        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::link('#sb-'.$bc->skipid, get_string('skipa', 'access', $skiptitle),
                array('class' => 'skip skip-block', 'id' => 'fsb-' . $bc->skipid));
            $skipdest = html_writer::span('', 'skip-block-to',
                array('id' => 'sb-' . $bc->skipid));
        }

        // Add MDL card classes
        $bc->attributes['class'] .= ' mdl-card mdl-shadow--2dp';

        $output .= html_writer::start_tag('div', $bc->attributes);

        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);

        $blockid = null;
        if (isset($bc->attributes['id'])) {
            $blockid = $bc->attributes['id'];
        }
        $output .= html_writer::div($this->block_controls($bc->controls, $blockid), 'mdl-card__menu header');

        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    /**
     * Produces a header for a block
     *
     * @param block_contents $bc
     * @return string
     */
    protected function block_header(block_contents $bc) {

        $title = '';
        if ($bc->title) {
            $attributes = array(
                'class' => 'mdl-card__title-text'
            );
            if ($bc->blockinstanceid) {
                $attributes['id'] = 'instance-'.$bc->blockinstanceid.'-header';
            }
            $title = html_writer::tag('h2', $bc->title, $attributes);
        }

        $blockid = null;
        if (isset($bc->attributes['id'])) {
            $blockid = $bc->attributes['id'];
        }
        //$controlshtml = $this->block_controls($bc->controls, $blockid);
        $controlshtml = '';

        $output = '';
        if ($title || $controlshtml) {
            $output .= html_writer::tag('div', html_writer::tag('div', html_writer::tag('div', '', array('class'=>'block_action')). $title . $controlshtml, array('class' => 'title mdl-card__title')), array('class' => 'header2'));
        }
        return $output;
    }

    /**
     * Output the row of editing icons for a block, as defined by the controls array.
     *
     * @param array $controls an array like {@link block_contents::$controls}.
     * @param string $blockid The ID given to the block.
     * @return string HTML fragment.
     */
    public function block_controls($actions, $blockid = null) {
        global $CFG;
        if (empty($actions)) {
            return '';
        }
        $menu = new action_menu($actions);
        if ($blockid !== null) {
            $menu->set_owner_selector('#'.$blockid);
        }
        $menu->set_constraint('.block-region');
        $menu->attributes['class'] .= ' block-control-actions commands';
        if (isset($CFG->blockeditingmenu) && !$CFG->blockeditingmenu) {
            $menu->do_not_enhance();
        }
        return $this->render($menu);
    }

    /**
     * Renders an action menu component.
     *
     * ARIA references:
     *   - http://www.w3.org/WAI/GL/wiki/Using_ARIA_menus
     *   - http://stackoverflow.com/questions/12279113/recommended-wai-aria-implementation-for-navigation-bar-menu
     *
     * @param action_menu $menu
     * @return string HTML
     */
    public function render_action_menu(action_menu $menu) {
        $menu->initialise_js($this->page);

        $output = html_writer::start_tag('div', $menu->attributes);
        $output .= html_writer::start_tag('ul', $menu->attributesprimary);
        $actions = $menu->get_primary_actions($this);
        foreach ($menu->get_primary_actions($this) as $action) {
            if ($action instanceof renderable) {
                $content = $this->render($action);
            } else {
                // Since we can't override how the toggle-display action is rendered
                // we have to manually rework it here
                if (strpos($action, 'toggle-display') !== false) {

                    $dom = new DOMDocument();
                    $dom->loadHTML($action);

                    $attributes = array();
                    $a = $dom->getElementsByTagName('a')->item(0);
                    if ($a->hasAttributes()) {
                        foreach ($a->attributes as $attr) {
                            $name = $attr->nodeName;
                            $value = $attr->nodeValue;
                            $attributes[$name] = $value;
                        }
                    }

                    $attributes['class'] .= ' mdl-button mdl-js-button mdl-button--icon';

                    $content = html_writer::tag('button', html_writer::tag('i', 'more_vert', array('class' => 'material-icons')), $attributes);

                } else {
                    $content = $action;
                }
            }
            $output .= html_writer::tag('li', $content, array('role' => 'presentation'));
        }
        $output .= html_writer::end_tag('ul');
        $output .= html_writer::start_tag('ul', $menu->attributessecondary);
        foreach ($menu->get_secondary_actions() as $action) {
            if ($action instanceof renderable) {
                $content = $this->render($action);
            } else {
                $content = $action;
            }
            $output .= html_writer::tag('li', $content, array('role' => 'presentation'));
        }
        $output .= html_writer::end_tag('ul');
        $output .= html_writer::end_tag('div');
        return $output;
    }

    /**
     * Renders an action_menu_link item.
     *
     * @param action_menu_link $action
     * @return string HTML fragment
     */
    protected function render_action_menu_link(action_menu_link $action) {
        static $actioncount = 0;
        $actioncount++;

        $comparetoalt = '';
        $text = '';
        if (!$action->icon || $action->primary === false) {
            $text .= html_writer::start_tag('span', array('class'=>'menu-action-text', 'id' => 'actionmenuaction-'.$actioncount));
            if ($action->text instanceof renderable) {
                $text .= $this->render($action->text);
            } else {
                $text .= $action->text;
                $comparetoalt = (string)$action->text;
            }
            $text .= html_writer::end_tag('span');
        }

        $icon = '';
        if ($action->icon) {
            $icon = $action->icon;
            if ($action->primary || !$action->actionmenu->will_be_enhanced()) {
                $action->attributes['title'] = $action->text;
            }
            if (!$action->primary && $action->actionmenu->will_be_enhanced()) {
                if ((string)$icon->attributes['alt'] === $comparetoalt) {
                    $icon->attributes['alt'] = '';
                }
                if (isset($icon->attributes['title']) && (string)$icon->attributes['title'] === $comparetoalt) {
                    unset($icon->attributes['title']);
                }
            }
            $icon = $this->render($icon);
        }

        // A disabled link is rendered as formatted text.
        if (!empty($action->attributes['disabled'])) {
            // Do not use div here due to nesting restriction in xhtml strict.
            return html_writer::tag('span', $icon.$text, array('class'=>'currentlink', 'role' => 'menuitem'));
        }

        $attributes = $action->attributes;
        unset($action->attributes['disabled']);
        $attributes['href'] = $action->url;
        if ($text !== '') {
            $attributes['aria-labelledby'] = 'actionmenuaction-'.$actioncount;
        }

        return html_writer::tag('a', $icon.$text, $attributes);
    }
}