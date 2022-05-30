<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Form\Type;

use Softify\SyliusImojePlugin\Api\ApiInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ImojeGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'environment',
                ChoiceType::class,
                [
                    'choices' => [
                        'softify.imoje_plugin.sandbox' => ApiInterface::ENVIRONMENT_SANDBOX,
                        'softify.imoje_plugin.production' => ApiInterface::ENVIRONMENT_PRODUCTION
                    ],
                    'label' => 'softify.imoje_plugin.environment',
                ]
            )
            ->add(
                'debug_mode',
                CheckboxType::class,
                [
                    'label' => 'softify.imoje_plugin.debug_mode',
                ]
            )
            ->add(
                'authorization_token',
                TextType::class,
                [
                    'label' => 'softify.imoje_plugin.authorization_token',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'softify.imoje_plugin.gateway_configuration.authorization_token.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'merchant_id',
                TextType::class,
                [
                    'label' => 'softify.imoje_plugin.merchant_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'softify.imoje_plugin.gateway_configuration.merchant_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'service_id',
                TextType::class,
                [
                    'label' => 'softify.imoje_plugin.service_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'softify.imoje_plugin.gateway_configuration.service_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'service_key',
                TextType::class,
                [
                    'label' => 'softify.imoje_plugin.service_key',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'softify.imoje_plugin.gateway_configuration.service_key.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            );
    }
}
