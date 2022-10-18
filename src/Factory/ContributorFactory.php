<?php

namespace App\Factory;

use App\Entity\Contributor;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Contributor>
 *
 * @method static Contributor|Proxy createOne(array $attributes = [])
 * @method static Contributor[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Contributor[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Contributor|Proxy find(object|array|mixed $criteria)
 * @method static Contributor|Proxy findOrCreate(array $attributes)
 * @method static Contributor|Proxy first(string $sortedField = 'id')
 * @method static Contributor|Proxy last(string $sortedField = 'id')
 * @method static Contributor|Proxy random(array $attributes = [])
 * @method static Contributor|Proxy randomOrCreate(array $attributes = [])
 * @method static Contributor[]|Proxy[] all()
 * @method static Contributor[]|Proxy[] findBy(array $attributes)
 * @method static Contributor[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Contributor[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Contributor|Proxy create(array|callable $attributes = [])
 */
final class ContributorFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'firstname' => self::faker()->firstName(),
            'lastname' => self::faker()->lastName(),
            'email' => self::faker()->unique()->email(),
            'password' => self::faker()->word(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Contributor $contributor): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Contributor::class;
    }
}
