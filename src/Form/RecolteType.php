<?php

namespace App\Form;

use App\Entity\Culture;
use App\Entity\Recolte;
use App\Repository\CultureRepository;
use App\Repository\RecolteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \DateTime;

/**
 * Class RecolteType
 * @package App\Form
 */
class RecolteType extends AbstractType
{
    /** @var RecolteRepository */
    private $repositoryRecolte;

    /**
     * RecolteType constructor.
     *
     * @param RecolteRepository $repositoryRecolte
     */
    public function __construct(RecolteRepository $repositoryRecolte)
    {
        $this->repositoryRecolte = $repositoryRecolte;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, ['widget' => 'single_text'])
            ->add('culture', EntityType::class, [
                'class' => Culture::class,
                'choice_label' => 'libelle',
                'query_builder' => function (CultureRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.libelle', 'ASC');
                },
                'placeholder' => ''
            ])
            ->add('poids')
            ->add('prixPaye')
            ->add('commentaire')
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event)  {
                    $data = $event->getData();
                    $dateLastId = $this->repositoryRecolte->findDateLastId();
                    $date = new DateTime($dateLastId);
                    if (empty($date)) {
                        $date = new DateTime();
                    }
                    $data->setDate($date);

                }
            )
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
