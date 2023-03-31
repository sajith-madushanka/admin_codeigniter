<?php 
namespace App\Controllers\Api; 
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use App\Models\PneumaticPair;
use App\Models\PneumaticPairData;
  
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
                        $data = [
                            'left_rfid'     => $this->request->getVar('LRFID'),
                            'right_rfid'     => $this->request->getVar('RRFID')
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

        $data = [
            'ok' => $ok,
            'message' => $message,
        ];

        return $this->respond($data);
    }
}