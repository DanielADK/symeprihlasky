<?php

namespace App\Form;

use App\Entity\Person;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add("name", TextType::class, [
                "label" => "Křestní jméno",
                "required" => true,
            ])
            ->add("surname", TextType::class, [
                "label" => "Příjmení",
                "required" => true,
            ])
            ->add("email", EmailType::class, [
                "label" => "Emailová adresa",
                "required" => true,
            ])
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Hesla se musí shodovat",
                "required" => true,
                "first_options" => ["label" => "Heslo"],
                "second_options" => ["label" => "Znovu heslo"],
                "mapped" => false,
                "constraints" => [
                    new NotBlank([
                        "message" => "Prosím, zadejte heslo",
                    ]),
                    new Length([
                        "min" => 6,
                        "minMessage" => "Vaše heslo musí být minimálně {{ limit }} znaků dlouhé.",
                        "max" => 4096,
                        "maxMessage" => "Vaše heslo je příliš dlouhé.",
                    ]),
                ],
            ])
            ->add("agreeTerms", CheckboxType::class, [
                "label" => "Souhlasím s podmínkami.",
                "mapped" => false,
                "constraints" => [
                    new IsTrue([
                        "message" => "Musíte souhlasit s podmínkami užití soukromých údajů.",
                    ])
                ],
                "required" => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "data_class" => Person::class
        ]);
    }

}