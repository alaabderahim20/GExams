<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class EntitySubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::postRemove,
            // Events::postUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEmptyInstance();

        if ($entity instanceof User) {
            $entity->setLocale('fr_FR');
        } else {
            // Uncomment and adjust the following lines if needed
            /*
            $user = $this->security->getUser();
            if (!$entity->getCreatedAt()) {
                $entity->setCreatedBy($user);
                $entity->setCreatedAt(new \DateTime());
            }
            $entity->setEditedBy($user);
            $entity->setEditedAt(new \DateTime());
            */
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }

    // Uncomment and implement postUpdate if needed
    /*
    public function postUpdate(PreUpdateEventArgs $event): void
    {
        $this->logActivity('postUpdate', $args);
    }
    */

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        // ... get the entity information and log it somehow
    }
}
