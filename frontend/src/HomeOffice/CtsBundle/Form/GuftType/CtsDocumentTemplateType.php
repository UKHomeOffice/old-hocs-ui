<?php
namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsDocumentTemplateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsDocumentTemplateType extends AbstractType
{
    /**
     * @var string
     */
    protected $workspace;

    /**
     * @var string
     */
    protected $store;

    /**
     * Constructor
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCaseDocumentTemplate $template */
        $template = $builder->getData();

        $builder
            ->add('templateName', 'text')
            ->add('appliesToCorrespondenceType', 'choice', [
                'choices'     => CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes(),
                'label'       => 'Case type',
                'empty_value' => '',
                'attr'        => ['class' => 'chosen']
            ])
            ->add('validFromDate', 'date', [
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ])
            ->add('validToDate', 'date', [
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ]);

        if (is_null($template->getCreatedDate())) {
            $builder->add('file', 'file', [
                'label'      => 'Template file',
                'label_attr' => ['class' => ''],
                'attr'       => ['class' => 'document-upload-class hidden'],
            ]);
        }

        $builder->add('save', 'submit', [
            'label' => is_null($template->getCreatedDate()) ? 'Add Template' : 'Save Template',
            'attr'  => ['class' => 'button'],
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CtsCaseDocumentTemplate::class,
            'empty_data' => new CtsCaseDocumentTemplate($this->workspace, $this->store),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CtsDocumentTemplate';
    }
}
