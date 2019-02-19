<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\MobilePageLayoutBundle\Tests\EventListener;

use Contao\MobilePageLayoutBundle\EventListener\EnforcePrivateResponseListener;
use Contao\PageModel;
use Contao\TestCase\ContaoTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EnforcePrivateResponseListenerTest extends ContaoTestCase
{
    public function testResponseRemainseUntouchedIfMobileIsNotEnabled(): void
    {
        $response = new Response();
        $response->setSharedMaxAge(600);

        $event = new FilterResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            Request::create('/foobar'),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $GLOBALS['objPage'] = $this->mockClassWithProperties(PageModel::class, [
            'isMobile' => false,
        ]);

        $listener = new EnforcePrivateResponseListener();
        $listener->onKernelResponse($event);

        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective('private'));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective('public'));
    }

    public function testEnforcesPrivateResponseIfMobileIsEnabled(): void
    {
        $response = new Response();
        $response->setSharedMaxAge(600);

        $event = new FilterResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            Request::create('/foobar'),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $GLOBALS['objPage'] = $this->mockClassWithProperties(PageModel::class, [
            'isMobile' => true,
        ]);

        $listener = new EnforcePrivateResponseListener();
        $listener->onKernelResponse($event);

        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective('private'));
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective('public'));
    }
}
