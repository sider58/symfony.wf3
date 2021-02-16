<?php

namespace App\Form;

use App\Controller\ForgottenMdpController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_mdp', PasswordType::class, [
                'required' => 'true', 'label' => 'Entrez votre nouveau mot de passe'
            ])
            ->add('confirm.new_mdp', PasswordType::class, [
                'required' => 'true', 'label' => 'Confirmez votre nouveau mot de passe'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data class' => ForgottenMdpController::class,
        ]);
    }
}
