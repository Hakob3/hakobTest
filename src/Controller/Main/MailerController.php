<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/email', name: 'send_mail')]
    public function sendEmail(Request $request): Response
    {
//        $email = (new Email())
////            ->from('baghdasaryan.hakob@mail.ru')
////            ->to('hakobApo@yandex.ru')
//            ->to('baghdasaryan.hakob@mail.ru')
//            //->cc('cc@example.com')
//            //->bcc('bcc@example.com')
//            //->replyTo('fabien@example.com')
//            //->priority(Email::PRIORITY_HIGH)
//            ->subject('Time for Symfony Mailer!')
//            ->text('Sending emails is fun again!');
////            ->html('<p>See Twig integration for better HTML integration!</p>');
////            dd($email);
//
//        try {
//            $mailer->send($email);
//        } catch (TransportExceptionInterface $e) {
//
//            echo $e;
//            // some error prevented the email sending; display an
//            // error message or try to resend the message
//        }

        $email = (new Email())
            ->from('hakobApo@yandex.ru')
            ->to('baghdasaryan.hakob@mail.ru')
//            ->replyTo(self::EMAIL_ADMIN_EMAIL_FROM)
            ->subject('ha4834kob')
            ->html('43243');

        $dsn = $this->getParameter('mailer_dsn');

//        dd($dsn);

        $transport = Transport::fromDsn($dsn);

        $mailer = new Mailer($transport);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            echo $e;
        }

        return $this->render('/main/mailer/index.html.twig', [
            'controller_name' => 'mailer'
        ]);
    }
}
