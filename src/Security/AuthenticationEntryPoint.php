<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface {
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null) {
        $request->getSession()->getFlashBag()->add("info", "Pro tuto sekci se musíte příhlásit.");
        return new RedirectResponse($this->urlGenerator->generate("login"));
    }
}