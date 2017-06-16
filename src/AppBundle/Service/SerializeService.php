<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializeService
{
    private $em;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function serializeObjects(array $entity, array $options = array())
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $normalizer->setCircularReferenceLimit(0);
        $normalizer->setIgnoredAttributes($options);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = new Response($serializer->serialize($entity, 'json'));
        $response->headers->set('Content-Type', 'application/vnd.api+json');
        return $response;
    }
}