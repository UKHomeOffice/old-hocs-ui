<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Consumer\EmailPreferences;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EmailPreferencesType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class EmailPreferencesType extends AbstractType
{
    /**
     * @var Person
     */
    protected $user;

    /**
     * @var ListHandler
     */
    protected $listHandler;

    /**
     * @var EmailPreferences
     */
    protected $emailPreferencesConsumer;

    /**
     * @var Unit[]
     */
    protected $units;

    /**
     * @var array
     */
    protected $userPreferences;

    /**
     * Constructor
     *
     * @param ListHandler      $listHandler
     * @param EmailPreferences $emailPreferencesConsumer
     */
    public function __construct(ListHandler $listHandler, EmailPreferences $emailPreferencesConsumer)
    {
        $this->listHandler = $listHandler;
        $this->emailPreferencesConsumer = $emailPreferencesConsumer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->units = $this->listHandler->getAllUnits();
        foreach ($this->units as $unit) {
            if ($this->user->isMemberOfUnit($unit, true)) {
                $builder->add($unit->getSlug(), 'EmailPreferencesChild', [
                    'required' => false,
                    'compound' => true,
                ]);

                $isMemberOfUnit = $this->user->isMemberOfUnit($unit);
                $builder->get($unit->getSlug())->add('unit', 'checkbox', [
                    'label'    => $unit->getDisplayName(),
                    'data'     => !$isMemberOfUnit ? false : $this->isActive($unit),
                    'disabled' => !$isMemberOfUnit,
                    'label_attr' => [
                        'class' => !$isMemberOfUnit ? 'disabled' : '',
                    ]
                ]);

                if (!empty($unit->getTeams())) {
                    $builder->get($unit->getSlug())->add('teams', 'EmailPreferencesChild', [
                        'compound' => true,
                    ]);
                    foreach ($unit->getTeams() as $team) {
                        if ($this->user->isMemberOfTeam($team)) {
                            $builder->get($unit->getSlug())->get('teams')->add($team->getSlug(), 'checkbox', [
                                'label' => $team->getDisplayName(),
                                'data'  => $this->isActive($team),
                            ]);
                        }
                    }
                }
            }
        }

        $builder->add('save', 'submit', [
            'label' => 'Update your email notifications',
            'attr' => [
                'class' => 'button',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'EmailPreferences';
    }

    /**
     * Set User
     *
     * @param Person $user
     *
     * @return $this
     */
    public function setUser(Person $user)
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @param Team|Unit $group
     *
     * @return bool
     */
    private function isActive($group)
    {
        if (is_null($this->userPreferences)) {
            $this->userPreferences = [];

            $userPreferences = $this->emailPreferencesConsumer->get();
            if (array_key_exists('groups', $userPreferences)) {
                foreach ($userPreferences['groups'] as $userPreference) {
                    $this->userPreferences[] = $userPreference['authorityName'];
                }
            }
        }

        return !in_array($group->getAuthorityName(), $this->userPreferences);
    }
}
