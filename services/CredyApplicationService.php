<?php

namespace app\services;

use app\components\http\HttpRequestInterface;
use Exception;
use Yii;

class CredyApplicationService implements CredyApplicationServiceInterface
{
    protected HttpRequestInterface $curlRequest;

    public function __construct(HttpRequestInterface $curlRequest)
    {
        $this->curlRequest = $curlRequest;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateJsonx(array $data): string
    {
        $firstName    = $data['application']['firstName'] ?? '';
        $lastName     = $data['application']['lastName'] ?? '';
        $email        = $data['application']['email'] ?? '';
        $bio          = $data['application']['bio'] ?? '';
        $technologies = explode(',', $data['application']['technologies']) ?? [];
        $vcsUri       = $data['application']['vcsUri'] ?? '';
        $timestamp    = $data['timestamp'] ?? '';
        $signature    = $data['signature'] ?? '';

        // Constructing the JSONx string
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <json:object xsi:schemaLocation=\"http://www.datapower.com/schemas/json jsonx.xsd\" 
                xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
                xmlns:json=\"http://www.ibm.com/xmlns/prod/2009/jsonx\">
            <json:string name=\"first_name\">$firstName</json:string>
            <json:string name=\"last_name\">$lastName</json:string>
            <json:string name=\"email\">$email</json:string>
            <json:string name=\"bio\">$bio</json:string>
            <json:array name=\"technologies\">";

        // Adding each technology to the JSONx string
        foreach ($technologies as $technology) {
            $result .= "\n    <json:string>$technology</json:string>";
        }

        // Adding the remaining JSONx
        $result .= "
            </json:array>
            <json:number name=\"timestamp\">$timestamp</json:number>
            <json:string name=\"signature\">$signature</json:string>
            <json:string name=\"vcs_uri\">$vcsUri</json:string>
        </json:object>";

        return $result;
    }

    /**
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function sendApplication(array $data): string
    {
        $jsonxString = $this->generateJsonx($data);

        try {
            $this->curlRequest->setOptionArray([
                CURLOPT_URL => Yii::$app->params['credyApiUri'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $jsonxString,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/xml',
                ],
            ]);

            $response = $this->curlRequest->execute();
            $httpCode = $this->curlRequest->getInfo(CURLINFO_RESPONSE_CODE);

            if ($response === false) {
                throw new Exception("cURL Request Failed !");
            }
            if ($httpCode < 200 || $httpCode >= 300) {
                throw new Exception("HTTP Error: $httpCode - $response");
            }

            return $response;
        } finally {
            $this->curlRequest->close();
        }
    }
}
