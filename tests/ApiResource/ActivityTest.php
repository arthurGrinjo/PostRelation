<?php

namespace ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\ApiResource\Activity;
use App\Factory\ActivityFactory;
use App\Factory\UserFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class ActivityTest extends ApiTestCase
{
    private const string END_POINT = 'api/activities';
    private const string RESPONSE_HEADER = 'application/ld+json';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetActivityCollection(): void
    {
        /** Arrange */
        UserFactory::createOne();
        ActivityFactory::createMany(60);

        /** Act */
        $response = static::createClient()->request('GET', self::END_POINT);

        /** Assert */
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/activity',
            '@id' => '/api/activities',
            '@type' => 'Collection',
            'totalItems' => 60,
            'view' => [
                '@id' => '/api/activities?page=1',
                '@type' => 'PartialCollectionView',
                'first' => '/api/activities?page=1',
                'last' => '/api/activities?page=2',
                'next' => '/api/activities?page=2'
            ]
        ]);
        $this->assertCount(30, $response->toArray()['member']);
        $this->assertMatchesResourceCollectionJsonSchema(Activity::class);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetActivityItem(): void {
        $client = static::createClient();

        /** Arrange  */
        UserFactory::createOne();
        $activityIri = sprintf(
            '/%s/%s',
            self::END_POINT,
            ActivityFactory::createOne()->getUuid()->toString()
        );

        /** Act */
        $client->request('GET', $activityIri);

        /** Assert */
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/activity',
            '@id' => $activityIri,
            '@type' => 'activity',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateActivityItem(): void
    {
        /** Arrange */
        $user = UserFactory::createOne();

        /** Act */
        $item = static::createClient()->request('POST', self::END_POINT, ['json' => [
            'name' => 'New activity',
            'user' => '/api/users/' . $user->getUuid()->toString(),
        ]])->toArray();

        /** Assert */
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/activity',
            '@id' => $item['@id'],
            '@type' => 'activity',
            'name' => 'New activity',
            'user' => [
                '@id' => '/api/users/' . $user->getUuid(),
                '@type' => 'user',
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
            ]
        ]);
    }
}
