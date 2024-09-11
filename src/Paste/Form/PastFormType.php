<?php
declare(strict_types=1);

namespace App\Paste\Form;

use App\Paste\Entity\Access;
use App\Paste\Entity\ExpirationTime;
use App\Paste\Entity\Language;
use App\Paste\Entity\Paste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PastFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('text')
            ->add('expirationTime', EnumType::class , [
                'class' => ExpirationTime::class
            ])
            ->add('access', EnumType::class, [
                'class' => Access::class
            ])
            ->add('language', EnumType::class, [
                'class' => Language::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paste::class,
        ]);
    }
}
