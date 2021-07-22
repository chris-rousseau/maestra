<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        // dd($user);

        if (!$user instanceof UserInterface) {
            return;
        }

        /** @var User $user */
        $data['data'] = array(
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
        );

        $event->setData($data);
    }
}
