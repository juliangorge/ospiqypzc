<?php
namespace Auth\Service;

use Auth\Entity\User;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Math\Rand;
use PHPMailer\PHPMailer\PHPMailer;

class UserManager
{
    private $em;  
    
    private $viewRenderer;

    private $config;

    public function __construct($em, $viewRenderer, $config) 
    {
        $this->em = $em;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    public function addUser($data) 
    {
        if($this->checkUserExists($data['email'])) {
            throw new \Exception('Ya existe un usuario con el email ingresado');
        }
        
        $user = new \Auth\Entity\User();

        $bcrypt = new Bcrypt();
        $data['password'] = $bcrypt->create($data['password']);

        $user->initialize($data);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function updateUser($user, $data) 
    {
        if($user->getEmail() != $data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception('El email ' . $data['email'] . ' ya existe');
        }

        $user->setEmail($data['email']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setStatus($data['status']);        
        
        $this->em->flush();

        return true;
    }

    public function checkUserExists($email)
    {
        $user = $this->em->getRepository(User::class)->findOneByEmail($email);
        return $user !== NULL;
    }

    public function validatePassword($user, $password) 
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        return $bcrypt->verify($password, $passwordHash);
    }

    public function generatePasswordResetToken($user)
    {
        if ($user->getStatus() == User::STATUS_RETIRED) {
            throw new \Exception('No puede reestablecer una cuenta inactiva: ' . $user->getEmail());
        }
        
        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        
        // Encrypt the token before storing it in DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);  
        
        // Save token to DB
        $user->setPasswordResetToken($tokenHash);
        
        // Save token creation date to DB.
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);  
        
        // Apply changes to DB.
        $this->em->flush();
        
        // Send an email to user.
        $subject = 'Cambio de contraseña';
            
        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();
        
        // Produce HTML of password reset email
        $body = $this->viewRenderer->render('users/email/reset-password-email', [
            'passwordResetUrl' => $passwordResetUrl,
        ]);

        $body = str_replace('{{firstname}}', $user->getFirstname(), $body);
        $body = str_replace('{{lastname}}', $user->getLastname(), $body);
        $body = str_replace('{{name}}', $this->config['name'], $body);
        $body = str_replace('{{domain}}', $this->config['domain'], $body);
        $body = str_replace('{{slogan}}', $this->config['slogan'], $body);
        $body = str_replace('{{facebook}}', $this->config['facebook'], $body);
        $body = str_replace('{{instagram}}', $this->config['instagram'], $body);

        $s = $this->em->createQuery('SELECT w.attention_schedules FROM Admin\Entity\Web w WHERE w.id = 1')
            ->getSingleResult();

        $body = str_replace('{{attention_shedule}}', $s['attention_schedules'], $body);
        $body = str_replace('{{address}}', $this->config['address'], $body);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $this->config['exceptionHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->config['exceptionEmail'];
        $mail->Password = $this->config['exceptionEmailPwd'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $this->config['exceptionHostPort'];
        $mail->CharSet = 'UTF-8';

        try {
            $mail->setFrom($this->config['noresponseEmail'], $this->config['name']);
            $mail->addAddress($user->getEmail());
            $mail->isHTML(true);
            $mail->Subject = $this->config['name'] . ' - Error';
            $mail->Body    = $body;
            $mail->AltBody = html_entity_decode($body);
            $mail->send();
        } catch (Exception $e) {
            #var_dump($e->getMessage());
        }
    }
    
    /**
     * Checks whether the given password reset token is a valid one.
     */
    public function validatePasswordResetToken($email, $password_reset_token)
    {
        // Find user by email.
        $user = $this->em->getRepository(User::class)
                ->findOneByEmail($email);
        
        if($user == null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }
        
        // Check that token hash matches the token hash in our DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getPasswordResetToken();
        
        if (!$bcrypt->verify($password_reset_token, $tokenHash)) {
            return false; // mismatch
        }
        
        // Check that token was created not too long ago.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
    }
    
    /**
     * This method sets new password by password reset token.
     */
    public function setNewPasswordByToken($email, $password_reset_token, $newPassword)
    {
        if (!$this->validatePasswordResetToken($email, $password_reset_token)) {
           return false; 
        }
        
        // Find user with the given email.
        $user = $this->em->getRepository(User::class)
                ->findOneByEmail($email);
        
        if ($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }
                
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);        
        $user->setPassword($passwordHash);
                
        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);
        
        $this->em->flush();
        
        return true;
    }
    
    /**
     * This method is used to change the password for the given user. To change the password,
     * one must know the old password.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];
        
        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }                
        
        $newPassword = $data['new_password'];
        
        // Check password length
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }
        
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);
        
        // Apply changes
        $this->em->flush();

        return true;
    }
}

