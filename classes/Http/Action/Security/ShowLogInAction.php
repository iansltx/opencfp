<?php

declare(strict_types=1);

/**
 * Copyright (c) 2013-2018 OpenCFP
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/opencfp/opencfp
 */

namespace OpenCFP\Http\Action\Security;

use OpenCFP\Domain\Services;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Twig_Environment;

final class ShowLogInAction
{
    /**
     * @var Services\Authentication
     */
    private $authentication;

    /**
     * @var Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    /** @var string */
    private $clientSecret;

    /** @var Twig_Environment */
    private $twig;

    public function __construct(
        Services\Authentication $authentication,
        Routing\Generator\UrlGeneratorInterface $urlGenerator,
        string $clientSecret,
        Twig_Environment $twig
    ) {
        $this->authentication = $authentication;
        $this->urlGenerator   = $urlGenerator;
        $this->clientSecret   = $clientSecret;
        $this->twig           = $twig;
    }

    public function __invoke(): HttpFoundation\Response
    {
        if ($this->authentication->isAuthenticated()) {
            $url = $this->urlGenerator->generate('dashboard');

            return new HttpFoundation\RedirectResponse($url);
        }
        $content = $this->twig->render('security/login.twig', ['clientSecret' => $this->clientSecret]);

        return new HttpFoundation\Response($content);
    }
}
