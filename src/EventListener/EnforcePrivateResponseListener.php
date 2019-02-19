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

class EnforcePrivateResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!isset($GLOBALS['objPage'])) {
            return;
        }

        /** @var PageModel $objPage */
        $objPage = $GLOBALS['objPage'];

        if ($objPage->isMobile) {
            $event->getResponse()->setPrivate();
        }
    }
}
