<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AppBundle\Entity\Product;
use AppBundle\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user->setEmail('admin@admin.lt');
        $user->setUsername('admin');
        $user->setPlainPassword('admin');
        $user->addRole('ROLE_SUPER_ADMIN');
        $user->setEnabled(true);

        $manager->persist($user);

        $products = [];
        
        for ($i = 0; $i < 54; $i++) {
            $product = new Product();
            $product->setName($this->generateRandomName());
            $product->setPrice(rand(1, 100) / 100 * rand(50, 100));
            $product->setImage('img' . ($i + 1) . '.jpg');
            $manager->persist($product);
            
            $products[] = $product;
        }

        for ($i = 0; $i < 100; $i++) {
            $total = 0;
            
            $order = new Order();
            $order->setEmail($this->generateRandomEmail());
            $order->setName($this->generateRandomName());
            $order->setComment('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
            $order->setAddress($this->generateRandomAddress());
            $order->setPhone(implode('-',str_split(rand(100000000,999999999),3)));
            
            for($j = 0; $j < rand(1, 5); $j++) {
                $product = $products[rand(0, count($products) - 1)];
                $order->addProduct($product);
                $total += $product->getPrice();
            }
            
            $order->setTotal($total);
            
            $manager->persist($order);
        }

        $manager->flush();
    }

    static function generateRandomName()
    {
        $names = ['Voya-Fresh', 'Unizap', 'Yearstring', 'Via Dontex', 'Tip-Lam', 'Vilatom', 'Domtax', 'Eco Zozkix', 'Xxx-fresh', 'Tempis', 'Sankeysoft', 'Dento Hotlex', 'K-zap', 'Cof Hotron', 'Jay Home', 'Indigodox', 'Dandox', 'Tintip', 'Indigosoft', 'Volcancom', 'Contouch', 'Quotetouch', 'Lam Quotech', 'Kan Gotax', 'Whiteeco'];

        return $names[rand(0, count($names) - 1)];
    }

    static function generateRandomEmail()
    {
        $emails = ['mthurn@live.com', 'fangorn@hotmail.com', 'euice@outlook.com', 'rgarcia@optonline.net', 'mxiao@yahoo.com', 'firstpr@att.net', 'webdragon@comcast.net', 'jguyer@aol.com', 'sakusha@yahoo.ca', 'crandall@sbcglobal.net', 'drezet@me.com', 'miyop@icloud.com'];
        
        return $emails[rand(0, count($emails) - 1)];
    }
    
    static function generateRandomAddress() {
        $streets = ['Vilniaus g.', 'Kauno g.', 'Gedimino pr.', 'Laisvės pr.', 'Savanorių pr.', 'Čiurliono g.'];
        $cities = ['Vilnius', 'Kaunas', 'Klaipėda', 'Panevėžys', 'Šiauliai'];
        
        return $streets[rand(0, count($streets) - 1)] . ' ' . rand(1, 80) . '-' . rand(1, 20) . ', ' . $cities[rand(0, count($cities) - 1)];
    }

}
