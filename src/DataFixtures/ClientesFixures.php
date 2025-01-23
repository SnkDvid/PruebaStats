<?php

namespace App\DataFixtures;

use App\Entity\Clientes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class ClientesFixures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_ES');

        for ($i = 0; $i < 20; $i++) {
            $cliente = new Clientes();
            $cliente->setNombre($faker->name);
            $cliente->setDireccion($faker->address);
            $cliente->setCorreo($faker->email);
            $cliente->setCelular($faker->phoneNumber);
            $cliente->setCivil($faker->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']));
            $cliente->setEstado($faker->boolean(60));

            $manager->persist($cliente);
        }

        $manager->flush();
    }
}
