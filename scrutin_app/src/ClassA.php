<?php
namespace Akobiabiolaabdbarr\ScrutinApp;
//class => enveloppe/isole les fonctions, variables, constantes => Comme le système de librairies dans app scripts

class ClassA
{
    const C = 100;
/**
 * @var float $nb Un nombre dont j'ai besoin dans mon app      
 */
    private float $nb;
/**

 * Une fonction qui calcule la racine carré d'un nombre    
 * 
 * @param int $x Un nombre             
 * 
 * @return float
 */
    public function f(int $x): float
    {
        $this->nb = $x ** 0.5;

        return $this->nb;
    }   
}

public function get Nb(): float   
{
    return $this->nb;
}








