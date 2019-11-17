<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Entity\LigneCommande;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commandes", name="commandes")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Commande::class);
        $commandes = $repo->findAll();
        return $this->render('commande/index.html.twig',[
            'commandes'=>$commandes
        ]);      
    }
    /**
     * @Route("/commandes/new", name="newCommande")
     */
    public function create(Request $request){
        dump($request);
        return $this->render('commande/create.html.twig');
    }
    /**
     * @Route("/commandes/{id}", name="showCommande")
     */
    public function show($id){
        $repoCommandes = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $repoCommandes->find($id);

        $repoLigneCommande = $this->getDoctrine()->getRepository(LigneCommande::class);
        $lignesCommandes =$repoLigneCommande->findBy(array('commande' => $commande));

        return $this->render('commande/show.html.twig',[
            'commande' => $commande ,
            'ligneCommandes' => $lignesCommandes
        ]);
    }
    
}
