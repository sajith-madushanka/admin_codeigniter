<?php 
namespace App\Controllers\App; 
use CodeIgniter\Controller;
use App\Models\PneumaticPair;
use App\Models\RawData;
use CodeIgniter\API\ResponseTrait;
use App\Models\PneumaticPairData;
  
class DashboardController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        helper('date');
        if ($this->request->is('get')) {
            $pneumatic_pair = new PneumaticPair();
            $date = date("Y-m-d", now("Asia/Shanghai"));
                $data = [
                    'date' => $date,
                    't1p' => $pneumatic_pair->where('updated_at >=', $date)->where('pair_status',1)->countAllResults(),
                    't1f' => $pneumatic_pair->where('updated_at >=', $date)->where('pair_status',2)->countAllResults(),
                    't2p' =>$pneumatic_pair->where('final_test >=', $date)->where('final_status',1)->countAllResults(),
                    't2f' =>$pneumatic_pair->where('final_test >=', $date)->where('final_status',2)->countAllResults(),
                ];
            
		
            echo view('Application/dashboard',$data);
        }
        
    }

    public function getData()
    {
        try{
        $session = session();
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $start = $this->request->getPost('start') ?? '';
        $end = $this->request->getPost('end') ?? '';
        $limit = 20; // Items per page
        $offset = ($page - 1) * $limit;
        $summary_count ='';
        
        $pneumatic_pair = new PneumaticPair();
        $pager = \Config\Services::pager(null,null,true);
        if($start && $end){
            $pneumatic_pair->where('updated_at BETWEEN "'.$start.'" and "'.$end.'"')->orwhere('final_test BETWEEN "'.$start.'" and "'.$end.'"');
            $rows = $pneumatic_pair->countAllResults();
            $pneumatic_pair->where('updated_at BETWEEN "'.$start.'" and "'.$end.'"')->orwhere('final_test BETWEEN "'.$start.'" and "'.$end.'"');
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
            $t1p = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',1)->countAllResults();
            $t1f = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',2)->countAllResults();
            $t2p = $pneumatic_pair->where('final_test >=', $start)->where('final_test <=', $end)->where('final_status',1)->countAllResults();
            $t2f = $pneumatic_pair->where('final_test >=', $start)->where('final_test <=', $end)->where('final_status',2)->countAllResults();
            $t1= $t1p + $t1f;
            $t2= $t2p + $t2f;
            
            $summary_count =  '<div class="col-md-6">
                                    <div class="card widget-card-1">
                                        <div class="card-block-small">
                                            <i class="icofont icofont-pie-chart bg-c-green card1-icon"></i>
                                            <span class="text-c-green f-w-100">Pneumatic Test Passed</span>
                                            <h4>'.$t1p.'/'.$t1.'</h4>
                                            <div>
                                                <span class="f-left m-t-10 text-muted">
                                                    <i class="text-c-green f-16 icofont icofont-warning m-r-10"></i> '.$t1f.' Devices Failed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card widget-card-1">
                                        <div class="card-block-small">
                                            <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                                            <span class="text-c-blue f-w-100">Final Test Passed</span>
                                            <h4>'.$t2p.'/'.$t2.'</h4>
                                            <div>
                                                <span class="f-left m-t-10 text-muted">
                                                    <i class="text-c-blue f-16 icofont icofont-warning m-r-10"></i>'.$t2f.' Devices Failed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        }
        else if (!empty($filter)) {
            $pneumatic_pair->like('dev_id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $rows = $pneumatic_pair->countAllResults();
            $pneumatic_pair->like('dev_id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $data =  $pneumatic_pair->orderBy('pinned', 'desc')->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        else{
            $rows = $pneumatic_pair->countAllResults();
            $data =  $pneumatic_pair->orderBy('pinned', 'desc')->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        $links = $pager->makeLinks($page,$limit,$rows);
        $pneumatic_pair_data = new PneumaticPairData();
        $table_data ='<thead>
                        <tr class="stickey_head">
                            <th>#</th>
                            <th>Left RFID</th>
                            <th>Right RFID</th>
                            <th>Pneumatic<br>Test</th>
                            <th>Final<br>Inspection</th>
                            <th>Overall<br>Inspection</th>
                            <th>Tester ID</th>';
        if($session->get('is_super')==1){
            $table_data .= '        <th>Action</th>
                                    </tr>
                                </thead>
                            <tbody>';
        }
        else{
            $table_data .= '    </tr>
                            </thead>
                            <tbody>';
        }
                            
        foreach ($data as  $row) {
            $table_data .= '<tr>';
            if($pneumatic_pair_data->where('pair_id',$row->id)->countAllResults()>1){
                if($row->dev_id){
                    $table_data .= '<th style="color:#FFB64D" onclick=pair_Data('.$row->id.')>' . $row->dev_id . '</th>';
                }
                else{
                    $table_data .= '<th style="color:#FFB64D" onclick=pair_Data('.$row->id.')>' . $row->id . '</th>';
                } 
            }
            else{
                if($row->dev_id){
                    $table_data .= '<th onclick=pair_Data('.$row->id.')>'.$row->dev_id .'</th>';
                }
                else{
                    $table_data .= '<th onclick=pair_Data('.$row->id.')>'.$row->id .'</th>';
                }
               
            }
            $table_data .= '<td onclick=pair_Data('.$row->id.') style="font-size: 13px">' . $row->left_rfid . '</td>';
            $table_data .= '<td onclick=pair_Data('.$row->id.') style="font-size: 13px">' . $row->right_rfid . '</td>';
            if($row->pair_status == 2){
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-pink f-w-600">Fail</span><p class="text-muted ">'.$row->updated_at.'</p></td>';
            }
            else{
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-green f-w-600"> Pass </span><p class="text-muted ">'.$row->updated_at.'</p></td>';
            }
            if($row->final_status == 1){
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-green f-w-600"><i class="icofont icofont-check-circled"></i> Matched </span><p class="text-muted ">'.$row->final_test.'</p></td>';
            }
            else if($row->final_status == 2){
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-pink f-w-600"><i class="icofont icofont-warning-alt"></i> Mismatched </span><p class="text-muted ">'.$row->final_test.'</p></td>';
            }
            else{
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-yellow f-w-600"><i class="icofont icofont-info-square"></i> Pending </span></td>';
            }
            if($row->final_status == 0){
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-yellow f-w-600"><i class="icofont icofont-info-square"></i> Pending </span></td>';
            }
            else if($row->pair_status == 1 && $row->final_status == 1){
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-green f-w-600"><i class="icofont icofont-check-circled"></i> Accepted </span></td>';
            }
            else{
                $table_data .= '<td onclick=pair_Data('.$row->id.')><span class="text-c-pink f-w-600"><i class="icofont icofont-warning-alt"></i> Rejected </span></td>';
            }
            $table_data .= '<td onclick=pair_Data('.$row->id.')><p class="text-muted ">'.$row->device.'</p></td>';
            if($session->get('is_super')==1){
                $table_data .= '<td style="display:inline-flex">';
                if($row->pinned == 0){
                    $table_data .='<i  onclick=pin_data('.$row->id.',1)  class="ti-star"></i>';
                }
                else{
                    $table_data .='<i  style="color:gold" onclick=pin_data('.$row->id.',0) class="ti-star"></i>';
                }
                $table_data .= '<input value = "'.$row->id.'" id="export_check" style="margin-left: 10px;" type="checkbox" name="checkbox" /></td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</tbody>';
        return $this->response->setJSON([
            'table_data' => $table_data,
            'links'=>$links,
            'summary'=>$summary_count
        ]);
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function exportData()
    {
        
        try{
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $start = $this->request->getPost('start') ?? '';
        $end = $this->request->getPost('end') ?? '';
        
        $pneumatic_pair = new PneumaticPair();
        if($start && $end){
            $pneumatic_pair->where('updated_at BETWEEN "'.$start.'" and "'.$end.'"')->orwhere('final_test BETWEEN "'.$start.'" and "'.$end.'"');
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }
        else if (!empty($filter)) {
            $pneumatic_pair->like('dev_id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }
        else{
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }
        if($data){
            $csvData = "ID,Left RFID,Right RFID,Pneumatic Test,Test Date,Final Inspection,Inspected Date,Overall Inspection,Tested device\n";
            foreach ($data as $key=>$row){
                if($row->pair_status == 1){
                    $pneumatic_test = "pass" ;
                }
                else{
                    $pneumatic_test = "fail" ;
                }
                if($row->dev_id){
                    $id = $row->dev_id;
                }
                else{
                    $id = $row->id;
                }
                if($row->final_status == 1){
                    $final_test = "matched" ;  
                    $final_date = $row->final_test ;          
                }
                else if($row->final_status == 2){
                    $final_test = "mismatched" ;
                    $final_date = $row->final_test ;               
                }
                else{
                    $final_test = "pending" ;    
                    $final_date = " " ;           
                }
                if($row->final_status == 0){
                    $overall = "pending" ;
                }
                else if($row->pair_status == 1 && $row->final_status == 1){
                    $overall = "accepted" ;               
                }
                else{
                    $overall = "rejected" ;              
                }
               
               
                $csvData .= "".$id.",".$row->left_rfid.",".$row->right_rfid.",".$pneumatic_test.",".$row->updated_at.",".$final_test.",".$final_date.",".$overall.",".$row->device."\n";
            }
            
            $csvData = json_encode($csvData);
            // Set the response headers for CSV download
            $filename = 'Pneumatic_test.csv';
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            // Output the CSV data
            echo $csvData;
            exit;
        }
        

        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function exportRawData()
    {
        
        try{
           
           $data = new RawData();
           $raw =  $data->where('pair_data_id',$this->request->getPost('id'))->get()->getResult();
           $raw = $raw[0];

           $pair = new PneumaticPair();
           $rfids =  $pair->find($this->request->getPost('pair_id'));

           if($rfids['dev_id']){
                $id = $rfids['dev_id'];
            }
            else{
                $id = $rfids['id'];
            }
           
           $pair_data = new PneumaticPairData();
           $pair_data =  $pair_data->find($this->request->getPost('id'));
        
        if($raw){
            $csvData = "pair id,left rfid,right rfid,date time,left top,left middle,left bottom,right top,right middle,right bottom\n";
            $lt = json_decode($raw->lt);
            $lm = json_decode($raw->lm);
            $lb = json_decode($raw->lb);
            $rt = json_decode($raw->rt);
            $rm = json_decode($raw->rm);
            $rb = json_decode($raw->rb);

            for($i=0;$i<480;$i++){
                if($i==0){
                    $csvData .= "".$id.",".$rfids['left_rfid'].",".$rfids['right_rfid'].",".$pair_data['date_time'].",".$lt[$i].",".$lm[$i].",".$lb[$i].",".$rt[$i].",".$rm[$i].",".$rb[$i]."\n";
                }
                else{
                    $csvData .= " , , , ,".$lt[$i].",".$lm[$i].",".$lb[$i].",".$rt[$i].",".$rm[$i].",".$rb[$i]."\n";
                }
            }
            
            $csvData = json_encode($csvData);
            // Set the response headers for CSV download
            $filename = 'raw_Data.csv';
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            // Output the CSV data
            echo $csvData;
            exit;
        }
        

        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function exportRawDataArray()
    {
        
        try{
           $ids = $this->request->getPost('ids');
           
           $count = 0;
           foreach($ids as $id){
                $pair_data = new PneumaticPairData();
                ${"pair_data".$id} =  $pair_data->where('pair_id',$id)->orderBy('date_time','desc')->first();
                $data = new RawData();
                $raw =  $data->where('pair_data_id',${"pair_data".$id}['id'])->get()->getResult();
                if($raw){
                    $count++;
                    ${"raw".$id} = $raw[0];
                    ${"lt".$id} = json_decode($raw[0]->lt);
                    ${"lm".$id} = json_decode($raw[0]->lm);
                    ${"lb".$id} = json_decode($raw[0]->lb);
                    ${"rt".$id} = json_decode($raw[0]->rt);
                    ${"rm".$id} = json_decode($raw[0]->rm);
                    ${"rb".$id} = json_decode($raw[0]->rb);

                    $pair = new PneumaticPair();
                    ${"rfids".$id} =  $pair->find($id);
                }
                else{
                    ${"raw".$id} = null;
                }
           }

           if($count > 0){

                $csvData ="";
                for($i=0;$i<$count;$i++){
                    if($i!=0){
                        $csvData .= " , ,";
                    }
                    $csvData .= "pair id,left rfid,right rfid,date time,left top,left middle,left bottom,right top,right middle,right bottom";
                }
                $csvData .="\n";

                for($i=0;$i<480;$i++){
                    foreach($ids as $id){
                        if(${"raw".$id} != null){
                            if(${"rfids".$id}['dev_id']){

                                $new_id =${"rfids".$id}['dev_id'];
                            }
                            else{
                                $new_id = ${"rfids".$id}['id'];
                            }
                            if($i==0){
                                $csvData .= "".$new_id.",".${"rfids".$id}['left_rfid'].",".${"rfids".$id}['right_rfid'].",".${"pair_data".$id}['date_time'].",".${"lt".$id}[$i].",".${"lm".$id}[$i].",".${"lb".$id}[$i].",".${"rt".$id}[$i].",".${"rm".$id}[$i].",".${"rb".$id}[$i]." , ,";
                            }
                            else{
                                $csvData .= " , , , ,".${"lt".$id}[$i].",".${"lm".$id}[$i].",".${"lb".$id}[$i].",".${"rt".$id}[$i].",".${"rm".$id}[$i].",".${"rb".$id}[$i]." , ,";
                            }
                        } 
                    }
                    $csvData .="\n";
                }
           }
           
            
            $csvData = json_encode($csvData);
            // Set the response headers for CSV download
            $filename = 'raw_Data.csv';
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            // Output the CSV data
            echo $csvData;
            exit;
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function pairData()
    {
        try{
        $pneumatic_pair = new PneumaticPair();
        $pair_data =  $pneumatic_pair->find($this->request->getPost('id'));
        $pneumatic_pair_data = new PneumaticPairData();
        $data =  $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->orderBy('date_time','desc')->get()->getResult();
        $table_data ='<input type="hidden" id="pair" value="'.$this->request->getPost('id').'" /><thead>
                        <tr class="stickey_head">
                            <th>#</th>
                            <th>Sensor</th>
                            <th>HP1</th>
                            <th>HP2</th>
                            <th>HP3</th>
                            <th>HP4</th>
                            <th>HP5</th>
                            <th>HP6</th>
                            <th>HP7</th>
                            <th>HP8</th>
                            <th>HP9</th>
                            <th>HP10</th>
                            <th>LP1</th>
                            <th>LP2</th>
                            <th>LP3</th>
                            <th>LP4</th>
                            <th>LP5</th>
                            <th>LP6</th>
                            <th>LP7</th>
                            <th>LP8</th>
                            <th>LP9</th>
                            <th>LP10</th>
                            <th>Date Time</th>
                        </tr>
                    </thead>
                    <tbody>';
        $char = 'a';
        foreach ($data as  $row) {
            $table_data .= '<tr >';
            if($pair_data['dev_id']){
                $table_data .= '<th>' . $pair_data['dev_id'] .' '.$char. '.</th>';
            }
            else{
                $table_data .= '<th>' . $this->request->getPost('id') .' '.$char. '.</th>';
            }
            
            $char++;
            $table_data .= '<td>LT</td>';
            $lt = json_decode($row->lt);
            if(25.5 <= $lt->HP1 &&  $lt->HP1 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP1 . '</td>';
            }
            else if(!$lt->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP1 . '</td>';
            }
            if(25.5 <= $lt->HP2 &&  $lt->HP2 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP2 . '</td>';
            }
            else if(!$lt->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP2 . '</td>';
            }
            if(25.5 <= $lt->HP3 &&  $lt->HP3 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP3 . '</td>';
            }
            else if(!$lt->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP3 . '</td>';
            }
            if(25.5 <= $lt->HP4 &&  $lt->HP4 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP4 . '</td>';
            }
            else if(!$lt->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP4 . '</td>';
            }
            if(25.5 <= $lt->HP5 &&  $lt->HP5 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP5 . '</td>';
            }
            else if(!$lt->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP5 . '</td>';
            }
            if(25.5 <= $lt->HP6 &&  $lt->HP6 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP6 . '</td>';
            }
            else if(!$lt->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP6 . '</td>';
            }
            if(25.5 <= $lt->HP7 &&  $lt->HP7 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP7 . '</td>';
            }
            else if(!$lt->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP7 . '</td>';
            }
            if(25.5 <= $lt->HP8 &&  $lt->HP8 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP8 . '</td>';
            }
            else if(!$lt->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP8 . '</td>';
            }
            if(25.5 <= $lt->HP9 &&  $lt->HP9 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP9 . '</td>';
            }
            else if(!$lt->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP9 . '</td>';
            }
            if(25.5 <= $lt->HP10 &&  $lt->HP10 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $lt->HP10 . '</td>';
            }
            else if(!$lt->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP10. '</td>';
            }


            if(6.5 <= $lt->LP1 &&  $lt->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP1 . '</td>';
            }
            else if(!$lt->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP1 . '</td>';
            }
            if(6.5 <= $lt->LP2 &&  $lt->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP2 . '</td>';
            }
            else if(!$lt->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP2 . '</td>';
            }
            if(6.5 <= $lt->LP3 &&  $lt->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP3 . '</td>';
            }
            else if(!$lt->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP3 . '</td>';
            }
            if(6.5 <= $lt->LP4 &&  $lt->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP4 . '</td>';
            }
            else if(!$lt->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP4 . '</td>';
            }
            if(6.5 <= $lt->LP5 &&  $lt->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP5 . '</td>';
            }
            else if(!$lt->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP5 . '</td>';
            }
            if(6.5 <= $lt->LP6 &&  $lt->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP6 . '</td>';
            }
            else if(!$lt->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP6 . '</td>';
            }
            if(6.5 <= $lt->LP7 &&  $lt->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP7 . '</td>';
            }
            else if(!$lt->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP7 . '</td>';
            }
            if(6.5 <= $lt->LP8 &&  $lt->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP8 . '</td>';
            }
            else if(!$lt->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP8 . '</td>';
            }
            if(6.5 <= $lt->LP9 &&  $lt->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP9 . '</td>';
            }
            else if(!$lt->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP9 . '</td>';
            }
            if(6.5 <= $lt->LP10 &&  $lt->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP10 . '</td>';
            }
            else if(!$lt->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP10 . '</td>';
            }
            $table_data .= '<td ><p style="margin-bottom:0px" class="text-muted ">'.$row->date_time.'</p></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LM</td>';
            $lm = json_decode($row->lm);
            if(29.5 <= $lm->HP1 &&  $lm->HP1 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP1 . '</td>';
            }
            else if(!$lm->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP1 . '</td>';
            }
            if(29.5 <= $lm->HP2 &&  $lm->HP2 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP2 . '</td>';
            }
            else if(!$lm->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP2 . '</td>';
            }
            if(29.5 <= $lm->HP3 &&  $lm->HP3 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP3 . '</td>';
            }
            else if(!$lm->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP3 . '</td>';
            }
            if(29.5 <= $lm->HP4 &&  $lm->HP4 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP4 . '</td>';
            }
            else if(!$lm->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP4 . '</td>';
            }
            if(29.5 <= $lm->HP5 &&  $lm->HP5 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP5 . '</td>';
            }
            else if(!$lm->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP5 . '</td>';
            }
            if(29.5 <= $lm->HP6 &&  $lm->HP6 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP6 . '</td>';
            }
            else if(!$lm->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP6 . '</td>';
            }
            if(29.5 <= $lm->HP7 &&  $lm->HP7 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP7 . '</td>';
            }
            else if(!$lm->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP7 . '</td>';
            }
            if(29.5 <= $lm->HP8 &&  $lm->HP8 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP8 . '</td>';
            }
            else if(!$lm->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP8 . '</td>';
            }
            if(29.5 <= $lm->HP9 &&  $lm->HP9 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP9 . '</td>';
            }
            else if(!$lm->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP9 . '</td>';
            }
            if(29.5 <= $lm->HP10 &&  $lm->HP10 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $lm->HP10 . '</td>';
            }
            else if(!$lm->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP10 . '</td>';
            }


            if(6.5 <= $lm->LP1 &&  $lm->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP1 . '</td>';
            }
            else if(!$lm->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP1 . '</td>';
            }
            if(6.5 <= $lm->LP2 &&  $lm->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP2 . '</td>';
            }
            else if(!$lm->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP2 . '</td>';
            }
            if(6.5 <= $lm->LP3 &&  $lm->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP3 . '</td>';
            }
            else if(!$lm->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP3 . '</td>';
            }
            if(6.5 <= $lm->LP4 &&  $lm->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP4 . '</td>';
            }
            else if(!$lm->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP4 . '</td>';
            }
            if(6.5 <= $lm->LP5 &&  $lm->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP5 . '</td>';
            }
            else if(!$lm->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP5 . '</td>';
            }
            if(6.5 <= $lm->LP6 &&  $lm->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP6 . '</td>';
            }
            else if(!$lm->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP6 . '</td>';
            }
            if(6.5 <= $lm->LP7 &&  $lm->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP7 . '</td>';
            }
            else if(!$lm->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP7 . '</td>';
            }
            if(6.5 <= $lm->LP8 &&  $lm->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP8 . '</td>';
            }
            else if(!$lm->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP8 . '</td>';
            }
            if(6.5 <= $lm->LP9 &&  $lm->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP9 . '</td>';
            }
            else if(!$lm->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP9 . '</td>';
            }
            if(6.5 <= $lm->LP10 &&  $lm->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP10 . '</td>';
            }
            else if(!$lm->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP10 . '</td>';
            }
            $table_data .= '<td><button style="width:100px;height:30px"  onclick=delete_data('.$this->request->getPost('id').','.$row->id.') class="btn btn-danger btn-sm btn-round">Delete</button></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LB</td>';
            $lb = json_decode($row->lb);
            if(31.5 <= $lb->HP1 &&  $lb->HP1 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP1 . '</td>';
            }
            else if(!$lb->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP1 . '</td>';
            }
            if(31.5 <= $lb->HP2 &&  $lb->HP2 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP2 . '</td>';
            }
            else if(!$lb->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP2 . '</td>';
            }
            if(31.5 <= $lb->HP3 &&  $lb->HP3 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP3 . '</td>';
            }
            else if(!$lb->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP3 . '</td>';
            }
            if(31.5 <= $lb->HP4 &&  $lb->HP4 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP4 . '</td>';
            }
            else if(!$lb->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP4 . '</td>';
            }
            if(31.5 <= $lb->HP5 &&  $lb->HP5 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP5 . '</td>';
            }
            else if(!$lb->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP5 . '</td>';
            }
            if(31.5 <= $lb->HP6 &&  $lb->HP6 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP6 . '</td>';
            }
            else if(!$lb->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP6 . '</td>';
            }
            if(31.5 <= $lb->HP7 &&  $lb->HP7 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP7 . '</td>';
            }
            else if(!$lb->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP7 . '</td>';
            }
            if(31.5 <= $lb->HP8 &&  $lb->HP8 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP8 . '</td>';
            }
            else if(!$lb->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP8 . '</td>';
            }
            if(31.5 <= $lb->HP9 &&  $lb->HP9 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP9 . '</td>';
            }
            else if(!$lb->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP9 . '</td>';
            }
            if(31.5 <= $lb->HP10 &&  $lb->HP10 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $lb->HP10 . '</td>';
            }
            else if(!$lb->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP10 . '</td>';
            }


            if(6.5 <= $lb->LP1 &&  $lb->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP1 . '</td>';
            }
            else if(!$lb->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP1 . '</td>';
            }
            if(6.5 <= $lb->LP2 &&  $lb->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP2 . '</td>';
            }
            else if(!$lb->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP2 . '</td>';
            }
            if(6.5 <= $lb->LP3 &&  $lb->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP3 . '</td>';
            }
            else if(!$lb->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP3 . '</td>';
            }
            if(6.5 <= $lb->LP4 &&  $lb->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP4 . '</td>';
            }
            else if(!$lb->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP4 . '</td>';
            }
            if(6.5 <= $lb->LP5 &&  $lb->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP5 . '</td>';
            }
            else if(!$lb->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP5 . '</td>';
            }
            if(6.5 <= $lb->LP6 &&  $lb->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP6 . '</td>';
            }
            else if(!$lb->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP6 . '</td>';
            }
            if(6.5 <= $lb->LP7 &&  $lb->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP7 . '</td>';
            }
            else if(!$lb->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP7 . '</td>';
            }
            if(6.5 <= $lb->LP8 &&  $lb->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP8 . '</td>';
            }
            else if(!$lb->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP8 . '</td>';
            }
            if(6.5 <= $lb->LP9 &&  $lb->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP9 . '</td>';
            }
            else if(!$lb->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP9 . '</td>';
            }
            if(6.5 <= $lb->LP10 &&  $lb->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP10 . '</td>';
            }
            else if(!$lb->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP10 . '</td>';
            }
            $table_data .= '<td><button style="width:100px;height:30px" onclick=raw_data('.$row->id.','.$this->request->getPost('id').') class="btn btn-info btn-sm btn-round">Row Data</button></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RT</td>';
            $rt = json_decode($row->rt);
            if(25.5 <= $rt->HP1 &&  $rt->HP1 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP1 . '</td>';
            }
            else if(!$rt->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP1 . '</td>';
            }
            if(25.5 <= $rt->HP2 &&  $rt->HP2 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP2 . '</td>';
            }
            else if(!$rt->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP2 . '</td>';
            }
            if(25.5 <= $rt->HP3 &&  $rt->HP3 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP3 . '</td>';
            }
            else if(!$rt->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP3 . '</td>';
            }
            if(25.5 <= $rt->HP4 &&  $rt->HP4 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP4 . '</td>';
            }
            else if(!$rt->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP4 . '</td>';
            }
            if(25.5 <= $rt->HP5 &&  $rt->HP5 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP5 . '</td>';
            }
            else if(!$rt->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP5 . '</td>';
            }
            if(25.5 <= $rt->HP6 &&  $rt->HP6 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP6 . '</td>';
            }
            else if(!$rt->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP6 . '</td>';
            }
            if(25.5 <= $rt->HP7 &&  $rt->HP7 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP7 . '</td>';
            }
            else if(!$rt->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP7 . '</td>';
            }
            if(25.5 <= $rt->HP8 &&  $rt->HP8 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP8 . '</td>';
            }
            else if(!$rt->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP8 . '</td>';
            }
            if(25.5 <= $rt->HP9 &&  $rt->HP9 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP9 . '</td>';
            }
            else if(!$rt->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP9 . '</td>';
            }
            if(25.5 <= $rt->HP10 &&  $rt->HP10 <= 32.5){
                $table_data .= '<td class="text-c-green">' . $rt->HP10 . '</td>';
            }
            else if(!$rt->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP10 . '</td>';
            }


            if(6.5 <= $rt->LP1 &&  $rt->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP1 . '</td>';
            }
            else if(!$rt->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP1 . '</td>';
            }
            if(6.5 <= $rt->LP2 &&  $rt->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP2 . '</td>';
            }
            else if(!$rt->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP2 . '</td>';
            }
            if(6.5 <= $rt->LP3 &&  $rt->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP3 . '</td>';
            }
            else if(!$rt->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP3 . '</td>';
            }
            if(6.5 <= $rt->LP4 &&  $rt->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP4 . '</td>';
            }
            else if(!$rt->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP4 . '</td>';
            }
            if(6.5 <= $rt->LP5 &&  $rt->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP5 . '</td>';
            }
            else if(!$rt->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP5 . '</td>';
            }
            if(6.5 <= $rt->LP6 &&  $rt->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP6 . '</td>';
            }
            else if(!$rt->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP6 . '</td>';
            }
            if(6.5 <= $rt->LP7 &&  $rt->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP7 . '</td>';
            }
            else if(!$rt->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP7 . '</td>';
            }
            if(6.5 <= $rt->LP8 &&  $rt->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP8 . '</td>';
            }
            else if(!$rt->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP8 . '</td>';
            }
            if(6.5 <= $rt->LP9 &&  $rt->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP9 . '</td>';
            }
            else if(!$rt->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP9 . '</td>';
            }
            if(6.5 <= $rt->LP10 &&  $rt->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP10 . '</td>';
            }
            else if(!$rt->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP10 . '</td>';
            }
            $table_data .= '<td><button style="width:100px;height:30px"  onclick=remark('.$row->id.') class="btn btn-success btn-sm btn-round">Remark</button></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RM</td>';
            $rm = json_decode($row->rm);
            if(29.5 <= $rm->HP1 &&  $rm->HP1 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP1 . '</td>';
            }
            else if(!$rm->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP1 . '</td>';
            }
            if(29.5 <= $rm->HP2 &&  $rm->HP2 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP2 . '</td>';
            }
            else if(!$rm->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP2 . '</td>';
            }
            if(29.5 <= $rm->HP3 &&  $rm->HP3 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP3 . '</td>';
            }
            else if(!$rm->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP3 . '</td>';
            }
            if(29.5 <= $rm->HP4 &&  $rm->HP4 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP4 . '</td>';
            }
            else if(!$rm->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP4 . '</td>';
            }
            if(29.5 <= $rm->HP5 &&  $rm->HP5 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP5 . '</td>';
            }
            else if(!$rm->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP5 . '</td>';
            }
            if(29.5 <= $rm->HP6 &&  $rm->HP6 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP6 . '</td>';
            }
            else if(!$rm->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP6 . '</td>';
            }
            if(29.5 <= $rm->HP7 &&  $rm->HP7 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP7 . '</td>';
            }
            else if(!$rm->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP7 . '</td>';
            }
            if(29.5 <= $rm->HP8 &&  $rm->HP8 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP8 . '</td>';
            }
            else if(!$rm->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP8 . '</td>';
            }
            if(29.5 <= $rm->HP9 &&  $rm->HP9 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP9 . '</td>';
            }
            else if(!$rm->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP9 . '</td>';
            }
            if(29.5 <= $rm->HP10 &&  $rm->HP10 <= 36.5){
                $table_data .= '<td class="text-c-green">' . $rm->HP10 . '</td>';
            }
            else if(!$rm->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP10 . '</td>';
            }



            if(6.5 <= $rm->LP1 &&  $rm->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP1 . '</td>';
            }
            else if(!$rm->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP1 . '</td>';
            }
            if(6.5 <= $rm->LP2 &&  $rm->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP2 . '</td>';
            }
            else if(!$rm->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP2 . '</td>';
            }
            if(6.5 <= $rm->LP3 &&  $rm->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP3 . '</td>';
            }
            else if(!$rm->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP3 . '</td>';
            }
            if(6.5 <= $rm->LP4 &&  $rm->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP4 . '</td>';
            }
            else if(!$rm->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP4 . '</td>';
            }
            if(6.5 <= $rm->LP5 &&  $rm->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP5 . '</td>';
            }
            else if(!$rm->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP5 . '</td>';
            }
            if(6.5 <= $rm->LP6 &&  $rm->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP6 . '</td>';
            }
            else if(!$rm->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP6 . '</td>';
            }
            if(6.5 <= $rm->LP7 &&  $rm->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP7 . '</td>';
            }
            else if(!$rm->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP7 . '</td>';
            }
            if(6.5 <= $rm->LP8 &&  $rm->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP8 . '</td>';
            }
            else if(!$rm->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP8 . '</td>';
            }
            if(6.5 <= $rm->LP9 &&  $rm->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP9 . '</td>';
            }
            else if(!$rm->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP9 . '</td>';
            }
            if(6.5 <= $rm->LP10 &&  $rm->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP10 . '</td>';
            }
            else if(!$rm->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP10 . '</td>';
            }
            $table_data .= '<td style="padding-left: 22px;font-size:11px">' . $row->remarks . '</td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RB</td>';
            $rb = json_decode($row->rb);
            if(31.5 <= $rb->HP1 &&  $rb->HP1 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP1 . '</td>';
            }
            else if(!$rb->HP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP1 . '</td>';
            }
            if(31.5 <= $rb->HP2 &&  $rb->HP2 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP2 . '</td>';
            }
            else if(!$rb->HP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP2 . '</td>';
            }
            if(31.5 <= $rb->HP3 &&  $rb->HP3 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP3 . '</td>';
            }
            else if(!$rb->HP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP3 . '</td>';
            }
            if(31.5 <= $rb->HP4 &&  $rb->HP4 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP4 . '</td>';
            }
            else if(!$rb->HP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP4 . '</td>';
            }
            if(31.5 <= $rb->HP5 &&  $rb->HP5 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP5 . '</td>';
            }
            else if(!$rb->HP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP5 . '</td>';
            }
            if(31.5 <= $rb->HP6 &&  $rb->HP6 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP6 . '</td>';
            }
            else if(!$rb->HP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP6 . '</td>';
            }
            if(31.5 <= $rb->HP7 &&  $rb->HP7 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP7 . '</td>';
            }
            else if(!$rb->HP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP7 . '</td>';
            }
            if(31.5 <= $rb->HP8 &&  $rb->HP8 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP8 . '</td>';
            }
            else if(!$rb->HP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP8 . '</td>';
            }
            if(31.5 <= $rb->HP9 &&  $rb->HP9 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP9 . '</td>';
            }
            else if(!$rb->HP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP9 . '</td>';
            }
            if(31.5 <= $rb->HP10 &&  $rb->HP10 <= 38.5){
                $table_data .= '<td class="text-c-green">' . $rb->HP10 . '</td>';
            }
            else if(!$rb->HP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP10 . '</td>';
            }


            if(6.5 <= $rb->LP1 &&  $rb->LP1 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP1 . '</td>';
            }
            else if(!$rb->LP1){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP1 . '</td>';
            }
            if(6.5 <= $rb->LP2 &&  $rb->LP2 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP2 . '</td>';
            }
            else if(!$rb->LP2){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP2 . '</td>';
            }
            if(6.5 <= $rb->LP3 &&  $rb->LP3 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP3 . '</td>';
            }
            else if(!$rb->LP3){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP3 . '</td>';
            }
            if(6.5 <= $rb->LP4 &&  $rb->LP4 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP4 . '</td>';
            }
            else if(!$rb->LP4){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP4 . '</td>';
            }
            if(6.5 <= $rb->LP5 &&  $rb->LP5 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP5 . '</td>';
            }
            else if(!$rb->LP5){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP5 . '</td>';
            }
            if(6.5 <= $rb->LP6 &&  $rb->LP6 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP6 . '</td>';
            }
            else if(!$rb->LP6){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP6 . '</td>';
            }
            if(6.5 <= $rb->LP7 &&  $rb->LP7 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP7 . '</td>';
            }
            else if(!$rb->LP7){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP7 . '</td>';
            }
            if(6.5 <= $rb->LP8 &&  $rb->LP8 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP8 . '</td>';
            }
            else if(!$rb->LP8){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP8 . '</td>';
            }
            if(6.5 <= $rb->LP9 &&  $rb->LP9 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP9 . '</td>';
            }
            else if(!$rb->LP9){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP9 . '</td>';
            }
            if(6.5 <= $rb->LP10 &&  $rb->LP10 <= 13.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP10 . '</td>';
            }
            else if(!$rb->LP10){
                $table_data .= '<td class="text-c-pink">' . "err1". '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
           
        }
        $table_data .= '<tr >';
        $table_data .= '<td colspan="23"  style="font-size: 13px;padding-left:20px ;padding-top:20px"> note : err1 arises due to instability of pressure cycles or not reaching the relevant pressure thresholds.</td></tr>';
        $table_data .= '</tbody>';
        return $this->response->setJSON([
            'table_data' => $table_data
        ]);
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function deleteData()
    {
        
        try{
        $session = session();
        if($session->get('is_super')==1){
            $id = $this->request->getPost('id');
            $data_id = $this->request->getPost('data_id');

            $pneumatic_pair_data = new PneumaticPairData();
            $count = $pneumatic_pair_data->where('pair_id',$id)->countAllResults();
            $pneumatic_pair_data->where('id',$data_id)->delete();
            if($count == 1){
                $pneumatic_pair = new PneumaticPair();
                $pneumatic_pair->where('id',$id)->delete();
                return $this->response->setJSON([
                    'deleted' => 2
                ]);
            }
            return $this->response->setJSON([
                'deleted' => 1
            ]);
        }
        return $this->response->setJSON([
            'deleted' => 0
        ]);
       
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function remarkData()
    {
        
        try{
        $session = session();
        if($session->get('is_super')==1){

            $id = $this->request->getPost('id');
            $remark = $this->request->getPost('value');
            $comment = $this->request->getPost('input');
            if( $this->request->getPost('mode') == 'add'){
                $data = $remark;
                if($comment){
                    $data = $remark." ( ".$comment." ) ";
                }
                $pneumatic_pair_data = new PneumaticPairData();
                $pneumatic_pair_data->update($id,['remarks'		=>  $data]);
            }
            else{
                $pneumatic_pair_data = new PneumaticPairData();
                $pneumatic_pair_data->update($id,['remarks'		=>  '']);
            }
            return $this->response->setJSON([
                'remarked' => 1
            ]);
        }
        return $this->response->setJSON([
            'remarked' => 0
        ]);
       
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function pinData()
    {
        
        try{
        $session = session();
        if($session->get('is_super')==1){
            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');
            $pneumatic_pair = new PneumaticPair();
            $data = $pneumatic_pair->where('id', $id)->first();
            $pneumatic_pair->update($id,['pinned'		=>  $status,'updated_at' =>  $data['updated_at']]);

            return $this->response->setJSON([
                'pinned' => 1
            ]);
        }
        return $this->response->setJSON([
            'pinned' => 0
        ]);
       
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }
   
}