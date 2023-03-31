<?php 
namespace App\Controllers\Auth;  
use CodeIgniter\Controller;
use App\Models\User;
  
class SignupController extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        echo view('Auth/signup', $data);
    }
  
    public function store()
    {
        helper(['form']);
        $session = session();
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
          
        if($this->validate($rules)){
            $userModel = new User();
            $data = [
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $userModel->save($data);
            return redirect()->to('/signin');
        }else{
            $data['validation'] = $this->validator;
            $session->setFlashdata('msg', $data['validation']->listErrors());
            return redirect()->to('/signup');
        }
          
    }
  
}