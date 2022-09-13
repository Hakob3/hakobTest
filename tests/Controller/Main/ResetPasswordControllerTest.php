<?php

namespace App\Tests\Controller\Main;

use App\Controller\Main\ResetPasswordController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group controller
 */
class ResetPasswordControllerTest extends WebTestCase
{
    public function testSendResetPasswordMail(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password');

        $crawler = $client->submitForm(
            'passwordResetRequest',
            [
                'reset_password_request_form[email]' => 'baghdasaryan.hakob@mail.ru'
            ]
        );

        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'p',
            'If an account matching your email exists, then an email was just sent that contains a link that you can use to reset your password. This link will expire in 1 hour .'
        );

        $pattern = '/(?<="http:\/\/localhost)\X*(?=")/';

        preg_match($pattern, $email->toString(), $matches);

        $client->request('GET', str_replace("=\r\n", '', $matches[0]));
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'label',
            'New password'
        );

        $client->submitForm(
            'resetPassword',
            [
                'change_password_form[plainPassword][first]' => '456456',
                'change_password_form[plainPassword][second]' => '456456',
            ]
        );
        $client->followRedirect();

        $this->assertSelectorTextContains(
            'label',
            'Username or email address'
        );

        $client->submitForm(
            'signIn',
            [
                'email' => 'baghdasaryan.hakob@mail.ru',
                'password' => '4356456',
            ]
        );

        $client->followRedirect();
        $this->assertSelectorTextContains(
            'h1',
            'Welcome, to your profile!'
        );
    }
}
