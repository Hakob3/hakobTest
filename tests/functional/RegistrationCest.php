<?php
namespace Tests\Functional;

use App\Tests\FunctionalTester;

class RegistrationCest
{
    public function trySignUp(FunctionalTester $I)
    {
        $I->amOnPage('/registration');
        $I->see('Registration Form');
        $I->fillField('registration_form[email]', 'test@test.com');
        $I->fillField('registration_form[plainPassword]', 'password');
        $I->checkOption('#registration_form_agreeTerms');
        $I->click('#submitReg');
        $I->see('An email has been sent. Please check your inbox to complete registration.');
    }
}