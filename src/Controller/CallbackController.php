<?php

namespace App\Controller;

use App\Service\Spotify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallbackController extends AbstractController
{
    /**
     * @Route("/callback", name="app_callback")
     */
    public function callbackFromSpotify(Request $request, Spotify $spotify): Response
    {
        $spotify->setAccessCode($request->query->get('code'));

        return new Response('Operation successfully completed ! You can close this page');
    }
}
