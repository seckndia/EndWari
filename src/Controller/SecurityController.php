<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compts;
use App\Form\UserType;
use App\Form\ComptType;
use App\Form\LoginType;
use App\Form\BloquerType;
use App\Entity\Partenaire;
use App\Form\ComptuserType;
use App\Form\PartenaireType;
use App\Repository\UserRepository;
use App\Repository\ComptsRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;


   /**
    * @Route("/api")
   */
class SecurityController extends AbstractController

{

    /**
     * @Route("/listpart", name="listpart" , methods={"POST", "GET"})
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function listpart( PartenaireRepository $partRepository , SerializerInterface $serializer): Response
    {
        $liste = $partRepository->findAll();
        $data = $serializer->serialize($liste, 'json', [
            'groups' => ['list']
        ]);
        return new Response($data, 200, [

            'Content-Type' => 'application/json'
        ]);
       
    }

     /**
     * @Route("/listuserpart", name="listuserpart" , methods={"POST", "GET"})
     * @IsGranted({"ROLE_SUPERADMIN", "ROLE_ADMIN"})
     */
    public function listuserpart(UserRepository $userRepository , SerializerInterface $serializer): Response
    {   $partuser=$this->getUser()->getPartenaire();
        $liste = $userRepository->findBy(["partenaire" => $partuser]);
        $data = $serializer->serialize($liste, 'json', [
            'groups' => ['list']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
       
    }

    /**
     * @Route("/listuser", name="listuser" , methods={"POST", "GET"})
     * @IsGranted("ROLE_SUPERADMIN")
     * 
     
     */
    public function listUser( UserRepository $userRepository , SerializerInterface $serializer): Response
    {
        $listeuser = $userRepository->findAll();
        $data = $serializer->serialize($listeuser, 'json', [
            'groups' => ['list']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
       
    }
    /**
     * @Route("/listcompt", name="listcompt",methods={"POST","GET"})
     * @IsGranted({"ROLE_SUPERADMIN", "ROLE_ADMIN"})
     */
    public function listcompt(ComptsRepository $comptRepository , SerializerInterface $serializer): Response
    {
        $compte =$this->getUser()->getPartenaire();
        $listecompt= $comptRepository->findBy(['partenaire' => $compte]);
        $data = $serializer->serialize($listecompt, 'json', [
            'groups' => ['list']
        ]);
        return new Response($data, 200, [

            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/comptAll", name="listcomptall",methods={"POST","GET"})
     * @IsGranted("ROLE_SUPERADMIN")
     * 
     */
    public function compteAll(ComptsRepository $comptRepository , SerializerInterface $serializer): Response
    {
        $listcompte = $comptRepository->findAll();
        $data = $serializer->serialize($listcompte, 'json', [
            'groups' => ['list']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    private $passwordEncoder;

public function __construct(UserPasswordEncoderInterface $passwordEncoder)
{
  $this->passwordEncoder = $passwordEncoder;
}
    /**
     * @Route("/login12", name="login12", methods={"POST"})
     * @param JWTEncoderInterface $JWTEncoder
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login(Request $request, JWTEncoderInterface $JWTEncoder)
    {
        $values = json_decode($request->getContent());
        $username   = $values->username;
        $password   = $values->password;
        

            $repo = $this->getDoctrine()->getRepository(User::class);
            $user = $repo-> findOneBy(['username' => $username]);
            if(!$user){
                return $this->json([
                        'alerte1' => 'Username incorrect'
                    ]);
            }

            $isValid = $this->encoder
            ->isPasswordValid($user, $password);
            if(!$isValid){ 
                return $this->json([
                    'alerte2' => 'Mot de passe incorect'
                ]);
            }
            if($user->getStatus()!=null && $user->getStatus()=="bloquer"){
                return $this->json([
                    'Message1' => 'Acces refusé!! Vous etes bloquer,Veillez contacter votres
                    administrateur !'
                ]);
            }
            elseif($user->getPartenaire()!=NULL && $user->getPartenaire()->getStatus()=="bloquer"){
                return $this->json([
                    'Message2' => 'Acces refusé!! Vous etes bloquer,Veillez contacter le service client'
                ]);
            }
            else {
                $token = $JWTEncoder->encode([
                    'username' => $user->getUsername(),
                    'partenaire' => $user->getPartenaire(),
                    //'roles'=> $user->getRoles(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
    
                return $this->json([
                    'token' => $token
                ]);
            }
        
                
   
        
    }

//  /**
//      * @Route("/login1", name="login1", methods={"POST"})
//      * @param JWTEncoderInterface $JWTEncoder
//      * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
//      */
//     public function login1(Request $request, JWTEncoderInterface $JWTEncoder,UserRepository $repo){
//        $user =  new User();
//       // $values=$request->request->all();//si form
//       $values =json_decode($request->getContent(),true);
//     $form = $this->createForm(LoginType::class, $user);
//     $form->handleRequest($request);
//    // $form->submit($values);
//     $user = $repo->findOneBy([
//          'username' =>$values["username"]
//     ]); 

//    // return new JsonResponse(['user'=>$user]);

//     /*if (!$user) {
//         //throw $this->createNotFoundException('usermane invalide');
//         $data = [
            
//             'alert1' => 'Username incorrect'
//         ];
//         return new JsonResponse($data);
//     }
//     $isValid = $this->passwordEncoder->isPasswordValid($user, $values["password"]);
  
   
//     if(!$isValid){ 
//      //throw $this->createNotFoundException('Password incorrect');
//      $data = [
            
//         'alert2' => 'Password incorrect'
//     ];
//     return new JsonResponse($data);

//     }
    
//     if($user->getStatus()=="bloquer"){
//        // throw $this->createNotFoundException('Acces refuser! Veillez contacter l admin');
//         $data = [
            
//             'Message1' => 'Acces refusé!! Vous etes bloquer,Veillez contacter votres
//              administrateur'
//         ];
//         return new JsonResponse($data);
//     }
//     elseif($user->getPartenaire()!=NULL && $user->getPartenaire()->getStatus()=="bloquer"){
//         //throw $this->createNotFoundException('Acces refuser! Veillez contacter l superadmin');
//         $data = [
          
//             'Message2' => 'Acces refusé!! Vous etes bloquer,Veillez contacter le service
//             client'
//         ];

//         return new JsonResponse($data);
//     }else{
//         $token = $JWTEncoder->encode([
//             'username' => $user->getUsername(),
//             'partenaire' => $user->getPartenaire(),
//             'roles'=> $user->getRoles(),
//             'exp' => time() + 3600 // 1 hour expiration
//         ]);

//     return new JsonResponse(['token' => $values["username"],
//                              'user' =>  $values["username"]
//                              ]);        
//         }*/
//         $login= $user->getUsername();
//         return new JsonResponse(['token' =>  $values
                             
//                              ]);  
//     }



    /**
     * @Route("/register", name="register", methods={"POST"})
     * @IsGranted("ROLE_SUPERADMIN")

     */

    //-------Ajout d'un SupertUser et Caissier----/////
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
         $values=$request->request->all();//si form
     
        $file=$request->files->all()['imageName'];
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
       
        $form->submit($values);
     
        if ($form->isSubmitted() && $form->isValid()) {
        
         $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            )
        );           
            if ($values['profil']==1) {
                $user->setRoles(['ROLE_SUPERADMIN']);    
            }
           
            if ($values['profil']==2) {
                $user->setRoles(['ROLE_CAISSIER']);    
            }
            $user->setImageFile($file);  
            $user->setUpdatedAt(new \DateTime()) ; 
            $user->setStatus('Activer');

            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'statut' => 201,
                'Message' => 'L\'utilisateur ajouter'
            ];

            return new JsonResponse($data, 201);
        }
        return new Response($validator->validate($form));
    }
    /**
     * @Route("/ajoutpart", name="ajoutpart", methods={"POST"}) 
     * @IsGranted("ROLE_SUPERADMIN")

     */
     
     //-------Ajout d'un Partenaire et son Admin et Compt ----/////
     public function ajoutpart(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager , ValidatorInterface $validator)
     {
         $values=$request->request->all();//si form
 
         $file=$request->files->all()['imageName'];
 
         $part = new Partenaire();
 
         $form=$this->createForm(PartenaireType::class, $part);
 
         $form->handleRequest($request);
        
         $form->submit($values);
 
          $part->setStatus('Activer');
           
          $compt = new Compts();
 
          // Enregistrons les informations de date dans des variables
          $form=$this->createForm(ComptType::class, $compt);
          $form->handleRequest($request);
         
          $form->submit($values);
                  $jours = date('d');
                  $mois = date('m');
                  $annee = date('Y');
          
                 $heure = date('H');
                 $minute = date('i');
                 $seconde= date('s');
      $test = $jours.$mois.$annee.$heure.$minute.$seconde;
          $compt->setNumcompt($test);
          $compt->setPartenaire($part);
          $compt->setSolde(0);
 
 
          $user = new User();
         $form = $this->createForm(UserType::class, $user);
         $form->handleRequest($request);
                   
         $form->submit($values);
                 
          $user->setPassword($passwordEncoder->encodePassword($user,
             $form->get('password')->getData())); 
 
          if ($values['profil']==3) {
              $user->setRoles(['ROLE_ADMIN']);    
                      }
                       
         if ($values['profil']==4) {
             $user->setRoles(['ROLE_USER']);    
                   }
             $user->setUpdatedAt(new \DateTime()) ; 
             $user->setStatus('Activer');
                  
             $user->setImageFile($file); 
             $user->setStatus('Activer');
             $user->setPartenaire($part);
             $user->setNumcompt($compt);
            
             $entityManager = $this->getDoctrine()->getManager();
 
             $entityManager->persist($user);
             $entityManager->persist($part);
             $entityManager->persist($compt);
             $entityManager->flush();
     
             $data = [
                 'statut' => 201,
                 'Messages' => 'Le partenaire ajouter'
             ];
 
             return new JsonResponse($data, 201);

             //return new Response($validator->validate($form));
 
            }
    /**
     * @Route("/ajoutpartuser", name="ajoutpartuser", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     
     */
     //-------Ajout des users d'un partenaire  ----/////
    public function ajoutpartuser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
    
     $values=$request->request->all();//si form
     $file=$request->files->all()['imageName'];
     $user = new User();
     $form = $this->createForm(UserType::class, $user);
     $form->handleRequest($request);
      
     $form->submit($values);
    
       if ($form->isSubmitted()) {
       
        $user->setPassword(
           $passwordEncoder->encodePassword(
               $user,
               $form->get('password')->getData()
           )
       );           
    if ($values['profil']==3) {
        $user->setRoles(['ROLE_ADMIN']);    
                }
                 
   if ($values['profil']==4) {
       $user->setRoles(['ROLE_USER']);    
             }

     $partenaire=$this->getUser()->getPartenaire();

            $user->setPartenaire($partenaire);
            $user->setStatus('Activer');
            $user->setImageFile($file);

            $user->setUpdatedAt(new \DateTime()) ;

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();
            $data = [
                'statu' => 201,
                'messages' => 'Lutilisateur ajouter'
            ];

            return new JsonResponse($data, 201);
   }
   return new Response($validator->validate($form));

}
//-------Bloquer Debloquer users part-----------------/////

/**
 * @Route("/bloquer/{id}", name="userBlock", methods={"GET","POST","PUT"})
 * @IsGranted({"ROLE_SUPERADMIN", "ROLE_ADMIN"})
 */

public function userBloquer(User $users, Request $request, UserRepository $userRepo,EntityManagerInterface $entityManager): Response
    {

        // $values=$request->request->all();//si form
        // $user = new User();
        // $form = $this->createForm(BloquerType::class, $user);
        // $form->handleRequest($request);
         
        // $form->submit($values);
        $values = json_decode($request->getContent(),true);

        $user=$userRepo->find($users->getId());

        //$user = $entityManager->getRepository(User::class)->find($user->getId());
       // $user=$userRepo->findOneByUsername($values['username']);
        
       if($user->getUsername()== "cheikh"){
            
        return $this->json([
            'message1' =>'Attention cest le SupertAdmin !!! '
        ]);
       
    }
        elseif($user->getStatus()=="Activer"){
            $user->setStatus("bloquer");
            $entityManager->flush();
            $data = [
                'statu' => 200,
                'messages' => 'utilisateur  bloquer'
            ];
            return new JsonResponse($data);
        }
        
        else{
            $user->setStatus("Activer");
            $entityManager->flush();
            $data = [
                'status' => 200,
                'message' => 'utilisateur debloquer'
            ];
            return new JsonResponse($data);
        }
    }
//--------------------Affectation de compte a un user------------------------////
/**
 * @Route("/afectcompt/{id}" , name="afectationCompt", methods={"POST"})
 * @IsGranted("ROLE_ADMIN")
 */

public function afectcompt(Request $request, UserRepository $userRepo,EntityManagerInterface $entityManager, User $user): Response
{

    $values=$request->request->all();


$uses = new Compts();
$entityManager = $this->getDoctrine()->getManager();
$form = $this->createForm(ComptuserType::class, $uses);
        $form->handleRequest($request);
         
        $form->submit($values);
$compte = $entityManager->getRepository(Compts::class)->findOneBy([
    'numcompt'=>$values['numcompt'],
    ]);  
$part=$compte->getPartenaire();
$partAdmin=$this->getUser()->getPartenaire();
if($partAdmin != $part){
    return new Response('Le compte n appartient pas a votre entreprise',Response::HTTP_CREATED);

}

   $user->setNumCompt($compte);
   $entityManager->flush();
   $data = [
    'status' => 200,
    'message' => 'afectation réussis'
];
return new JsonResponse($data);


}
  /**
     * @Route("/findcompt", name="findcompt",methods={"POST"})
     * @IsGranted({"ROLE_SUPERADMIN", "ROLE_ADMIN"})
     * 
     * 
     */
    public function findcompt(Request $request,ComptsRepository $comptRepository , SerializerInterface $serializer): Response
    {
        $values =json_decode($request->getContent(),true);
        $listecompt= $comptRepository->findOneBy(['numcompt' => $values]);
        $data = $serializer->serialize($listecompt, 'json', [
            'groups' => ['find']
        ]);
        return new Response($data, 200, [

            'Content-Type' => 'application/json'
        ]);
    }

}
