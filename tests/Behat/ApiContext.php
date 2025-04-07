<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Hook\AfterScenario;
use Behat\Hook\BeforeScenario;
use Behat\Step\Then;
use Behat\Step\When;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

final class ApiContext implements Context
{
    const BASE_URL = 'http://localhost';
    private ResponseInterface $response;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[When('I send a :method request to :path')]
    public function iSentRequestTo(string $method, string $path): void
    {
        $this->response = $this->httpClient->request($method, self::BASE_URL . $path, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    #[When('I send a :method request to :path with body:')]
    public function iSentRequestToWithBody(string $method, string $path, PyStringNode $content): void
    {
        $payload = json_decode($content->getRaw(), true , 512, JSON_THROW_ON_ERROR);

        $this->response = $this->httpClient->request($method, self::BASE_URL . $path, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
    }

    #[Then('the response code should be :code')]
    public function theResponseCodeShouldBe(int $code): void
    {
        Assert::eq($this->response->getStatusCode(), $code);
    }

    #[Then('the response body should be:')]
    public function theResponseBodyShouldBe(PyStringNode $content): void
    {
        $expectedContentAsArray = json_decode($content->getRaw(), true, 512, JSON_THROW_ON_ERROR);
        $actualContentAsArray = json_decode($this->response->getContent(false), true, 512, JSON_THROW_ON_ERROR);

        DeepCompare::compare($actualContentAsArray, $expectedContentAsArray);
    }

    #[Then('I dump response')]
    public function iDumpResponse(): void
    {
        var_dump(json_encode(json_decode($this->response->getContent(false)), JSON_PRETTY_PRINT));
    }
}
