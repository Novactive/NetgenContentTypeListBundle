<?php
/**
 * @copyright Novactive
 * Date: 26/02/2020
 */

declare(strict_types=1);

namespace Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\DataTransformer;

use Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Value;
use Symfony\Component\Form\DataTransformerInterface;

class ValueTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }

        if ($value->identifiers === []) {
            return null;
        }

        return $value->identifiers;
    }

    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        return new Value($value);
    }
}
