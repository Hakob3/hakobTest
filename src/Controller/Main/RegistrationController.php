<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Form\Main\RegistrationFormType;
use App\Repository\RolesRepository;
use App\Repository\UserRepository;
use App\Security\Verifier\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Class RegistrationController
 * @package App\Controller\Main
 */
class RegistrationController extends AbstractController
{
    /** @var EmailVerifier  */
    private EmailVerifier $emailVerifier;

    /** @var VerifyEmailHelperInterface  */
    private VerifyEmailHelperInterface $verifyEmailHelper;

    /** @var RolesRepository  */
    private RolesRepository $rolesRepository;

    /**
     * RegistrationController constructor.
     * @param EmailVerifier $emailVerifier
     * @param VerifyEmailHelperInterface $verifyEmailHelper
     * @param RolesRepository $rolesRepository
     */
    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $verifyEmailHelper, RolesRepository $rolesRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->rolesRepository = $rolesRepository;
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/registration', name: 'main_registration')]
    public function registration(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main_profile_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($this->rolesRepository->findOneBy(['name' => 'ROLE_USER']));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('hakobapo3@yandex.ru', 'Hakob'))
                    ->to(new Address($user->getEmail(), 'Apo'))
                    ->subject('Email verification')
                    ->htmlTemplate('main/email/security/confirmation_email.html.twig')
            );
            $this->addFlash('success', 'An email has been sent. Please check your inbox to complete registration.');
            return $this->redirectToRoute('main_homepage');
        }

        return $this->renderForm('main/security/registration.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('main_registration');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('main_registration');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request->getUri(), $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('main_homepage');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('main_homepage');
    }
}
