<?php

namespace App\Form;

use App\Entity\DailyReport;
use App\Entity\Habit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
// ...
class HabitForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name')
            ->add('description')
            ->add('period')
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',  // makes it an <input type="date">
                'required' => false,
                'html5' => true,
                // optional: set format if needed
                //'format' => 'yyyy-MM-dd',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habit::class,
        ]);
    }
}
