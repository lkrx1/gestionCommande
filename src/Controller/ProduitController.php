<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/produits/new", name="newProduit")
     * @Route("/produits/{id}/edit", name="editProduit")
     */
    public function form(Produit $produit=null,Request $request,ObjectManager $manager){
        if(!$produit){
            $produit = new Produit();
        }
        
        $formProduit = $this->createForm(ProduitType::class,$produit);

        $formProduit->handleRequest($request);
        if ($formProduit->isSubmitted() && $formProduit->isValid()) {
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('produits');
        }
        dump($produit);
        return $this->render('produit/create.html.twig',[
            'formProduit'=>$formProduit->createView(),
            'editMode'=>$produit->getId()!==null
        ]);
    }
     /**
     * @Route("/produits/{id}", name="showProduit")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repo->findBy(array('id'=>$id));
        return $this->render('produit/show.html.twig',[
            'produit'=>$produit,
        ]);
    }
    /**
     * @Route("/produits/delete/{id}", name="deleteProduit")
     */
    public function delete(Produit $produit,ObjectManager $manager){
        $manager->remove($produit);
        $manager->flush();
        return $this->redirectToRoute('produits');
    }
}
