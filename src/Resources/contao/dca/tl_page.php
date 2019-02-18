<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = function() {
    \Contao\CoreBundle\DataContainer\PaletteManipulator::create()
        ->addField('mobileLayout', 'layout')
        ->applyToSubpalette('includeLayout', 'tl_page');
};

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileLayout'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mobileLayout'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_layout.name',
    'options_callback'        => array('tl_page', 'getPageLayouts'),
    'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);