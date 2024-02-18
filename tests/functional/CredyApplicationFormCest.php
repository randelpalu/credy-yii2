<?php

use app\services\CredyApplicationServiceInterface;
use tests\mocks\services\MockCredyApplicationServiceSuccess;
use tests\mocks\services\MockCredyApplicationServiceHttpError;
use tests\mocks\services\MockCredyApplicationServiceFailure;

class CredyApplicationFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/credy-application');
    }

    public function submitWithEmptyFields(FunctionalTester $I): void
    {
        $I->seeElement('#credy-application-form');
        $I->submitForm('#credy-application-form', []);

        $I->expectTo('see validation errors');
        $I->see('First Name cannot be blank');
        $I->see('Last Name cannot be blank');
        $I->see('Email cannot be blank');
        $I->see('Bio cannot be blank');
        $I->see('Technologies cannot be blank');
        $I->see('Vcs Uri cannot be blank');
    }

    public function submitWithFaultyEmail(FunctionalTester $I): void
    {
        $I->seeElement('#credy-application-form');
        $I->submitForm('#credy-application-form', [
            'CredyApplicationForm[firstName]' => 'fake',
            'CredyApplicationForm[lastName]' => 'lastname',
            'CredyApplicationForm[email]' => 'faulty.email',
            'CredyApplicationForm[bio]' => 'some text',
            'CredyApplicationForm[technologies]' => 'php,javascript',
            'CredyApplicationForm[vcsUri]' => 'http://www.google.com',
        ]);

        $I->expectTo('see email validation error');
        $I->see('Email is not a valid email address.');
        $I->dontSee('First Name cannot be blank');
        $I->dontSee('Last Name cannot be blank');
        $I->dontSee('Bio cannot be blank');
        $I->dontSee('Technologies cannot be blank');
        $I->dontSee('Vcs Uri cannot be blank');
    }

    public function submitSuccessfully(FunctionalTester $I): void
    {
        Yii::$container->set(CredyApplicationServiceInterface::class, function () {
            return new MockCredyApplicationServiceSuccess();
        });

        $I->seeElement('#credy-application-form');
        $I->submitForm('#credy-application-form', [
            'CredyApplicationForm[firstName]' => 'John',
            'CredyApplicationForm[lastName]' => 'Melvin',
            'CredyApplicationForm[email]' => 'fake@email.com',
            'CredyApplicationForm[bio]' => 'some text',
            'CredyApplicationForm[technologies]' => 'php,javascript',
            'CredyApplicationForm[vcsUri]' => 'http://www.google.com',
        ]);

        $I->dontSeeElement('#credy-application-form');
        $I->see('Mocked success message');
        $I->see('Following data was submitted:');
        $I->see('First name: John');
        $I->see('Last name: Melvin');
        $I->see('Email: fake@email.com');
        $I->see('Bio: some text');
        $I->see('Technologies: php,javascript');

        $I->expectTo('See 10 digits long unix timestamp');
        $timestampValue = $I->grabTextFrom("//li[label[text()='Timestamp']]/text()");
        $I->assertRegExp('/^\s*:\s*\d{10}\s*$/', $timestampValue);

        $I->expectTo('See 40 digits SHA1 hash');
        $signatureValue = $I->grabTextFrom("//li[label[text()='Signature']]/text()");
        $I->assertRegExp('/^\s*:\s*[0-9a-zA-Z]{40}\s*$/', $signatureValue);
    }

    public function submitFailsDueToHttpError(FunctionalTester $I): void
    {
        Yii::$container->set(CredyApplicationServiceInterface::class, function () {
            return new MockCredyApplicationServiceHttpError();
        });

        $I->seeElement('#credy-application-form');
        $I->submitForm('#credy-application-form', [
            'CredyApplicationForm[firstName]' => 'John',
            'CredyApplicationForm[lastName]' => 'Melvin',
            'CredyApplicationForm[email]' => 'fake@email.com',
            'CredyApplicationForm[bio]' => 'some text',
            'CredyApplicationForm[technologies]' => 'php,javascript',
            'CredyApplicationForm[vcsUri]' => 'http://www.google.com',
        ]);

        $I->dontSeeElement('#credy-application-form');
        $I->expectTo('See Http error');
        $I->see('Error HTTP Error: 300 - not sure');
        $I->see('The above error occurred while the Web server was processing your request');
    }

    public function submitFailsDueToCURLFailure(FunctionalTester $I): void
    {
        Yii::$container->set(CredyApplicationServiceInterface::class, function () {
            return new MockCredyApplicationServiceFailure();
        });

        $I->seeElement('#credy-application-form');
        $I->submitForm('#credy-application-form', [
            'CredyApplicationForm[firstName]' => 'John',
            'CredyApplicationForm[lastName]' => 'Melvin',
            'CredyApplicationForm[email]' => 'fake@email.com',
            'CredyApplicationForm[bio]' => 'some text',
            'CredyApplicationForm[technologies]' => 'php,javascript',
            'CredyApplicationForm[vcsUri]' => 'http://www.google.com',
        ]);

        $I->dontSeeElement('#credy-application-form');
        $I->expectTo('See cURL failure error');
        $I->see('Error cURL Request Failed !');
        $I->see('The above error occurred while the Web server was processing your request');
    }
}
