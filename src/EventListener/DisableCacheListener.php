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
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class DisableCacheListener
{
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!isset($GLOBALS['objPage'])) {
            return;
        }

        /** @var PageModel $objPage */
        $objPage = $GLOBALS['objPage'];

        // If the current page has a mobile layout assigned, we disable caching
        if ($objPage->mobileLayout) {
            $event->getResponse()->headers->set('Cache-Control', 'no-store');
        }
    }
}
