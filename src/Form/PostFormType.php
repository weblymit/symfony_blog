<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form',
                    'placeholder' => 'Titre du post...'
                ],
                'label' => "Titre de l'article",
                // 'required' => false
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form',
                    'placeholder' => 'Titre du post...'
                ],
                // 'required' => false
            ])
            ->add('category', TextType::class, [
                'attr' => [
                    'class' => 'form',
                    'placeholder' => 'Titre du post...'
                ],
                // 'required' => false

            ])
            // ->add('url_img', FileType::class, [
            //     'attr' => [
            //         'class' => 'formd',
            //         'placeholder' => 'Titre du post...'
            //     ],
            // ])
            ->add('author', TextType::class, [
                'attr' => [
                    'class' => 'form',
                    'placeholder' => 'Titre du post...'
                ],
                // 'required' => false

            ])
            ->add('url_img', FileType::class, [
                // 'required' => false,
                'mapped' => false, //mapped => ne pas associer se champs a Entity manager
                'attr' => [
                    'class' => 'formd',
                    'placeholder' => 'Titre du post...'
                ],
            ]);
        // ->add('created_at')
        // ->add('updated_at')
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
