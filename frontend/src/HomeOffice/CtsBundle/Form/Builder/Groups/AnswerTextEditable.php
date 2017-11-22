<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AnswerTextEdit
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait AnswerTextEditable
{
    use Elements\AnswerText;
    use Elements\EditAnswer;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function answerTextEditable(FormBuilderInterface $builder)
    {
        $this
            ->answerText($builder)
            ->editAnswer($builder);

        return $this;
    }
}
