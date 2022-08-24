<?php

namespace Tests\Functional;

use App\Tests\FunctionalTester;

class LoginCest
{
    public function tryLogin(FunctionalTester $I)
    {
        $I->amOnPage('/registration');
        $I->see('Registration Form');
        $I->fillField('registration_form[email]', 'test@test.com');
        $I->fillField('registration_form[plainPassword]', 'password');
        $I->checkOption('#registration_form_agreeTerms');
        $I->click('#submitReg');
        $I->see('An email has been sent. Please check your inbox to complete registration.');

        $I->amOnPage('/login');
        $I->see('Please Sign In');
        $I->fillField('email', 'test@test.com');
        $I->fillField('password', 'password');
        $I->click('signIn');
        $I->see('Welcome, to your profile!', 'h1');

        $I->amOnPage('/profile/edit');
        $I->see('Edit your profile', 'h1');

        $I->fillField('profile_edit_form[fullName]', 'test Name');
        $I->fillField('profile_edit_form[phone]', '+788888888');
        $I->fillField('profile_edit_form[address]', 'test address');
        $I->fillField('profile_edit_form[zipcode]', '8558');
        $I->click('#saveChanges');
        $I->see('Welcome, to your profile!', 'h1');
    }
}