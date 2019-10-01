<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => false, 'attr' => ['placeholder' => 'Email']])
            ->add('password', PasswordType::class, ['label' => false, 'attr' => ['placeholder' => 'Password']])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn-success']]);
    }
}
