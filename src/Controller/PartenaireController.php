<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Compts;
use App\Entity\Depots;
use App\Entity\Envoie;
use App\Entity\Tarifs;
use App\Entity\Retrait;
use App\Form\ComptType;
use App\Form\DepotType;
use App\Form\EnvoiType;
use App\Form\RetraiType;
use App\Form\RetraitType;
use App\Entity\Partenaire;
use App\Form\BlocPartType;
use App\Entity\Transaction;
use App\Form\ComptuserType;
use App\Form\TransactionType;
use App\Repository\UserRepository;

use App\Repository\ComptsRepository;
use App\Repository\DepotsRepository;
use App\Repository\RetraitRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */

class PartenaireController extends AbstractController
{



    /**
     * @Route("/ajoutcompt", name="compt", methods={"POST","GET"})
     * @IsGranted("ROLE_SUPERADMIN")
     
     */

    //-----------AjoutCompt--------------///////////
    public function ajoutcompt(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $compt = new Compts();
        $form = $this->createForm(ComptType::class, $compt);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);
        // Enregistrons les informations de date dans des variables

        $jours = date('d');
        $mois = date('m');
        $annee = date('Y');

        $heure = date('H');
        $minute = date('i');
        $seconde = date('s');
        $test = $jours . $mois . $annee . $heure . $minute . $seconde;
        $compt->setNumcompt($test);
        $compt->setSolde(0);

        $repo = $this->getDoctrine()->getRepository(Partenaire::class);
        $partenaires = $repo->find($values['partenaire']);


        $compt->setPartenaire($partenaires);


        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($compt);
        $entityManager->flush();
        $data = [
            'statut' => 201,
            'Messages' => 'compte creer'
        ];

        return new JsonResponse($data, 201);
    }

    //---------------Faire un dépots--------------------//////

    /**
     * @Route("/depot", name="depot", methods={"POST"})
     * @IsGranted("ROLE_CAISSIER")
     * 
     */
    public function depot(Request $request, EntityManagerInterface $entityManager): Response
    {

        $depot = new Depots();
        $compte = new Compts();
      
         $values = $request->request->all();
       
        $depot->setDateDepot(new \DateTime());
        $repo = $this->getDoctrine()->getRepository(Compts::class);

        $compt = $repo->findOneBy(['numcompt' => $values['numcompt']]);
      //Si var_dump ne march pas  return new JsonResponse( $compt->getId());

      $compt->getId();




if($values['montant'] >= 75000 && $compt) {
$depot->setSoldeInitial($compt->getSolde());
$compt->setSolde($values['montant']+$compt->getSolde());
$depot->setMontant($values['montant']);

             $depot->setCompt($compt);

            // $depot->setMontant($values['montant']);
             $user = $this->getUser();

             $depot->setCaissier($user);

            // $depot->setSoldeInitial($compt->getSolde());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depot);

            $entityManager->flush();
            $data = [
                'statut' => 201,
                'messages' => 'Depot effectuer avec succées'
            ];

            return new JsonResponse($data, 201);
        } else {
            $err = [
                'statut' => 500,
                'messages' => 'veillez saisir un montant superieur ou egal a 75000'
            ];

            return new JsonResponse($err, 500);
        }
    }

    //---------Bloquer Debloquer partenaire----------///

    /**
     * @Route("/partbloquer/{id}", name="partBlock", methods={"PUT","GET"})
     * @IsGranted("ROLE_SUPERADMIN")

     */

    public function partBloquer(Request $request, EntityManagerInterface $entityManager, Partenaire $part): Response
    {

        //$values = $request->request->all(); //si form
        //$part = new Partenaire();
        /* $form = $this->createForm(BlocPartType::class, $part);
        $form->handleRequest($request);

        $form->submit($values); */


        //$part = $entityManager->getRepository(Partenaire::class)->find($user->getId());

        if ($part->getStatus() == "Activer") {
            $part->setStatus("bloquer");
            $entityManager->flush();
            $data = [
                'statu' => 200,
                'Message' => 'partenaire bloquer'
            ];
            
        } else {
            $part->setStatus("Activer");
            $entityManager->flush();
            $data = [
                'statu' => 200,
                'Message' => 'partenaire debloquer'
            ];
        }
        return new JsonResponse($data);
    }
    /**
     *@Route("/envoi", name="envoi", methods={"POST"})
     *@IsGranted({"ROLE_USER", "ROLE_ADMIN"})
     
     */

    public function envoie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $values = $request->request->all();

      
        $envoie = new Envoie();

        $form1 = $this->createForm(EnvoiType::class, $envoie);
        $form1->handleRequest($request);

        $form1->submit($values);


        $retrait = new Retrait();

        $form3 = $this->createForm(RetraitType::class, $retrait);

        $form3->handleRequest($request);

        $form3->submit($values);

        $trans = new Transaction();

        $form = $this->createForm(TransactionType::class, $trans);

        $form->handleRequest($request);

        $form->submit($values);

        $mois = date('m');
        $annee = date('Y');
        $seconde = date('s');

        $code = $mois . $annee . $seconde;

        $argent = $form->get('montant')->getData();

        $tarif = $this->getDoctrine()->getRepository(Tarifs::class)->findAll();

        foreach ($tarif as $values) {
            $values->getBorneInferieure();
            $values->getBorneSuperieure();
            $values->getValeur();
            if (
                $argent >= $values->getBorneInferieure()
                && $argent <= $values->getBorneSuperieure()
            ) {


                $trans->setTarif($values);
                $commission = $values->getValeur();

                $commi1 = ($commission * 10) / 100;
                $commi2 = ($commission * 20) / 100;
                $commi3 = ($commission * 30) / 100;
                $commi4 = ($commission * 40) / 100;
            }
        }

        $trans->setCommissionEnvoie($commi1);
        $trans->setCommissionRetrait($commi2);
        $trans->setCommissionEtat($commi3);
        $trans->setCommissionAdmin($commi4);


        $trans->setCodeEnvoie($code);
        $trans->setDateEnvoie(new \DateTime());
        $user = $this->getUser();
        $trans->setUser($user);
        $trans->setEnvoie($envoie);
        $trans->setRetrait($retrait);

        $trans->setStatus("Disponible");


        $compt = $this->getDoctrine()->getRepository(Compts::class)->findOneBy(['partenaire' => $user->getPartenaire()]);



        if ($compt->getSolde() > $trans->getMontant()) {
            $montantcal = $compt->getSolde() - $trans->getMontant() + $commi1;


            $compt->setSolde($montantcal);

            $entityManager->persist($trans);
            $entityManager->persist($envoie);
            $entityManager->persist($retrait);
            $entityManager->persist($compt);

            $entityManager->flush();
            $data = [
                'status' => 200,
                'message' =>  'Bienvenue chez wari: '.$envoie->getPrenomEnvoyeur(). 
                ' '.$envoie->getNomEnvoyeur(). '  vous à transfert: '.$trans->getMontant().'  Voici le code : '.$trans->getCodeEnvoie()
                
            ];
            return new JsonResponse($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Veiller revoir votre solde'
            ];
            return new JsonResponse($data);
        }
    }
/**
 * @Route("/retrait", name="retrait", methods={"POST","GET"})
 * @IsGranted({"ROLE_USER", "ROLE_ADMIN"})
 */
public function retrait(Request $request, EntityManagerInterface $entityManager): Response

{

 $values = json_decode($request->getContent(),true); 

    $user = $this->getUser();

    $retrait= $this->getDoctrine()->getRepository(Transaction::class)->findOneBy(['codeEnvoie' => $values['codeEnvoie']]);   
            if(!$retrait){
                $data = [
                    'statu' => 500,
                    'Message' => 'Le code saisi est incorecte .Veuillez ressayer un autre  '
                ];
                return new JsonResponse($data);
     }

      else if($retrait->getCodeEnvoie()==$values['codeEnvoie'] && $retrait->getStatus()=="retirer" ){
        $data = [
            'statu' => 400,
            'Messages' => 'Le code est déja retiré'
        ];
        return new JsonResponse($data);
  
                }
     
        $retrait->setDateretrait(new \DateTime());
        $retrait->setStatus("retirer");
        $retrait->setCni($values['cni']);
     
        $retrait->setUser($user);
   
    $entityManager->persist($retrait);
    $entityManager->flush(); 
    $data = [
        'statu' => 200,
        'Message' => 'Retrait effectuer. Voici le montant:  '. $retrait->getMontant()
    ];
    return new JsonResponse($data);

}
/**
 * @Route("/test", name="testretrait", methods={"POST","GET"})
 * 
 */
public function testretrait(Request $request,RetraitRepository $retrait,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response

{
    $values = json_decode($request->getContent(),true); 
    
    $listeuser = $retrait->findAll();
    $data = $serializer->serialize($listeuser, 'json', [
        'groups' => ['list']
    ]);
    return new Response($data, 200, [
        'Content-Type' => 'application/json'
    ]);
   // $test= $this->getDoctrine()->getRepository(Transaction::class)->findOneBy(['codeEnvoie' => $values['codeEnvoie']])->getRetrait();
   
return new JsonResponse($test);
 
}
/**
 * @Route("/findcode",name="findcode",methods={"POST","GET"})
 */

public function findcodeEnvoi(Request $request,TransactionRepository $retrait,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
{

 $values = json_decode($request->getContent(),true); 
    $testcode=$retrait->findByCodeEnvoie($values['codeEnvoie']);
    //var_dump($testcode);die();
    $testcode[0]->getRetrait();
    $data = $serializer->serialize($testcode[0], 'json', [
        'groups' => ['list']
    ]);
    return new Response($data, 200, [
        'Content-Type' => 'application/json'
    ]);
}



}
