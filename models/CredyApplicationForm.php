<?php

namespace app\models;

use yii\base\Model;

class CredyApplicationForm extends Model
{
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $bio = '';
    public string $technologies = '';
    public string $vcsUri = '';

    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'email', 'bio', 'technologies', 'vcsUri'], 'trim'],
            [['firstName', 'lastName', 'email', 'bio', 'technologies', 'vcsUri'], 'required'],
            ['firstName', 'string', 'max' => 255],
            ['lastName', 'string', 'max' => 255],
            ['email', 'email'],
            ['bio', 'string', 'max' => 1000],
            ['technologies', 'validateTechnologies'],
            ['technologies', 'string', 'max' => 255],
            ['vcsUri', 'url'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateTechnologies($attribute, $params): void
    {
        $technologies = $this->convertToTrimmedValuesArray($this->$attribute);

        foreach ($technologies as $technology) {
            if (!is_string($technology) || strlen($technology) > 20) {
                $this->addError(
                    $attribute,
                    'Each technology has to be a string and not longer than 20 characters.'
                );
                return;
            }
        }

        $this->$attribute = implode(',', $technologies); // Re-implode the array to a CSV string
    }

    /**
     * Convert imploded string to an array, remove empty values
     *
     * @param string $imploded
     * @return array
     */
    protected function convertToTrimmedValuesArray(string $imploded): array
    {
        $arr = array_filter(explode(',', $imploded), function ($value) {
            return !empty(trim($value));
        });

        return array_map('trim', $arr);
    }
}
