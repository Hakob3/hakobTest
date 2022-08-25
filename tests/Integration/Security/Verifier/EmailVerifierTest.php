<?php

namespace App\Tests\Integration\Security\Verifier;

use App\Repository\UserRepository;
use App\Security\Verifier\EmailVerifier;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;


/**
 * @group integrationEmail
 */
class EmailVerifierTest extends KernelTestCase
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->emailVerifier = self::getContainer()->get(EmailVerifier::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    public function testEmailSignature(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'test_admin_1@gmail.com']);
        $user->setIsVerified(false);

        $currentDateTime = new \DateTimeImmutable();
        $emailSignature = $this->emailVerifier->generateEmailSignature('app_verify_email', $user);

        $this->assertGreaterThan($currentDateTime, $emailSignature->getExpiresAt());
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function testHandleEmailConfirmation(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'test_admin_1@gmail.com']);
        $user->setIsVerified(false);

        $emailSignature = $this->emailVerifier->generateEmailSignature('app_verify_email', $user);

        $this->emailVerifier->handleEmailConfirmation($emailSignature->getSignedUrl(), $user);
        $this->assertTrue($user->isVerified());
    }
}
