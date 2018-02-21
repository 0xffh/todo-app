<?php

namespace AppBundle\Util;

use Symfony\Component\Form\FormInterface;

class Helper
{
    /**
     * Symfony form errors formatter
     *
     * @param FormInterface $form
     *
     * @return array
     */
    public static function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = self::getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}