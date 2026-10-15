<?php

namespace ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\ApiResource\User;
use App\Factory\UserFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class UserTest extends ApiTestCase
{
    private const string END_POINT = 'api/users';
    private const string RESPONSE_HEADER = 'application/ld+json';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetUserCollection(): void
    {
        /** Arrange */
        UserFactory::createMany(5);

        /** Act */
        $response = static::createClient()->request('GET', self::END_POINT);

        /** Assert */
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/user',
            '@id' => '/api/users',
            '@type' => 'Collection',
            'totalItems' => 5,
        ]);
        $this->assertCount(5, $response->toArray()['member']);
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetUserItem(): void {
        $client = static::createClient();

        /** Arrange  */
        $userIri = sprintf('/%s/%s', self::END_POINT, UserFactory::createOne()->getUuid()->toString());

        /** Act */
        $client->request('GET', $userIri);

        /** Assert */
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/user',
            '@id' => $userIri,
            '@type' => 'user',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateUserItem(): void
    {
        /** Arrange/Act */
        $user = static::createClient()->request('POST', self::END_POINT, ['json' => [
            'email' => 'new_user@domain.com',
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'password' => 'Test123!',
        ]])->toArray();

        /** Assert */
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/user',
            '@type' => 'user',
            'email' => 'new_user@domain.com',
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
        ]);
    }
}
