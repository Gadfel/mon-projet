<?php

namespace App\Controller;


use cebe\markdown\GithubMarkdown;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CgvController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function index(GithubMarkdown $parser): Response
    {
        $content = file_get_contents('./../cgv.md'); // récupère le contenu du README
        // dd($content);

        $parsedContent = $parser->parse($content);
        // dd($parsedContent);
        return $this->render('cgv/index.html.twig', [
            'content' => $parsedContent,
        ]);
    }
}
