<?php
namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank(), new Email()]
            ])
            ->add('company', TextType::class, [
                'label' => 'Company / Organization',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
            ->add('jobTitle', TextType::class, [
                'label' => 'Job Title',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('mealPreference', ChoiceType::class, [
                'label' => 'Meal Preference',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Standard' => 'standard',
                    'Vegan' => 'vegan',
                    'Vegetarian' => 'vegetarian',
                    'Halal' => 'halal',
                    'Gluten-Free' => 'gluten-free',
                ],
                'constraints' => [new NotBlank()]
            ])
            ->add('needsParking', CheckboxType::class, [
                'label' => 'I need a parking spot',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('needsAccommodation', CheckboxType::class, [
                'label' => 'I need hotel accommodation info',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('newsletterConsent', CheckboxType::class, [
                'label' => 'I agree to receive the ISM newsletter',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('dataProtectionConsent', CheckboxType::class, [
                'label' => 'I accept the data protection policy *',
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [new IsTrue(['message' => 'You must accept the data protection policy.'])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Registration::class]);
    }
}