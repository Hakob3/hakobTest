<?php

namespace Tests\Functional;

use App\Tests\FunctionalTester;

class UserEditCest
{
    public function edit(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->see('Auth Form', 'h1');
        $I->fillField('email', 'admin@test.com');
        $I->fillField('password', '123123');
        $I->click('Login');
        $I->see('Dashboard', 'h1');
        $I->amOnPage('/admin/user/edit/4');
        $I->see('Edit', 'h6');
        $I->fillField('edit_user_form[email]', 'testUser@mail.com');
        $I->fillField('edit_user_form[password]', '456456');
        $I->selectOption('edit_user_form[roles]', 'Admin');
        $I->checkOption('edit_user_form[isVerified]');
        $I->click('Save changes');
        $I->see('Your changes were saved!', 'div');
    }
}