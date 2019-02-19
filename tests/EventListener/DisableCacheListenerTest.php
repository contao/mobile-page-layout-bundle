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

use Contao\MobilePageLayoutBundle\EventListener\DisableCacheListener;
use Contao\PageModel;
use Contao\TestCase\ContaoTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class DisableCacheListenerTest extends ContaoTestCase
{
    public function testResponseRemainsUntouchedIfMobileLayoutIsNotAssigned(): void
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
            'mobileLayout' => false,
        ]);

        $listener = new DisableCacheListener();
        $listener->onKernelResponse($event);

        $this->assertSame('public, s-maxage=600', $event->getResponse()->headers->get('Cache-Control'));
    }

    public function testDisablesCacheIfMobileLayoutIsAssigned(): void
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
            'mobileLayout' => 42,
        ]);

        $listener = new DisableCacheListener();
        $listener->onKernelResponse($event);

        $this->assertSame('no-store, private', $event->getResponse()->headers->get('Cache-Control'));
    }
}
