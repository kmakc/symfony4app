<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email'    => 'john@gmail.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles'    => [User::ROLE_USER],
        ],
        [
            'username' => 'rob_smith',
            'email'    => 'rob@gmail.com',
            'password' => 'rob123',
            'fullName' => 'Rob Smith',
            'roles'    => [User::ROLE_USER],
        ],
        [
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => 'admin',
            'fullName' => 'admin admin',
            'roles'    => [User::ROLE_ADMIN],
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'Its nice weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'Theres a problem with my phone',
        'I need go to a doctor',
        'What are you up today?',
        'Yesterday all my trouble was so far away',
        'How was your day?'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference($userData['username'], $user);

            $manager->persist($user);
            $manager->flush();
        }
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[rand(0, count(self::POST_TEXT) -1)]
            );
            $date = new \DateTime();
            $date->modify('-' . rand(0, 100) . ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) -1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
