<?php

namespace App\Form;

use App\Entity\Culture;
use App\Entity\Recolte;
use App\Repository\CultureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecolteType
 * @package App\Form
 */
class RecolteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', null, ['widget' => 'single_text'])
            ->add('culture', EntityType::class, [
                'class' => Culture::class,
                'choice_label' => 'libelle',
                'query_builder' => function (CultureRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.libelle', 'ASC');
                },
                'placeholder' => ''
            ])
            ->add('poids')
            ->add('commentaire')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Recolte::class]);
    }
}
