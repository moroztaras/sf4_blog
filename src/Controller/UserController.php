<?php

namespace App\Controller;

use App\Components\Users\Models\ChangePasswordModel;
use App\Forms\ChangePasswordForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

  public function recoverPassword( Request $request ){
    /** @var User $user */
    $user = $this->getUser();
    $userAccount = $user->getAccount();
    if(!$userAccount->getTokenRecover())
      return $this->redirectToRoute('user');

    $changePasswordModel = new ChangePasswordModel();
    $formChangePassword = $this->createForm(ChangePasswordForm::class, $changePasswordModel);
    $formChangePassword->handleRequest($request);
    if($formChangePassword->isSubmitted() && $formChangePassword->isValid()){
      $encoder = $this->get('security.password_encoder');
      $password = $encoder->encodePassword($user, $changePasswordModel->password);
      $user->setPassword($password);
      $userAccount->setTokenRecover(null);
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('user');
    }
    return $this->render('@User/security/recover.html.twig',[
      'recover_form' => $formChangePassword->createView()
    ]);
  }
}