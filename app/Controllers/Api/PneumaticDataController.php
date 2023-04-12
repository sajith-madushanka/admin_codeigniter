<?php 
namespace App\Controllers\Api; 
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use App\Models\PneumaticPair;
use App\Models\PneumaticPairData;
use App\Models\Device;
  
class PneumaticDataController extends Controller
{
    use ResponseTrait;

    public function pneumaticData()
    {  
        $rules = [
            'LRFID'          => 'required',
            'RRFID'         => 'required',
            'UNIX'      => 'required'
        ];
        
        $deviceModel = model('Device');
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            if($this->validate($rules)){
                try {
                    
                    $pneumatic_pair = new PneumaticPair();
                    $data = $pneumatic_pair->where('left_rfid', $this->request->getVar('LRFID'))->where('right_rfid', $this->request->getVar('RRFID'))->first();
                    
                    if($data){
                        $pair_id = $data['id'];
                    }
                    else{
                        $lrfid_entry = $pneumatic_pair->where('left_rfid', $this->request->getVar('LRFID'))->orwhere('right_rfid', $this->request->getVar('LRFID'))->first();
                        $rrfid_entry = $pneumatic_pair->where('left_rfid', $this->request->getVar('RRFID'))->orwhere('right_rfid', $this->request->getVar('RRFID'))->first();
                        if(!$lrfid_entry && !$rrfid_entry){
                            if($this->request->getVar('LStatus') == 0 || $this->request->getVar('RStatus') == 0){
                                $status = 2;
                            }
                            else{
                                $status = 1;
                            }
                            $data = [
                                'left_rfid'     => $this->request->getVar('LRFID'),
                                'right_rfid'     => $this->request->getVar('RRFID'),
                                'pair_status'   => $status
                            ];
                            $pneumatic_pair->save($data);
                            if($pneumatic_pair->getInsertID()){
                                $pair_id = $pneumatic_pair->getInsertID();
                            }
                        }
                        else{
                            $pair_id = null;
                        }
                        
                    }
                    
                    if($pair_id != null){
                        $pneumatic_data = new PneumaticPairData();
                        $data = [
                            'pair_id'     => $pair_id,
                            'lt'     => json_encode($this->request->getVar('LT')),
                            'lm'     => json_encode($this->request->getVar('LM')),
                            'lb'     => json_encode($this->request->getVar('LB')),
                            'rt'     => json_encode($this->request->getVar('RT')),
                            'rm'     => json_encode($this->request->getVar('RM')),
                            'rb'     => json_encode($this->request->getVar('RB')),
                            'left_status'     => $this->request->getVar('LStatus'),
                            'right_status'     => $this->request->getVar('RStatus'),
                            'date_time'     => date("Y-m-d H:i:s", $this->request->getVar('UNIX'))
    
                        ];
                        $pneumatic_data->save($data);
                        if($this->request->getVar('LStatus') == 0 || $this->request->getVar('RStatus') == 0){
                            $status = 2;
                        }
                        else{
                            $status = 1;
                        }
                        $data2 = [
                            'updated_at'		=>  date("Y-m-d H:i:s", $this->request->getVar('UNIX')),
                            'pair_status'   => $status
                        ];
                        $pneumatic_pair->update($pair_id,$data2);
                        $ok = 1;
                        $message = "Pair Data Inserted Successfully.";
                    }
                    else{
                        $ok = 0;
                        $message = "Pair Didnt Match.";
                    }
    
                    
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

    public function pneumaticData_final()
    {  
       
        $rules = [
            'LRFID'          => 'required',
            'RRFID'         => 'required'
        ];
         
        $deviceModel = model('Device');
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            if($this->validate($rules)){
                try {
                    
                    $pneumatic_pair = new PneumaticPair();
                    $data = $pneumatic_pair->where('left_rfid', $this->request->getVar('LRFID'))->where('right_rfid', $this->request->getVar('RRFID'))->first();
                    
                    if($data){
                        $pneumatic_pair->update($data['id'],['final_status'		=>  1]);
                        $ok = 1;
                        $message = "Pair matched Successfully.";
                    }
                    else{
                        $lrfid_entry = $pneumatic_pair->where('left_rfid', $this->request->getVar('LRFID'))->orwhere('right_rfid', $this->request->getVar('LRFID'))->first();
                        $rrfid_entry = $pneumatic_pair->where('left_rfid', $this->request->getVar('RRFID'))->orwhere('right_rfid', $this->request->getVar('RRFID'))->first();
                        if($lrfid_entry){
                            $pneumatic_pair->update($lrfid_entry['id'],['final_status'		=>  2]);
                        }
                        if($rrfid_entry){
                            $pneumatic_pair->update($rrfid_entry['id'],['final_status'		=>  2]);
                        }
                        $ok = 0;
                        $message = 'pair didnt match';
                    }

                    
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

    public function deviceAdd()
    {  
        $rules = [
            'name'          => 'required'
        ];
        helper('text'); 
        if($this->validate($rules)){
            try {
                
                $device = new Device();
                
                $token = random_string('alnum', 16);
                    
                $data = [
                    'name'     => $this->request->getVar('name'),
                    'token'     => $token
                ];
                $device->save($data);
                
                $ok = 1;
                $message = "device added successfully";
                
            } catch (\Throwable $e) {
                $ok = 0;
                $message = $e->getMessage();
            }
        }
        else{
            $ok = 0;
            $message = "Some Parameters missing.";
        }

        $data = [
            'ok' => $ok,
            'message' => $message,
        ];

        return $this->respond($data);
    }

    public function heartBeat()
    {  
        helper('date');
        $unix = now("Asia/Colombo");
        $bit = 0;
        $deviceModel = new Device();
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            if($device['status'] == 1){
                $bit = 1; 
                $deviceModel->update($device['id'],['status'		=>  0]);
            }
            $ok = 1;
            $message = "unix time";
        }
        $data = [
            'ok' => $ok,
            'status_bit' => $bit,
            'unix' => $unix,
            'message' => $message,
        ];

        return $this->respond($data);
    }

    public function uploadFile()
    {  
        $deviceModel = new Device();
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            
            if($this->request->getFiles()){
                try {
                   
                    $file = $this->request->getFile('data');
                    unlink(WRITEPATH . 'uploads/' . $file->getName());
                    $file->move(WRITEPATH . 'uploads');
                    $deviceModel->update($device['id'],['status'		=>  1,'file' => 'uploads/' . $file->getName() ]);
                    $ok = 1;
                    $message = "uploaded the file";
                } catch (\Throwable $e) {
                    $ok = 0;
                    $message = $e->getMessage();
                }
            }
            else{
                $ok = 0;
                $message = "no data file";
            }
        }
       

        $data = [
            'ok' => $ok,
            'message' => $message,
        ];

        return $this->respond($data);
    }

    public function heartBeatOld()
    {  
        helper('date');
        $unix = now("Asia/Colombo");
        $bit = 0;
        $deviceModel = new Device();
        $device = $deviceModel->where('token', $this->request->getVar('api_token'))->first();
        
        if (!$device) {
            $ok = 0;
            $message = "Invalid token.";
        }
        else{
            
            if($this->request->getFiles()){
                try {
                   
                    $file = $this->request->getFile('data');
                    unlink(WRITEPATH . 'uploads/' . $file->getName());
                    $file->move(WRITEPATH . 'uploads');
                    $deviceModel->update($device['id'],['status'		=>  1,'file' => 'uploads/' . $file->getName() ]);
                    $ok = 1;
                    $message = "uploaded the file";
                } catch (\Throwable $e) {
                    $ok = 0;
                    $message = $e->getMessage();
                }
            }
            else{
                if($device['status'] == 1){
                    $bit = 1; 
                    $deviceModel->update($device['id'],['status'		=>  0]);
                }
                $ok = 1;
                $message = "unix time";
            }
        }
       

        $data = [
            'ok' => $ok,
            'status_bit' => $bit,
            'unix' => $unix,
            'message' => $message,
        ];

        return $this->respond($data);
    }

    public function showFile()
    {   
        helper("filesystem");
        $filename = 'data.bin';
        $image = file_get_contents(WRITEPATH.'uploads/'.$filename);
        $mim = 'bin';
        return $this->response
                ->setHeader('Content-Type', $mim)
                ->setHeader('Content-disposition', 'inline; filename="data.bin"')
                ->setStatusCode(200)
                ->setBody($image);
    }

}