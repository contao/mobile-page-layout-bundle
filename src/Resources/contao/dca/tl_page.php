<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = function() {
    Contao\CoreBundle\DataContainer\PaletteManipulator::create()
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
    'options_callback'        => function ()
    {
        $objLayout = Contao\Database::getInstance()->execute("SELECT l.id, l.name, t.name AS theme FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id ORDER BY t.name, l.name");

        if ($objLayout->numRows < 1)
        {
            return array();
        }

        $return = array();

        while ($objLayout->next())
        {
            $return[$objLayout->theme][$objLayout->id] = $objLayout->name;
        }

        return $return;
    },
    'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);
