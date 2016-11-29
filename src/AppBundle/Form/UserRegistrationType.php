<?php

/**
 * Created by PhpStorm.
 * User: kaanburaksener
 * Date: 14/10/16
 * Time: 16:58
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class UserRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'     => false,
                'required'  => true,
                'attr'      => [
                    'class'       => 'input pass',
                    'placeholder' => 'Username'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'     => false,
                'required'  => true,
                'attr'      => [
                    'class'       => 'input pass',
                    'placeholder' => 'Email'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => false,
                    'attr'  => [
                        'class'       => 'input pass',
                        'placeholder' => 'Password'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr'  => [
                        'class'       => 'input pass',
                        'placeholder' => 'Repeat Password',
                        'onChange'    => 'checkPasswordMatch();'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'loh',
                'attr'  => [
                    'class' => 'input-button'
                ]
            ])
            ->add('sdfhsdf', ButtonType::class, [
                'label' => 'nb',

                'attr' => [
                    'class' => 'input-button'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}