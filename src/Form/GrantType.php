<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class GrantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client_id', HiddenType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('response_type', HiddenType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Choice([
                        'choices' => [
                            'code',
                            'token',
                        ],
                        'message' => 'Available response_type are either "code" or "token".'
                    ])
                ]
            ])
            ->add('authorize', SubmitType::class)
        ;
    }
}
