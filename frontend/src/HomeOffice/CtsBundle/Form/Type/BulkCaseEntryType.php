<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Entity\BulkCaseEntry;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BulkCaseEntryType
 *
 * @package HomeOffice\CtsBundle\Form\Type
 */
class BulkCaseEntryType extends AbstractType
{
    /**
     * @var ListHandler
     */
    protected $listHandler;

    /**
     * @var array
     */
    protected $unitMap = array();

    /**
     * Constructor
     *
     * @param ListHandler $listHandler
     */
    public function __construct(ListHandler $listHandler)
    {
        $this->listHandler = $listHandler;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('correspondenceType', 'choice', [
                'choices'     => CaseCorrespondenceType::getCorrespondenceArrayWithSubTypesForBulkCreateCase(),
                'empty_value' => '',
                'label'       => 'Case type',
                'attr'        => ['class' => 'chosen', 'data-placeholder' => 'Select case type'],
            ])
            ->add('assignedUnit', 'choice', [
                'choices'     => $this->setupInitialUnitMapValues(),
                'empty_value' => '',
                'label'       => 'Unit',
                'attr'        => ['class' => 'chosen', 'data-placeholder' => 'Select a unit'],
            ])
            ->add('assignedTeam', 'choice', [
                'choices'     => [],
                'empty_value' => '',
                'label'       => 'Team',
                'attr'        => ['class' => 'chosen', 'data-placeholder' => 'Select a team'],
            ])
            ->add('assignedUser', 'choice', [
                'choices'     => [],
                'empty_value' => '',
                'label'       => 'User',
                'attr'        => ['class' => 'chosen', 'data-placeholder' => 'Select a user'],
            ])
            ->add('files', 'file', [
                'label'       => 'Original documents',
                'label_attr'  => ['class' => 'hidden'],
                'attr'        => ['class' => 'document-upload-class hidden'],
                'multiple'    => true
            ])
            ->add('UploadAndCreate', 'submit', [
                'label'       => 'Upload',
                'attr'        => ['class' => 'button hidden']
            ]);
    }


    /**
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => BulkCaseEntry::class,
            'empty_data'      => new BulkCaseEntry(),
            'csrf_protection' => false,
        ]);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsBulkCaseEntry';
    }

    /**
     * Setup the values for the unitMap array
     */
    public function setupInitialUnitMapValues()
    {
        foreach ($this->listHandler->getList('ctsUnitAndTeamList') as $unit) {
            /** @var Unit $unit */
            $this->unitMap[$unit->getAuthorityName()] = $unit->getDisplayName();
        }

        return $this->unitMap;
    }
}
