<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy random()
 * @method static User[]|Proxy[] randomSet(int $number)
 * @method static User[]|Proxy[] randomRange(int $min, int $max)
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create($attributes = [])
 * @method User[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->encoder = $encoder;
    }

    protected function getDefaults(): array
    {
        return [
            'firstname' => self::faker()->firstName,
            'lastname' => self::faker()->lastName,
            'email' => self::faker()->email,
            'password' => 'user',
            'createdAt' => self::faker()->dateTimeBetween('-3 years', 'now', 'Europe/Paris'),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            ->afterInstantiate(function(User $user) {
                $plainPassword = $user->getPassword();
                $hashedPassword = $this->encoder->encodePassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            })
            ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}