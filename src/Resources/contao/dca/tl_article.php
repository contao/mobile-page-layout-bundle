<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

/**
 * Override core options_callback
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['inColumn']['options_callback'][0] = 'tl_article_mobile_page_layout_bundle';

class tl_article_mobile_page_layout_bundle
{
    /**
     * Return all active layout sections as array
     *
     * @param Contao\DataContainer $dc
     *
     * @return array
     */
    public function getActiveLayoutSections(Contao\DataContainer $dc)
    {
        // Show only active sections
        if ($dc->activeRecord->pid)
        {
            $arrSections = array();
            $objPage = Contao\PageModel::findWithDetails($dc->activeRecord->pid);

            // Get the layout sections
            foreach (array('layout', 'mobileLayout') as $key)
            {
                if (!$objPage->$key)
                {
                    continue;
                }

                $objLayout = Contao\LayoutModel::findByPk($objPage->$key);

                if ($objLayout === null)
                {
                    continue;
                }

                $arrModules = Contao\StringUtil::deserialize($objLayout->modules);

                if (empty($arrModules) || !\is_array($arrModules))
                {
                    continue;
                }

                // Find all sections with an article module (see #6094)
                foreach ($arrModules as $arrModule)
                {
                    if ($arrModule['mod'] == 0 && $arrModule['enable'])
                    {
                        $arrSections[] = $arrModule['col'];
                    }
                }
            }
        }

        // Show all sections (e.g. "override all" mode)
        else
        {
            $arrSections = array('header', 'left', 'right', 'main', 'footer');
            $objLayout = $this->Database->query("SELECT sections FROM tl_layout WHERE sections!=''");

            while ($objLayout->next())
            {
                $arrCustom = Contao\StringUtil::deserialize($objLayout->sections);

                // Add the custom layout sections
                if (!empty($arrCustom) && \is_array($arrCustom))
                {
                    foreach ($arrCustom as $v)
                    {
                        if (!empty($v['id']))
                        {
                            $arrSections[] = $v['id'];
                        }
                    }
                }
            }
        }

        return Contao\Backend::convertLayoutSectionIdsToAssociativeArray($arrSections);
    }
}