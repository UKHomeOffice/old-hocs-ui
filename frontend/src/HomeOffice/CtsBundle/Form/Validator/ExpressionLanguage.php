<?php

namespace HomeOffice\CtsBundle\Form\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;

/**
 * Class ExpressionLanguage
 *
 * @package HomeOffice\CtsBundle\Form\Validator
 */
class ExpressionLanguage extends BaseExpressionLanguage
{
    /**
     * @inheritdoc
     */
    protected function registerFunctions()
    {
        parent::registerFunctions();

        $this->register('notBlank', function ($value) {
            return sprintf('%s !== null)', $value);
        }, function (array $arguments, $value) {
            return !is_null($value);
        });

        $this->register('notEmpty', function ($value) {
            return sprintf('count(%s) !== 0)', $value);
        }, function (array $arguments, $value) {
            return !empty($value);
        });
    }
}
