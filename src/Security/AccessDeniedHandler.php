<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new Response($this->twig->render('access-denied.html.twig'), 403, ['Refresh' => '5; url=/dashboard']);
    }
}