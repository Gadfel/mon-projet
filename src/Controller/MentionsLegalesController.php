<?php

namespace App\Controller;

use cebe\markdown\GithubMarkdown;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions/legales', name: 'app_mentions_legales')]
    public function index(GithubMarkdown $parser): Response
    {
        $content = file_get_contents('./../mentions_legales.md'); // récupère le contenu du Metion legale
        // dd($content);

        $parsedContent = $parser->parse($content);
        // dd($parsedContent);
        return $this->render('mentions_legales/index.html.twig', [
            'content' => $parsedContent,
        ]);
    }
}
