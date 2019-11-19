<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Client;
use App\Form\ClientType;

class ClientController extends AbstractController
{
    /**
     * @Route("/clients", name="clients")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Client::class);
        $clients=$repo->findAll();
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
        
    }
    /**
     * @Route("/clients/new", name="newClient")
     * @Route("/clients/{id}/edit", name="editClient")
     */
    public function form(Client $client=null,Request $request,ObjectManager $manager){
        if(!$client){
            $client = new Client();
        }
        $formClient = $this->createForm(ClientType::class,$client);
        $formClient->handleRequest($request);
        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $manager->persist($client);
            $manager->flush();
            return $this->redirectToRoute('clients');
        }
        dump($client);
        return $this->render('client/create.html.twig',[
            'formClient'=>$formClient->createView(),
            'editMode'=>$client->getId()!==null
        ]);
    }
    /**
     * @Route("/clients/{id}", name="showClient")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Client::class);
        $client = $repo->findBy(array('id'=>$id));
        return $this->render('client/show.html.twig',[
            'client'=>$client,
        ]);
    }
    /**
     * @Route("/clients/delete/{id}", name="deleteClient")
     */
    public function delete(Client $client,ObjectManager $manager){
        $manager->remove($client);
        $manager->flush();
        return $this->redirectToRoute('clients');
    }
}
