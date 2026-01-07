<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Subscriber that checks if user has API access enabled before generating JWT token.
 */
class ApiAccessSubscriber implements EventSubscriberInterface
{
    /**
     * Returns the subscribed events
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
        ];
    }

    /**
     * Checks API access permission when JWT token is being created
     * Throws 403 is user disabled API access
     *
     * @param JWTCreatedEvent $event
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User || !$user->isApiAccess()) {
            throw new AccessDeniedHttpException('Accès API non activé');
        }
    }
}
