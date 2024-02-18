<?php

namespace tests\unit\models;

use app\models\CredyApplicationForm;

class CredyApplicationFormTest extends \Codeception\Test\Unit
{
    public function testValidModel(): void
    {
        $model = new CredyApplicationForm();
        $model->firstName = 'John';
        $model->lastName = 'Doe';
        $model->email = 'fake@example.com';
        $model->bio = 'i dont like biology';
        $model->technologies = '   PHP, JavaScript   ,,,,   ';
        $model->vcsUri = 'https://github.com/fake';

        $this->assertTrue($model->validate());
    }

    public function testTechnologiesValidationAndFormatting(): void
    {
        $model = new CredyApplicationForm();
        $model->technologies = '   PHP,,, JavaScript   ,,,,   ';

        $model->validateTechnologies('technologies', []);

        $this->assertEquals('PHP,JavaScript', $model->technologies);
    }

    public function testTechnologiesValidationSingleTechnologyTooError(): void
    {
        $model = new CredyApplicationForm();
        $model->technologies = '   PHP,,, JavaSsdfsdfsdf-too-long-sdfsdfdfcript   ,,,,   ';

        $model->validateTechnologies('technologies', []);

        $this->assertTrue($model->hasErrors('technologies'));
        $this->assertContains(
            'Each technology has to be a string and not longer than 20 characters.',
            $model->getErrors('technologies')
        );
        $this->assertEquals('   PHP,,, JavaSsdfsdfsdf-too-long-sdfsdfdfcript   ,,,,   ', $model->technologies);
    }
}
