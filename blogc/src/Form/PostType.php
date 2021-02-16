<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article'
            ])
            ->add('imageFile', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'constrains' => [
                    new Image([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'Le fichier ne peut excéder {{ limit }}'
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function(CategoryRepository $categoryRepository) {

                    return $categoryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');

                }
            ])
        ;
    }

    public function configurationOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data class' => Post::class,
        ]);
    }
}