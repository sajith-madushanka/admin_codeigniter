<?php 
namespace App\Controllers\Auth;  
use CodeIgniter\Controller;
use App\Models\User;
  
class SigninController extends Controller
{
    public function index()
    {
        helper(['form']);
        echo view('Auth/signin');
    } 
  
    public function login()
    {
        $session = session();
        $userModel = new User();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
        
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'is_super' => array_key_exists('is_super', $data)?$data['is_super']:0,
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/');
            
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/signin');
            }
        }else{
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('/signin');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/signin');
    }
}