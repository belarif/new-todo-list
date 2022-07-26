<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
            ])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
	        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
		        /** @var User $user */
		        $user = $event->getData();
		        $form = $event->getForm();
		        $formOptions = [
			        'class' => Role::class,
			        'multiple' => true,
			        'choice_label' => 'roleName',
			        'query_builder' => function (RoleRepository $repository) use ($user) {
				       if ($user) {
						   $roles = $user->getRoles();
				       } else {
						   $roles = array_map(function (Role $role) {
							   return $role->getRoleName();
						   }, $repository->findAll());
				       }
ª
				        $qb = $repository->createQueryBuilder('r');
				        $expr = $qb->expr();
				        return $qb
					        ->select('r')
					        ->where($expr->in('r.roleName', $roles));
			        },
		        ];

		        // create the field, this is similar the $builder->add()
		        // field name, field type, field options
		        $form->add('roles', EntityType::class, $formOptions);
	        })
            // ->add('roles', EntityType::class, [
            //         'class' => Role::class,
            //         'multiple' => true,
            //         'choice_label' => 'roleName',
            //     ]
            // )
        ;
    }
}

