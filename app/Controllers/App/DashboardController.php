<?php 
namespace App\Controllers\App; 
use CodeIgniter\Controller;
  
class DashboardController extends Controller
{
    public function index()
    {
        $session = session();
        $data = [
            'name' => 'sajith223'
        ];
        echo view('Application/dashboard',$data);
    }
}