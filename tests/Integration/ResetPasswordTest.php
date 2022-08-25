<?php

namespace App\Tests\Integration;

use App\Controller\Main\ResetPasswordController;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;

/**
 * @group resetPassword
 */
class ResetPasswordTest extends KernelTestCase
{
    /**
     * @var ResetPasswordController|object|null
     */
    private $resetPasswordController;

    /**
     * @var UserRepository|object|null
     */
    private $userRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->resetPasswordController = self::getContainer()->get(ResetPasswordController::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    /**
     * @throws ResetPasswordExceptionInterface
     */
    public function testPasswordReset(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'test_admin_1@gmail.com']);
        $this->assertFalse(null === $user);
        $resetToken = $this->resetPasswordController->generatePasswordResetToken($user);

        $this->resetPasswordController->hashAndResetPassword($resetToken->getToken(), $user, 123123);

        $currentDateTime = new \DateTimeImmutable();

        $this->assertGreaterThan($currentDateTime, $resetToken->getExpiresAt());
    }
}
