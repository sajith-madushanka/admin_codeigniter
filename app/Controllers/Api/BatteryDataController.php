<?php 
namespace App\Controllers\Api; 
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use App\Models\Battery;
use App\Models\Device;
  
class BatteryDataController extends Controller
{
    use ResponseTrait;

    public function batteryData()
    {  
        $rules = [
            'Voltage'          => 'required',
            'Status'         => 'required'
        ];
        helper('date');
        $deviceModel = new Device();
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            if($this->validate($rules)){
                try {
                    
                    $battery = new Battery();
                    $data = [
                        'voltage'     => $this->request->getVar('Voltage'),
                        'test_date'		=>  date("Y-m-d H:i:s", now("Asia/Shanghai")),
                        'status'   =>  $this->request->getVar('Status'),
                        'device'    => $device['name'],  
                    ];
                    $battery->save($data);
                   
                    $ok = 1;
                    $message = "Battery Data Inserted Successfully.";
                    
                } catch (\Throwable $e) {
                    $ok = 0;
                    $message = $e->getMessage();
                }
            }
            else{
                $ok = 0;
                $message = "Some Parameters missing.";
            }
        }
       

        $data = [
            'ok' => $ok,
            'message' => $message,
        ];

        return $this->respond($data);
    }

}