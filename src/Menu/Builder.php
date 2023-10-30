<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Builder
{
    private $factory;
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'homepage'])->setAttribute('style', 'display:inline');
        $token = $this->tokenStorage->getToken();
        if($token){
            $user = $token->getUser();
            if($user && is_object($user)){
                if(in_array('ROLE_ADMIN', $user->getRoles())){
                    $menu->addChild('Admin', ['route' => 'admin'])->setAttribute('style', 'display:inline');
                }
                $menu->addChild('Logout', ['route' => 'app_logout'])->setAttribute('style', 'display:inline');
            }
        }else{
            $menu->addChild('Login', ['route' => 'app_login'])->setAttribute('style', 'display:inline');
            $menu->addChild('Sing Up', ['route' => 'app_register'])->setAttribute('style', 'display:inline');
        }
        return $menu;
    }
}