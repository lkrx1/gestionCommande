<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommandeType;
use App\Form\LigneCommandeType;
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
     * @Route("/commandes/{id}/edit", name="editCommande")
     */
    public function form(Commande $commande=null,Request $request,ObjectManager $manager){
        if(!$commande){
            $commande = new Commande();
        }
        
        $formCommande = $this->createForm(CommandeType::class,$commande);

        $formCommande->handleRequest($request);
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            $manager->persist($commande);
            $manager->flush();
            return $this->redirectToRoute('commandes');
        }
        dump($commande);
        return $this->render('commande/create.html.twig',[
            'formCommande'=>$formCommande->createView(),
            'editMode'=>$commande->getId()!==null
        ]);
    }

     /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($issue)
    {
        if (null === $issue) {
            return '';
        }

        return $issue->getId();
    }

    /**
     * @Route("/commandes/{id}/add", name="addProduit")
     */
    public function addProduit(Commande $commande,Request $request,ObjectManager $manager){
        $ligneCommande = new LigneCommande();
        $formLigneCommande = $this->createForm(LigneCommandeType::class,$ligneCommande);
        $formLigneCommande->handleRequest($request);
        if ($formLigneCommande->isSubmitted() && $formLigneCommande->isValid()) {
            $ligneCommande->setCommande($this->transform($commande));
            $manager->persist($ligneCommande);
            $manager->flush();
            return $this->redirectToRoute('showCommande',array('id' => $commande));
        }
        return $this->render('commande/add.html.twig',[
            'formLigneCommande'=>$formLigneCommande->createView(),
            'commande' => $commande
        ]);
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
