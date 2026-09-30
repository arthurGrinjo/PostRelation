<?php

namespace ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\ApiResource\ActivityListItem;
use App\Factory\ActivityFactory;
use App\Factory\UserFactory;
use Symfony\Component\Uid\Uuid;
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
        UserFactory::createMany(5);
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
        $this->assertMatchesRegularExpression('#^/api/users/[0-9a-f-]{36}$#', $response->toArray()['member'][0]['user']);
        $this->assertMatchesResourceCollectionJsonSchema(ActivityListItem::class);
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
        ActivityFactory::createOne();

        /** Act */
        $item = $client
            ->request('GET', self::END_POINT)
            ->toArray()['member'][0]['@id']
        ;
        $client->request('GET', $item);

        /** Assert */
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/activity',
            '@id' => $item,
            '@type' => 'Activity',
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
            'name' => 'Nieuwe activiteit',
            'user' => '/api/users/' . $user->getUuid()->toString(),
        ]])->toArray();

        /** Assert */
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', self::RESPONSE_HEADER);
        $this->assertJsonContains([
            '@context' => '/api/contexts/activity',
            '@id' => $item['@id'],
            '@type' => 'Activity',
            'name' => 'Nieuwe activiteit',
            'user' => [
                '@id' => '/api/users/' . $user->getUuid(),
                '@type' => 'User',
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
            ]
        ]);
    }

    public function testCreateActivityWithUserIriPersistsRelation(): void
    {
        $user = UserFactory::createOne();
        $userIri = '/api/users/' . $user->getUuid()->toString();

        $response = static::createClient()->request('POST', self::END_POINT, ['json' => [
            'name' => 'Activity with IRI',
            'user' => $userIri,
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'name' => 'Activity with IRI',
            'user' => [
                '@id' => $userIri,
                'uuid' => $user->getUuid()->toString(),
            ],
        ]);

        $activity = ActivityFactory::repository()->findOneBy(['name' => 'Activity with IRI']);
        $this->assertNotNull($activity);
        $this->assertTrue($user->getUuid()->equals($activity->getUser()->getUuid()));
        $this->assertSame('/api/activities/' . $activity->getUuid()->toString(), $response->toArray()['@id']);
    }

    public function testCreateActivityWithUnknownUserIri(): void
    {
        static::createClient()->request('POST', self::END_POINT, ['json' => [
            'name' => 'Activity with unknown user',
            'user' => '/api/users/' . Uuid::v6()->toString(),
        ]]);

        $this->assertResponseStatusCodeSame(400);
        ActivityFactory::repository()->assert()->count(0);
    }
}
