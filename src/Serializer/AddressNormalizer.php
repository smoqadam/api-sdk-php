<?php

namespace eLife\ApiSdk\Serializer;

use eLife\ApiSdk\Model\Address;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AddressNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = []) : Address
    {
        return new Address($data['formatted'],
            $data['components']['streetAddress'] ?? [],
            $data['components']['locality'] ?? [], $data['components']['area'] ?? [],
            $data['components']['country'] ?? null,
            $data['components']['postalCode'] ?? null);
    }

    public function supportsDenormalization($data, $type, $format = null) : bool
    {
        return Address::class === $type;
    }

    /**
     * @param Address $object
     */
    public function normalize($object, $format = null, array $context = []) : array
    {
        $data = [
            'formatted' => $object->getFormatted(),
            'components' => [],
        ];

        if ($object->getStreetAddress()) {
            $data['components']['streetAddress'] = $object->getStreetAddress();
        }

        if ($object->getLocality()) {
            $data['components']['locality'] = $object->getLocality();
        }

        if ($object->getArea()) {
            $data['components']['area'] = $object->getArea();
        }

        if ($object->getCountry()) {
            $data['components']['country'] = $object->getCountry();
        }

        if ($object->getPostalCode()) {
            $data['components']['postalCode'] = $object->getPostalCode();
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null) : bool
    {
        return $data instanceof Address;
    }
}
