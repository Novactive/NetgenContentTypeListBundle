<?php
/**
 * @copyright Novactive
 * Date: 26/02/2020
 */

declare(strict_types=1);

namespace Netgen\Bundle\ContentTypeListBundle\Core\Form\Type\FieldType;


use Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\DataTransformer\ValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentTypeListFieldType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'ezplatform_fieldtype_ngclasslist';
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ValueTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'expanded'          => false,
                'choices_as_values' => true,
            ]
        );
    }
}
