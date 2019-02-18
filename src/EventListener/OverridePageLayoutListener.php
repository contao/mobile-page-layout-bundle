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

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\LayoutModel;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;

class OverridePageLayoutListener
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;
    }

    public function onGetPageLayout(PageModel $pageModel, LayoutModel &$layoutModel): void
    {
        if (!$pageModel->mobileLayout) {
            return;
        }

        /** @var Environment $environment */
        $environment = $this->framework->getAdapter(Environment::class);
        $request = $this->requestStack->getMasterRequest();
        $isMobile = $environment->get('agent')->mobile;

        if (null !== $request && $request->cookies->has('TL_VIEW')) {
            $isMobile = 'mobile' === $request->cookies->get('TL_VIEW');
        }

        if (!$isMobile) {
            return;
        }

        $layoutModel = $this->framework->getAdapter(LayoutModel::class)->findByPk($pageModel->mobileLayout);
    }
}
