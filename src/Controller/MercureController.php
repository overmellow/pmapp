<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class MercureController extends AbstractController
{
    /**
     * @Route("/admin/mercure", name="admin-mercure")
     */
    public function index()
    {
        return $this->render('mercure/index.html.twig', [
            'controller_name' => 'MercureController',
            'MERCURE_PUBLISH_URL' => $_ENV['MERCURE_PUBLISH_URL']
        ]);
    }

    /**
     * @Route("/admin/mercure/test", name="admin-mercure-test")
     */
    public function test(Request $request, PublisherInterface $publisher)
    {
        $val = $request->query->get('value');

        $update = new Update(
            '/admin/mercure',
            json_encode(['val' => $val])
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response(json_encode(['status' => true]), 200, array('Content-Type' => 'application/json'));
    }    
}
