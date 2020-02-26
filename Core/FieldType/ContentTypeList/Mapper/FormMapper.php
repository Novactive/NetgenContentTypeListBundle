<?php
/**
 * @copyright Novactive
 * Date: 26/02/2020
 */

declare(strict_types=1);

namespace Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Mapper;


use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Netgen\Bundle\ContentTypeListBundle\Core\Form\Type\FieldType\ContentTypeListFieldType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
{
    /** @var ContentTypeService */
    protected $contentTypeService;

    /**
     * FormMapper constructor.
     *
     * @param ContentTypeService $contentTypeService
     */
    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data)
    {

    }

    /**
     * @inheritDoc
     */
    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data)
    {
        $fieldDefinition = $data->fieldDefinition;
        $formConfig = $fieldForm->getConfig();
        $languageCode = $fieldForm->getConfig()->getOption('languageCode');

        $choices = [];
        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();
        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypeGroupChoices = [];
            $contentTypes = $this->contentTypeService->loadContentTypes($contentTypeGroup);
            foreach ($contentTypes as $contentType) {
                $contentTypeGroupChoices[$contentType->getName($languageCode)] = $contentType->identifier;
            }
            ksort($contentTypeGroupChoices);
            $choices[$contentTypeGroup->identifier] = $contentTypeGroupChoices;
        }
        ksort($choices);

        $fieldForm
            ->add(
                $formConfig->getFormFactory()->createBuilder()
                    ->create(
                        'value',
                        ContentTypeListFieldType::class,
                        [
                            'required' => $fieldDefinition->isRequired,
                            'label'    => $fieldDefinition->getName(),
                            'multiple' => true,
                            'choices'  => $choices,
                        ]
                    )
                    ->setAutoInitialize(false)
                    ->getForm()
            );

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'translation_domain' => 'ezrepoforms_content_type',
                ]
            );
    }
}
