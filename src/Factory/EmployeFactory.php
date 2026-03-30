<?php

namespace App\Factory;

use App\Entity\Employe;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Employe>
 */
final class EmployeFactory extends PersistentProxyObjectFactory
{

    private UserPasswordHasherInterface $passwordHasher;
    private GoogleAuthenticatorInterface $googleAuthenticator;

    public function __construct(UserPasswordHasherInterface $passwordHasher, GoogleAuthenticatorInterface $googleAuthenticator)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->googleAuthenticator = $googleAuthenticator;
    }

    #[\Override]
    public static function class(): string
    {
        return Employe::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'date_entree' => self::faker()->dateTimeBetween('-10 years', 'now'),
            'email' => self::faker()->unique()->safeEmail(),
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'statut' => self::faker()->randomElement(['CDI', 'CDD', 'Freelance', 'Stagiaire']),
            // On définit un mot de passe par défaut en clair ici
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'googleAuthenticatorSecret' => $this->googleAuthenticator->generateSecret(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Employe $employe): void {
                // On récupère le mot de passe en clair et on le hache
                $plainPassword = $employe->getPassword();
                $employe->setPassword($this->passwordHasher->hashPassword($employe, $plainPassword));
            })
        ;
    }
}
