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
     * Inherits the mobile layout from the parent models.
     */
    public function onLoadPageDetails(array $parentModels, PageModel $page): void
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
