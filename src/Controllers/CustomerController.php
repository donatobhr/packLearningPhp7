<?php declare(strict_types=1);


namespace Bookstore\Controllers;
use Bookstore\Exceptions\NotFoundException;
use Bookstore\Models\CustomerModel;

class CustomerController extends AbstractController {
    public function login(string $email = "") : string {
        if(!$this->request->isPost()) {
            return $this->render('login.twig', []);
        }

        $params = $this->request->getParams();
        
        if(!$params->has('email')){
            $params = ['errorMessage' => 'No info provided.'];
            return $this->render('login.twig', $params);
        }

        $email = $params->getString('email');
        $customerModel = new CustomerModel($this->db);

        try {
            $customer = $customerModel->getByEmail($email);
        } catch (NotFoundException $e){
            $this->log->warning('Customer email not found:' . $email);
            $params = ['errorMessage' => 'Email not found.'];
            return $this->render('login.twig', $params);
        }

        setcookie('user', (string) $customer->getId());
        $this->setCustomerId($customer->getId());
        $bookCtrl = new BookController($this->di, $this->request);
        return $bookCtrl->getAll();
    }
}