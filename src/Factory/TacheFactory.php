<?php

namespace App\Factory;

use App\Entity\Tache;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Tache>
 */
final class TacheFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Tache::class;
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
            'titre' => self::faker()->words(3, true),
            'description' => self::faker()->optional(0.7)->paragraph(), // 70% de chance d'avoir une description
            'date' => self::faker()->optional(0.5)->dateTimeBetween('now', '+1 month'), // 50% de chance d'avoir une date
            'statut' => self::faker()->randomElement(['A_FAIRE', 'EN_COURS', 'TERMINE']),
            
            // RELATIONS : Foundry va chercher un objet existant ou en créer un nouveau
            'projet' => ProjetFactory::random()  , 
            // 'employe' => self::faker()->boolean(70) ? EmployeFactory::random() : null,
            'employe' => null, // On laisse la tâche sans employé assigné par défaut
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Tache $tache): void {
                $projet = $tache->getProjet();
                
                // On récupère la liste des employés liés à ce projet
                $employesDuProjet = $projet->getEmployes();

                // Si le projet a des employés, on en choisit un au hasard (70% de chance)
                if (!$employesDuProjet->isEmpty() && self::faker()->boolean(70)) {
                    // On transforme la Collection en tableau pour utiliser randomElement
                    $randomEmploye = self::faker()->randomElement($employesDuProjet->toArray());
                    $tache->setEmploye($randomEmploye);
                }
            })
        ;
    }
}
