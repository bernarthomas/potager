<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ServiceParent
 * @package App\Service
 */
class ServiceParent
{
    /** @var ContainerInterface  */
    protected $container;

    /** @var EntityManagerInterface  */
    protected $entityManager;

    /** @var TokenStorageInterface  */
    protected $tokenStorage;

    /**
     * ParentAdmin constructor.
     *
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }
}