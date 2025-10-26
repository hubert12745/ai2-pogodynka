<?php
namespace App\Form;

use App\Entity\Measurement;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city', // dopasuj do tego, jak Location ma nazwane pole
                'label' => 'Lokalizacja',
                'placeholder' => '— wybierz —',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data pomiaru',
            ])
            ->add('celsius', NumberType::class, [
                'label' => 'Temperatura (°C)',
                'scale' => 0,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
