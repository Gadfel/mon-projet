<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index')]
    public function index(Request $request, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $mail = (new TemplatedEmail())
                ->from(new Address($contact['emailAddress'], $contact['firstName'] . ' ' . $contact['lastName']))
                ->to(new Address('gadfellouiza@gmail.com'))
                ->subject('A.G Distribution - demande de contact')
                ->htmlTemplate('contact/contact_email.html.twig')
                ->context([
                    'firstName' => $contact['firstName'],
                    'lastName' => $contact['lastName'],
                    'emailAddress' => $contact['emailAddress'],
                    'phone' => $contact['phone'],
                    'companyName' => $contact['companyName'],
                    'message' => $contact['message'],
                ]);
            $mailer->send($mail);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
