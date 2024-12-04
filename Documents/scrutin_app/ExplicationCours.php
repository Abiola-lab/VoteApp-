<?php
/** Dans  une classe on a des constantes, des variables et des fonction exemples  */
{
    const C = 100;
private float $nb; 
/** avant chaque fonction il faut commenter pour expliquer le role de la fonction */ */
public f(int$x): floatval
{
    $this->nb = $x ** 0.5;  
}
}
?> 

<?php 
/**Pour utiliser une classe il faut utiliser la commande use qui est dans l'espase. Dans notre contexte on peut dire use App\Condorcy\ClassA; (Pour faire ça \ faut faire option = maj = le slash existant)
Pour créer un objet d'une classe on peut faire : */
$a = new ClassA(); 

/** pour utiliser une fonction présent dans un autre objet on fait */
$result = $a->f(36); 


