<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\MobilePageLayoutBundle\EventListener\InsertTags;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\RequestStack;

class ToggleViewListener
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $fragmentPath;

    public function __construct(ContaoFramework $framework, RequestStack $requestStack, string $fragmentPath)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;
        $this->fragmentPath = $fragmentPath;
    }

    /**
     * Replaces the "toggle_view" insert tag.
     *
     * @return string|false
     */
    public function onReplaceInsertTags(string $tag, bool $cache)
    {
        $chunks = explode('::', $tag);

        if ('toggle_view' !== $chunks[0]) {
            return false;
        }

        if ($cache) {
            return '{{toggle_view|uncached}}';
        }

        $request = $this->requestStack->getMasterRequest();

        if (null === $request) {
            return '';
        }

        /** @var Environment $environment */
        $environment = $this->framework->getAdapter(Environment::class);
        $strRequest = $environment->get('request');

        // ESI request
        if (preg_match('/^'.preg_quote(ltrim($this->fragmentPath, '/'), '/').'/', $strRequest)) {
            $strRequest = $request->query->get('request');
        }

        $strUrl = ampersand($strRequest);
        $strGlue = (false === strpos($strUrl, '?')) ? '?' : '&amp;';

        /** @var System $system */
        $system = $this->framework->getAdapter(System::class);
        $system->loadLanguageFile('default');

        if ('mobile' === $request->cookies->get('TL_VIEW') || ($environment->get('agent')->mobile && 'desktop' !== $request->cookies->get('TL_VIEW'))) {
            return '<a href="'.$strUrl.$strGlue.'toggle_view=desktop" class="toggle_desktop" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['toggleDesktop'][1]).'">'.$GLOBALS['TL_LANG']['MSC']['toggleDesktop'][0].'</a>';
        }

        return '<a href="'.$strUrl.$strGlue.'toggle_view=mobile" class="toggle_mobile" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['toggleMobile'][1]).'">'.$GLOBALS['TL_LANG']['MSC']['toggleMobile'][0].'</a>';
    }
}
