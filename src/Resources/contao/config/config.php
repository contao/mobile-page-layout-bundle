<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

/**
 * Inherit the mobile page layout
 */
\Contao\PageModel::addInheritanceCallback(function(Contao\PageModel $currentPage, Contao\PageModel $parentPage) {

    $currentPage->mobileLayout = $currentPage->includeLayout ? $currentPage->mobileLayout : false;

    // Layout
    if ($parentPage->includeLayout)
    {
        if ($currentPage->mobileLayout === false)
        {
            $currentPage->includeLayout = $parentPage->includeLayout;
            $currentPage->mobileLayout = $parentPage->mobileLayout;
        }
    }
});
