<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends AbstractController
{

//     private $passwordEncoder;

// public function __construct(UserPasswordEncoderInterface $passwordEncoder)
// {
//   $this->passwordEncoder = $passwordEncoder;
// }
    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param JWTEncoderInterface $JWTEncoder
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login(Request $request, JWTEncoderInterface $JWTEncoder,UserPasswordEncoderInterface $passwordEncoder)
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

            $isValid = $passwordEncoder
            ->isPasswordValid($user, $password);
            if(!$isValid){ 
                return $this->json([
                    'alerte2' => 'Mot de passe incorect'
                ]);
            }
            if($user->getStatus()!=null && $user->getStatus()=="bloquer"){
                return $this->json([
                    'Message1' => 'Acces refusé!! Vous etes bloquer,Veillez contacter votre
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
                    'roles'=> $user->getRoles(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
    
                return $this->json([
                    'token' => $token
                ]);
            }
        
                
   
        
    }

}
