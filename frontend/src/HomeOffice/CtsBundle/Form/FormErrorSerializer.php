<?php

namespace HomeOffice\CtsBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\SubmitButton;

/**
 * Class FormErrorSerializer
 *
 * @package HomeOffice\CtsBundle\Form
 */
class FormErrorSerializer
{
    /**
     * @param Form   $form
     * @param bool   $flatArray
     * @param bool   $addFormName
     * @param string $glueKeys
     *
     * @return array
     */
    public function serializeFormErrors(Form $form, $flatArray = true, $addFormName = true, $glueKeys = '_')
    {
        $errors = [
            'global' => [],
            'fields' => [],
        ];

        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $error->getMessage();
        }

        $errors['fields'] = $this->serialize($form);
        if ($flatArray === true) {
            $errors['fields'] = $this->arrayFlatten(
                $errors['fields'],
                $glueKeys,
                $addFormName ? $form->getName() : ''
            );
        }

        return $errors;
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    private function serialize(Form $form)
    {
        $localErrors = [];

        /** @var Form $child */
        foreach ($form->getIterator() as $key => $child) {
            foreach ($child->getErrors(true) as $error) {
                $localErrors[$key] = $error->getMessage();
            }

            if ($child->count() > 0) {
                if (!$child instanceof SubmitButton) {
                    $childErrors = $this->serialize($child);
                    if (!empty($childErrors)) {
                        $localErrors[$key] = $childErrors;
                    }
                }
            }
        }

        return $localErrors;
    }

    /**
     * @param array  $array
     * @param string $separator
     * @param string $flattenedKey
     *
     * @return array
     */
    private function arrayFlatten(array $array, $separator = "_", $flattenedKey = '')
    {
        $flattenedArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $flattenedArray = array_merge($flattenedArray,
                    $this->arrayFlatten(
                        $value,
                        $separator,
                        (strlen($flattenedKey) > 0 ? $flattenedKey . $separator : '') . $key
                    )
                );
            } else {
                $flattenedArray[(strlen($flattenedKey) > 0 ? $flattenedKey . $separator : '') . $key] = $value;
            }
        }

        return $flattenedArray;
    }
}
