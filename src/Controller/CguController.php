<?php

namespace App\Controller;

use cebe\markdown\GithubMarkdown;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(GithubMarkdown $parser): Response
    {

        $content = file_get_contents('./../cgu.md'); // récupère le contenu du README
        // dd($content);

        $parsedContent = $parser->parse($content);
        // dd($parsedContent);
        return $this->render('cgu/index.html.twig', [
            'content' =>  $parsedContent,
        ]);
    }
}
