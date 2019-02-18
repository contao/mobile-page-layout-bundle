<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\MobilePageLayoutBundle\EventListener;

use Contao\PageModel;

class InheritMobileLayoutListener
{
    /**
     * Inherit mobile layout from parent models.
     */
    public function onLoadPageDetails(PageModel $page, array $parentModels): void
    {
        $page->mobileLayout = $page->includeLayout ? $page->mobileLayout : false;

        foreach ($parentModels as $parentModel) {
            if ($parentModel->includeLayout && $parentModel->mobileLayout) {
                $page->mobileLayout = $parentModel->mobileLayout;
                break;
            }
        }
    }
}
