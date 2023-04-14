<?php 
namespace App\Controllers\App; 
use CodeIgniter\Controller;
use App\Models\PneumaticPair;
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
            $date = date("Y-m-d", now("Asia/Colombo"));
                $data = [
                    'date' => $date,
                    't1p' => $pneumatic_pair->where('updated_at >=', $date)->where('pair_status',1)->countAllResults(),
                    't1f' => $pneumatic_pair->where('updated_at >=', $date)->where('pair_status',2)->countAllResults(),
                    't2p' =>$pneumatic_pair->where('updated_at >=', $date)->where('pair_status',4)->countAllResults(),
                    't2f' =>$pneumatic_pair->where('updated_at >=', $date)->where('pair_status',3)->countAllResults(),
                ];
            
		
            echo view('Application/dashboard',$data);
        }
        
    }

    public function getData()
    {
        try{
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $start = $this->request->getPost('start') ?? '';
        $end = $this->request->getPost('end') ?? '';
        $limit = 10; // Items per page
        $offset = ($page - 1) * $limit;
        $summary_count ='';
        
        $pneumatic_pair = new PneumaticPair();
        $pager = \Config\Services::pager(null,null,true);
        if($start && $end){
            $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end);
            $rows = $pneumatic_pair->countAllResults();
            $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
            $t1p = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',1)->countAllResults();
            $t1f = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',2)->countAllResults();
            $t2p = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',4)->countAllResults();
            $t2f = $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end)->where('pair_status',3)->countAllResults();
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
            $pneumatic_pair->like('id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $rows = $pneumatic_pair->countAllResults();
            $pneumatic_pair->like('id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        else{
            $rows = $pneumatic_pair->countAllResults();
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        $links = $pager->makeLinks($page,$limit,$rows);
        $table_data ='<thead>
                        <tr>
                            <th>#</th>
                            <th>Left RFID</th>
                            <th>Right RFID</th>
                            <th>Pneumatic Test</th>
                            <th>Final Inspection</th>
                            <th>Overall Inspection</th>
                            <th>Pneumatic Tested Device</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as  $row) {
            $table_data .= '<tr  onclick=pair_Data('.$row->id.') >';
            $table_data .= '<th>' . $row->id . '</th>';
            $table_data .= '<td>' . $row->left_rfid . '</td>';
            $table_data .= '<td>' . $row->right_rfid . '</td>';
            if($row->pair_status == 2){
                $table_data .= '<td><span class="text-c-pink f-w-600">Fail</span></td>';
            }
            else{
                $table_data .= '<td><span class="text-c-green f-w-600"> Pass </span></td>';
            }
            if($row->final_status == 1){
                $table_data .= '<td><span class="text-c-green f-w-600"><i class="icofont icofont-check-circled"></i> Matched </span></td>';
            }
            else if($row->final_status == 2){
                $table_data .= '<td><span class="text-c-pink f-w-600"><i class="icofont icofont-warning-alt"></i> Mismatched </span></td>';
            }
            else{
                $table_data .= '<td><span class="text-c-yellow f-w-600"><i class="icofont icofont-info-square"></i> Pending </span></td>';
            }
            if($row->final_status == 0){
                $table_data .= '<td><span class="text-c-yellow f-w-600"><i class="icofont icofont-info-square"></i> Pending </span></td>';
            }
            else if($row->pair_status == 1 && $row->final_status == 1){
                $table_data .= '<td><span class="text-c-green f-w-600"><i class="icofont icofont-check-circled"></i> Accepted </span></td>';
            }
            else{
                $table_data .= '<td><span class="text-c-pink f-w-600"><i class="icofont icofont-warning-alt"></i> Rejected </span></td>';
            }
            $table_data .= '<td><p class="text-muted ">'.$row->device.'</p></td>';
            $table_data .= '<td><p class="text-muted ">'.$row->updated_at.'</p></td>';
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
            $pneumatic_pair->where('updated_at >=', $start)->where('updated_at <=', $end);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }
        else if (!empty($filter)) {
            $pneumatic_pair->like('id',$filter)->orLike('left_rfid',$filter)->orLike('right_rfid',$filter);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }
        else{
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get()->getResult();
        }

        $filename = 'report'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");


        // file creation 
        $file = fopen('php://output', 'w');

        $header = array("ID","Left RFID","Right RFID","Pneumatic Test","Final Inspection","Overall Inspection","Last Update"); 
        fputcsv($file, $header);
        foreach ($data as $key=>$row){
            if($row->pair_status == 2){
                $pneumatic_test = "pass" ;
            }
            else{
                $pneumatic_test = "fail" ;
            }
            if($row->final_status == 1){
                $final_test = "matched" ;            
            }
            else if($row->final_status == 2){
                $final_test = "mismatched" ;            
            }
            else{
                $final_test = "pending" ;            
            }
            if($row->final_status == 0){
                $overall = "pending" ;            }
            else if($row->pair_status == 1 && $row->final_status == 1){
                $overall = "accepted" ;               
            }
            else{
                $overall = "rejected" ;              
            }
           
           
            
            fputcsv($row->id,$row->left_rfid,$row->right_rfid,$pneumatic_test,$final_test,$overall,$row->updated_at); 
        }
        $data2 = file_get_contents('php://output'); 
        fclose($file); 
        helper("filesystem");
        $mim = 'csv';
        return $this->response
                ->setHeader('Content-Type', $mim)
                ->setHeader('Content-disposition', 'inline; filename="report.csv"')
                ->setStatusCode(200)
                ->setBody($data2);

        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function pairData()
    {
        try{
        $pneumatic_pair_data = new PneumaticPairData();
        $data =  $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->orderBy('date_time','desc')->get()->getResult();
        $table_data ='<input type="hidden" id="pair" value="'.$this->request->getPost('id').'" /><thead>
                        <tr>
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
        foreach ($data as  $row) {
            $table_data .= '<tr >';
            $table_data .= '<th>' . $row->id . '</th>';
            $table_data .= '<td>LT</td>';
            $lt = json_decode($row->lt);
            if(26.4 <= $lt->HP1 &&  $lt->HP1 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP1 . '</td>';
            }
            if(26.4 <= $lt->HP2 &&  $lt->HP2 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP2 . '</td>';
            }
            if(26.4 <= $lt->HP3 &&  $lt->HP3 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP3 . '</td>';
            }
            if(26.4 <= $lt->HP4 &&  $lt->HP4 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP4 . '</td>';
            }
            if(26.4 <= $lt->HP5 &&  $lt->HP5 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP5 . '</td>';
            }
            if(26.4 <= $lt->HP6 &&  $lt->HP6 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP6 . '</td>';
            }
            if(26.4 <= $lt->HP7 &&  $lt->HP7 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP7 . '</td>';
            }
            if(26.4 <= $lt->HP8 &&  $lt->HP8 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP8 . '</td>';
            }
            if(26.4 <= $lt->HP9 &&  $lt->HP9 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP9 . '</td>';
            }
            if(26.4 <= $lt->HP10 &&  $lt->HP10 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $lt->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->HP10 . '</td>';
            }


            if(7.5 <= $lt->LP1 &&  $lt->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP1 . '</td>';
            }
            if(7.5 <= $lt->LP2 &&  $lt->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP2 . '</td>';
            }
            if(7.5 <= $lt->LP3 &&  $lt->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP3 . '</td>';
            }
            if(7.5 <= $lt->LP4 &&  $lt->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP4 . '</td>';
            }
            if(7.5 <= $lt->LP5 &&  $lt->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP5 . '</td>';
            }
            if(7.5 <= $lt->LP6 &&  $lt->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP6 . '</td>';
            }
            if(7.5 <= $lt->LP7 &&  $lt->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP7 . '</td>';
            }
            if(7.5 <= $lt->LP8 &&  $lt->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP8 . '</td>';
            }
            if(7.5 <= $lt->LP9 &&  $lt->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP9 . '</td>';
            }
            if(7.5 <= $lt->LP10 &&  $lt->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lt->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lt->LP10 . '</td>';
            }
            $table_data .= '<td><p class="text-muted ">'.$row->date_time.'</p></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LM</td>';
            $lm = json_decode($row->lm);
            if(30.4 <= $lm->HP1 &&  $lm->HP1 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP1 . '</td>';
            }
            if(30.4 <= $lm->HP2 &&  $lm->HP2 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP2 . '</td>';
            }
            if(30.4 <= $lm->HP3 &&  $lm->HP3 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP3 . '</td>';
            }
            if(30.4 <= $lm->HP4 &&  $lm->HP4 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP4 . '</td>';
            }
            if(30.4 <= $lm->HP5 &&  $lm->HP5 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP5 . '</td>';
            }
            if(30.4 <= $lm->HP6 &&  $lm->HP6 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP6 . '</td>';
            }
            if(30.4 <= $lm->HP7 &&  $lm->HP7 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP7 . '</td>';
            }
            if(30.4 <= $lm->HP8 &&  $lm->HP8 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP8 . '</td>';
            }
            if(30.4 <= $lm->HP9 &&  $lm->HP9 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP9 . '</td>';
            }
            if(30.4 <= $lm->HP10 &&  $lm->HP10 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $lm->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->HP10 . '</td>';
            }


            if(7.5 <= $lm->LP1 &&  $lm->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP1 . '</td>';
            }
            if(7.5 <= $lm->LP2 &&  $lm->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP2 . '</td>';
            }
            if(7.5 <= $lm->LP3 &&  $lm->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP3 . '</td>';
            }
            if(7.5 <= $lm->LP4 &&  $lm->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP4 . '</td>';
            }
            if(7.5 <= $lm->LP5 &&  $lm->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP5 . '</td>';
            }
            if(7.5 <= $lm->LP6 &&  $lm->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP6 . '</td>';
            }
            if(7.5 <= $lm->LP7 &&  $lm->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP7 . '</td>';
            }
            if(7.5 <= $lm->LP8 &&  $lm->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP8 . '</td>';
            }
            if(7.5 <= $lm->LP9 &&  $lm->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP9 . '</td>';
            }
            if(7.5 <= $lm->LP10 &&  $lm->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lm->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lm->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LB</td>';
            $lb = json_decode($row->lb);
            if(32.4 <= $lb->HP1 &&  $lb->HP1 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP1 . '</td>';
            }
            if(32.4 <= $lb->HP2 &&  $lb->HP2 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP2 . '</td>';
            }
            if(32.4 <= $lb->HP3 &&  $lb->HP3 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP3 . '</td>';
            }
            if(32.4 <= $lb->HP4 &&  $lb->HP4 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP4 . '</td>';
            }
            if(32.4 <= $lb->HP5 &&  $lb->HP5 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP5 . '</td>';
            }
            if(32.4 <= $lb->HP6 &&  $lb->HP6 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP6 . '</td>';
            }
            if(32.4 <= $lb->HP7 &&  $lb->HP7 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP7 . '</td>';
            }
            if(32.4 <= $lb->HP8 &&  $lb->HP8 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP8 . '</td>';
            }
            if(32.4 <= $lb->HP9 &&  $lb->HP9 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP9 . '</td>';
            }
            if(32.4 <= $lb->HP10 &&  $lb->HP10 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $lb->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->HP10 . '</td>';
            }


            if(7.5 <= $lb->LP1 &&  $lb->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP1 . '</td>';
            }
            if(7.5 <= $lb->LP2 &&  $lb->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP2 . '</td>';
            }
            if(7.5 <= $lb->LP3 &&  $lb->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP3 . '</td>';
            }
            if(7.5 <= $lb->LP4 &&  $lb->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP4 . '</td>';
            }
            if(7.5 <= $lb->LP5 &&  $lb->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP5 . '</td>';
            }
            if(7.5 <= $lb->LP6 &&  $lb->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP6 . '</td>';
            }
            if(7.5 <= $lb->LP7 &&  $lb->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP7 . '</td>';
            }
            if(7.5 <= $lb->LP8 &&  $lb->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP8 . '</td>';
            }
            if(7.5 <= $lb->LP9 &&  $lb->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP9 . '</td>';
            }
            if(7.5 <= $lb->LP10 &&  $lb->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $lb->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $lb->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RT</td>';
            $rt = json_decode($row->rt);
            if(26.4 <= $rt->HP1 &&  $rt->HP1 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP1 . '</td>';
            }
            if(26.4 <= $rt->HP2 &&  $rt->HP2 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP2 . '</td>';
            }
            if(26.4 <= $rt->HP3 &&  $rt->HP3 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP3 . '</td>';
            }
            if(26.4 <= $rt->HP4 &&  $rt->HP4 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP4 . '</td>';
            }
            if(26.4 <= $rt->HP5 &&  $rt->HP5 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP5 . '</td>';
            }
            if(26.4 <= $rt->HP6 &&  $rt->HP6 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP6 . '</td>';
            }
            if(26.4 <= $rt->HP7 &&  $rt->HP7 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP7 . '</td>';
            }
            if(26.4 <= $rt->HP8 &&  $rt->HP8 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP8 . '</td>';
            }
            if(26.4 <= $rt->HP9 &&  $rt->HP9 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP9 . '</td>';
            }
            if(26.4 <= $rt->HP10 &&  $rt->HP10 <= 31.6){
                $table_data .= '<td class="text-c-green">' . $rt->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->HP10 . '</td>';
            }


            if(7.5 <= $rt->LP1 &&  $rt->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP1 . '</td>';
            }
            if(7.5 <= $rt->LP2 &&  $rt->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP2 . '</td>';
            }
            if(7.5 <= $rt->LP3 &&  $rt->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP3 . '</td>';
            }
            if(7.5 <= $rt->LP4 &&  $rt->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP4 . '</td>';
            }
            if(7.5 <= $rt->LP5 &&  $rt->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP5 . '</td>';
            }
            if(7.5 <= $rt->LP6 &&  $rt->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP6 . '</td>';
            }
            if(7.5 <= $rt->LP7 &&  $rt->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP7 . '</td>';
            }
            if(7.5 <= $rt->LP8 &&  $rt->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP8 . '</td>';
            }
            if(7.5 <= $rt->LP9 &&  $rt->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP9 . '</td>';
            }
            if(7.5 <= $rt->LP10 &&  $rt->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rt->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rt->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RM</td>';
            $rm = json_decode($row->rm);
            if(30.4 <= $rm->HP1 &&  $rm->HP1 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP1 . '</td>';
            }
            if(30.4 <= $rm->HP2 &&  $rm->HP2 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP2 . '</td>';
            }
            if(30.4 <= $rm->HP3 &&  $rm->HP3 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP3 . '</td>';
            }
            if(30.4 <= $rm->HP4 &&  $rm->HP4 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP4 . '</td>';
            }
            if(30.4 <= $rm->HP5 &&  $rm->HP5 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP5 . '</td>';
            }
            if(30.4 <= $rm->HP6 &&  $rm->HP6 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP6 . '</td>';
            }
            if(30.4 <= $rm->HP7 &&  $rm->HP7 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP7 . '</td>';
            }
            if(30.4 <= $rm->HP8 &&  $rm->HP8 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP8 . '</td>';
            }
            if(30.4 <= $rm->HP9 &&  $rm->HP9 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP9 . '</td>';
            }
            if(30.4 <= $rm->HP10 &&  $rm->HP10 <= 35.6){
                $table_data .= '<td class="text-c-green">' . $rm->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->HP10 . '</td>';
            }



            if(7.5 <= $rm->LP1 &&  $rm->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP1 . '</td>';
            }
            if(7.5 <= $rm->LP2 &&  $rm->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP2 . '</td>';
            }
            if(7.5 <= $rm->LP3 &&  $rm->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP3 . '</td>';
            }
            if(7.5 <= $rm->LP4 &&  $rm->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP4 . '</td>';
            }
            if(7.5 <= $rm->LP5 &&  $rm->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP5 . '</td>';
            }
            if(7.5 <= $rm->LP6 &&  $rm->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP6 . '</td>';
            }
            if(7.5 <= $rm->LP7 &&  $rm->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP7 . '</td>';
            }
            if(7.5 <= $rm->LP8 &&  $rm->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP8 . '</td>';
            }
            if(7.5 <= $rm->LP9 &&  $rm->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP9 . '</td>';
            }
            if(7.5 <= $rm->LP10 &&  $rm->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rm->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rm->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RB</td>';
            $rb = json_decode($row->rb);
            if(32.4 <= $rb->HP1 &&  $rb->HP1 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP1 . '</td>';
            }
            if(32.4 <= $rb->HP2 &&  $rb->HP2 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP2 . '</td>';
            }
            if(32.4 <= $rb->HP3 &&  $rb->HP3 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP3 . '</td>';
            }
            if(32.4 <= $rb->HP4 &&  $rb->HP4 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP4 . '</td>';
            }
            if(32.4 <= $rb->HP5 &&  $rb->HP5 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP5 . '</td>';
            }
            if(32.4 <= $rb->HP6 &&  $rb->HP6 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP6 . '</td>';
            }
            if(32.4 <= $rb->HP7 &&  $rb->HP7 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP7 . '</td>';
            }
            if(32.4 <= $rb->HP8 &&  $rb->HP8 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP8 . '</td>';
            }
            if(32.4 <= $rb->HP9 &&  $rb->HP9 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP9 . '</td>';
            }
            if(32.4 <= $rb->HP10 &&  $rb->HP10 <= 37.6){
                $table_data .= '<td class="text-c-green">' . $rb->HP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->HP10 . '</td>';
            }


            if(7.5 <= $rb->LP1 &&  $rb->LP1 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP1 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP1 . '</td>';
            }
            if(7.5 <= $rb->LP2 &&  $rb->LP2 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP2 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP2 . '</td>';
            }
            if(7.5 <= $rb->LP3 &&  $rb->LP3 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP3 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP3 . '</td>';
            }
            if(7.5 <= $rb->LP4 &&  $rb->LP4 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP4 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP4 . '</td>';
            }
            if(7.5 <= $rb->LP5 &&  $rb->LP5 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP5 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP5 . '</td>';
            }
            if(7.5 <= $rb->LP6 &&  $rb->LP6 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP6 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP6 . '</td>';
            }
            if(7.5 <= $rb->LP7 &&  $rb->LP7 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP7 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP7 . '</td>';
            }
            if(7.5 <= $rb->LP8 &&  $rb->LP8 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP8 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP8 . '</td>';
            }
            if(7.5 <= $rb->LP9 &&  $rb->LP9 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP9 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP9 . '</td>';
            }
            if(7.5 <= $rb->LP10 &&  $rb->LP10 <= 12.5){
                $table_data .= '<td class="text-c-green">' . $rb->LP10 . '</td>';
            }
            else{
                $table_data .= '<td class="text-c-pink">' . $rb->LP10 . '</td>';
            }
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
           
        }
        $table_data .= '</tbody>';
        return $this->response->setJSON([
            'table_data' => $table_data
        ]);
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }
   
}