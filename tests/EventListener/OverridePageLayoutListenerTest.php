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

use Contao\Environment;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\TestCase\ContaoTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class OverridePageLayoutListenerTest extends ContaoTestCase
{
    /**
     * @dataProvider getLayoutData
     *
     * @param mixed|null $expectedMobileLayoutId
     */
    public function testCorrectLayoutIsAssigned(PageModel $pageModel, bool $agentIsMobile, ?string $viewCookieContent, $expectedMobileLayoutId = null): void
    {
        $agent = new \stdClass();
        $agent->mobile = $agentIsMobile;

        $environmentAdapter = $this->mockAdapter(['get']);
        $environmentAdapter
            ->expects($pageModel->mobileLayout ? $this->once() : $this->never())
            ->method('get')
            ->with('agent')
            ->willReturn($agent)
        ;

        $layoutModel = $this->mockClassWithProperties(LayoutModel::class, [
            'id' => $expectedMobileLayoutId ?: 12,
        ]);

        $layoutAdapter = $this->mockAdapter(['findByPk']);
        $layoutAdapter
            ->expects($expectedMobileLayoutId ? $this->once() : $this->never())
            ->method('findByPk')
            ->with($expectedMobileLayoutId)
            ->willReturn($layoutModel)
        ;

        $framework = $this->mockContaoFramework([
            Environment::class => $environmentAdapter,
            LayoutModel::class => $layoutAdapter,
        ]);

        $request = Request::create('/foobar');

        if ($viewCookieContent) {
            $request->cookies->set('TL_VIEW', $viewCookieContent);
        }

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $listener = new OverridePageLayoutListener($framework, $requestStack);
        $listener->onGetPageLayout($pageModel, $layoutModel);

        $this->assertSame($expectedMobileLayoutId ?: 12, $layoutModel->id);
    }

    public function getLayoutData(): \Generator
    {
        yield 'Pagemodel has no mobile layout assigned, should not be overridden when agent is not mobile' => [
            $this->getPageModel(null),
            false,
            null,
        ];

        yield 'Pagemodel has no mobile layout assigned, should not be overridden when agent is mobile' => [
            $this->getPageModel(null),
            true,
            null,
        ];

        yield 'Pagemodel has no mobile layout assigned, should not be overridden when agent is not mobile and view cookie set to mobile' => [
            $this->getPageModel(null),
            true,
            'mobile',
        ];

        yield 'Pagemodel has no mobile layout assigned, should not be overridden when agent is not mobile and view cookie set to mobile' => [
            $this->getPageModel(null),
            false,
            'mobile',
        ];

        yield 'Pagemodel has a mobile layout assigned, should not be overridden when agent is not mobile' => [
            $this->getPageModel(null),
            false,
            null,
        ];

        yield 'Pagemodel has a mobile layout assigned, should not be overridden when agent is mobile but view cookie is not' => [
            $this->getPageModel(null),
            true,
            'desktop',
        ];

        yield 'Pagemodel has a mobile layout assigned, should be overridden when agent is mobile and no view cookie is set' => [
            $this->getPageModel(42),
            true,
            null,
            42,
        ];

        yield 'Pagemodel has a mobile layout assigned, should not be overridden when agent is mobile but view cookie is set to desktop' => [
            $this->getPageModel(42),
            true,
            'desktop',
        ];

        yield 'Pagemodel has a mobile layout assigned, should overridden when agent is mobile and view cookie is set to mobile' => [
            $this->getPageModel(42),
            true,
            'mobile',
            42,
        ];
    }

    private function getPageModel(?int $mobileLayout): PageModel
    {
        return $this->mockClassWithProperties(PageModel::class, [
            'mobileLayout' => $mobileLayout,
        ]);
    }
}
