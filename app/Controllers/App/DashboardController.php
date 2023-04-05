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
        if ($this->request->is('get')) {
            $pneumatic_pair = new PneumaticPair();
                $data = [
                    'pairs' => $pneumatic_pair->paginate(6),
                    'pager' => $pneumatic_pair->pager
                ];
		
            echo view('Application/dashboard',$data);
        } elseif ($this->request->is('post')) {
            $session = session();
       
        }
        
    }

    public function getData()
    {
        try{
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $limit = 5; // Items per page
        $offset = ($page - 1) * $limit;
        
        $pneumatic_pair = new PneumaticPair();
        $pneumatic_pair_data = new PneumaticPairData();
        $pager = \Config\Services::pager(null,null,true);
        if (!empty($filter)) {
            $pneumatic_pair->like('id',$filter)->orLike('left_rfid',$filter);
            $rows = $pneumatic_pair->countAllResults();
            $pneumatic_pair->like('id',$filter)->orLike('left_rfid',$filter);
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        else{
            $rows = $pneumatic_pair->countAll();
            $data =  $pneumatic_pair->orderBy('updated_at','desc')->get($limit,$offset)->getResult();
        }
        $links = $pager->makeLinks($page,$limit,$rows);
        foreach ($data as &$pair) {
            $pair->pair_data = $pneumatic_pair_data->where('pair_id', $pair->id)->orderBy('date_time','desc')->get()->getResult();
        }
        $table_data ='<thead>
                        <tr>
                            <th>#</th>
                            <th>left RFID</th>
                            <th>right RFID</th>
                            <th>Pair Status</th>
                            <th>Left Status</th>
                            <th>Right Status</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as  $row) {
            $table_data .= '<tr  onclick=pair_Data('.$row->id.') >';
            $table_data .= '<th>' . $row->id . '</th>';
            $table_data .= '<td>' . $row->left_rfid . '</td>';
            $table_data .= '<td>' . $row->right_rfid . '</td>';
            if($row->pair_status == 1){
                $table_data .= '<td><span class="text-c-green f-w-600"><i class="icofont icofont-info-square"></i> Accepted</span></td>';
            }
            else if($row->pair_status == 2){
                $table_data .= '<td><span class="text-c-pink f-w-600"><i class="icofont icofont-warning-alt"></i> Rejected</span></td>';
            }
            else if($row->pair_status == 3){
                $table_data .= '<td><span class="text-c-blue f-w-600"><i class="icofont icofont-check-circled"></i> Finished</span></td>';
            }
            if($row->pair_data[0]){
                if($row->pair_data[0]->left_status){
                    $table_data .= '<td><span class="text-c-green f-w-600"> Active</span></td>';
                }
                else{
                    $table_data .= '<td><span class="text-c-pink f-w-600"> Fail</span></td>';
                }
                if($row->pair_data[0]->right_status){
                    $table_data .= '<td><span class="text-c-green f-w-600"> Active</span></td>';
                }
                else{
                    $table_data .= '<td><span class="text-c-pink f-w-600"> Fail</span></td>';
                }
            }
            else{
                $table_data .= '<td><span class="text-c-yellow f-w-600"> No records</span></td>';
                $table_data .= '<td><span class="text-c-yellow f-w-600"> No records</span></td>';
            }
            $table_data .= '<td><p class="text-muted ">'.$row->updated_at.'</p></td>';
            $table_data .= '</tr>';
        }
        $table_data .= '</tbody>';
        return $this->response->setJSON([
            'table_data' => $table_data,
            'links'=>$links
        ]);
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    public function pairData()
    {
        try{
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $start = $this->request->getPost('start') ?? '';
        $end = $this->request->getPost('end') ?? '';
        $limit = 2; // Items per page
        $offset = ($page - 1) * $limit;
        
        //$pneumatic_pair = new PneumaticPair();
        $pneumatic_pair_data = new PneumaticPairData();
        $pager = \Config\Services::pager(null,null,true);
        if ($start && $end) {
            $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->where('date_time >=', $start)->where('date_time <=', $end);
            $rows = $pneumatic_pair_data->countAllResults();
            $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->where('date_time >=', $start)->where('date_time <=', $end);
            $data =  $pneumatic_pair_data->orderBy('date_time','desc')->get($limit,$offset)->getResult();
        }
        else{
            $rows = $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->countAllResults();
            $data =  $pneumatic_pair_data->where('pair_id', $this->request->getPost('id'))->orderBy('date_time','desc')->get($limit,$offset)->getResult();
        }
        
        $links = $pager->makeLinks($page,$limit,$rows);
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
            $table_data .= '<td>' . $lt->HP1 . '</td>';
            $table_data .= '<td>' . $lt->HP2 . '</td>';
            $table_data .= '<td>' . $lt->HP3 . '</td>';
            $table_data .= '<td>' . $lt->HP4 . '</td>';
            $table_data .= '<td>' . $lt->HP5 . '</td>';
            $table_data .= '<td>' . $lt->HP6 . '</td>';
            $table_data .= '<td>' . $lt->HP7 . '</td>';
            $table_data .= '<td>' . $lt->HP8 . '</td>';
            $table_data .= '<td>' . $lt->HP9 . '</td>';
            $table_data .= '<td>' . $lt->HP10 . '</td>';
            $table_data .= '<td>' . $lt->LP1 . '</td>';
            $table_data .= '<td>' . $lt->LP2 . '</td>';
            $table_data .= '<td>' . $lt->LP3 . '</td>';
            $table_data .= '<td>' . $lt->LP4 . '</td>';
            $table_data .= '<td>' . $lt->LP5 . '</td>';
            $table_data .= '<td>' . $lt->LP6 . '</td>';
            $table_data .= '<td>' . $lt->LP7 . '</td>';
            $table_data .= '<td>' . $lt->LP8 . '</td>';
            $table_data .= '<td>' . $lt->LP9 . '</td>';
            $table_data .= '<td>' . $lt->LP10 . '</td>';
            $table_data .= '<td><p class="text-muted ">'.$row->date_time.'</p></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LM</td>';
            $lm = json_decode($row->lm);
            $table_data .= '<td>' . $lm->HP1 . '</td>';
            $table_data .= '<td>' . $lm->HP2 . '</td>';
            $table_data .= '<td>' . $lm->HP3 . '</td>';
            $table_data .= '<td>' . $lm->HP4 . '</td>';
            $table_data .= '<td>' . $lm->HP5 . '</td>';
            $table_data .= '<td>' . $lm->HP6 . '</td>';
            $table_data .= '<td>' . $lm->HP7 . '</td>';
            $table_data .= '<td>' . $lm->HP8 . '</td>';
            $table_data .= '<td>' . $lm->HP9 . '</td>';
            $table_data .= '<td>' . $lm->HP10 . '</td>';
            $table_data .= '<td>' . $lm->LP1 . '</td>';
            $table_data .= '<td>' . $lm->LP2 . '</td>';
            $table_data .= '<td>' . $lm->LP3 . '</td>';
            $table_data .= '<td>' . $lm->LP4 . '</td>';
            $table_data .= '<td>' . $lm->LP5 . '</td>';
            $table_data .= '<td>' . $lm->LP6 . '</td>';
            $table_data .= '<td>' . $lm->LP7 . '</td>';
            $table_data .= '<td>' . $lm->LP8 . '</td>';
            $table_data .= '<td>' . $lm->LP9 . '</td>';
            $table_data .= '<td>' . $lm->LP10 . '</td>';
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>LB</td>';
            $lb = json_decode($row->lb);
            $table_data .= '<td>' . $lb->HP1 . '</td>';
            $table_data .= '<td>' . $lb->HP2 . '</td>';
            $table_data .= '<td>' . $lb->HP3 . '</td>';
            $table_data .= '<td>' . $lb->HP4 . '</td>';
            $table_data .= '<td>' . $lb->HP5 . '</td>';
            $table_data .= '<td>' . $lb->HP6 . '</td>';
            $table_data .= '<td>' . $lb->HP7 . '</td>';
            $table_data .= '<td>' . $lb->HP8 . '</td>';
            $table_data .= '<td>' . $lb->HP9 . '</td>';
            $table_data .= '<td>' . $lb->HP10 . '</td>';
            $table_data .= '<td>' . $lb->LP1 . '</td>';
            $table_data .= '<td>' . $lb->LP2 . '</td>';
            $table_data .= '<td>' . $lb->LP3 . '</td>';
            $table_data .= '<td>' . $lb->LP4 . '</td>';
            $table_data .= '<td>' . $lb->LP5 . '</td>';
            $table_data .= '<td>' . $lb->LP6 . '</td>';
            $table_data .= '<td>' . $lb->LP7 . '</td>';
            $table_data .= '<td>' . $lb->LP8 . '</td>';
            $table_data .= '<td>' . $lb->LP9 . '</td>';
            $table_data .= '<td>' . $lb->LP10 . '</td>';
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RT</td>';
            $rt = json_decode($row->rt);
            $table_data .= '<td>' . $rt->HP1 . '</td>';
            $table_data .= '<td>' . $rt->HP2 . '</td>';
            $table_data .= '<td>' . $rt->HP3 . '</td>';
            $table_data .= '<td>' . $rt->HP4 . '</td>';
            $table_data .= '<td>' . $rt->HP5 . '</td>';
            $table_data .= '<td>' . $rt->HP6 . '</td>';
            $table_data .= '<td>' . $rt->HP7 . '</td>';
            $table_data .= '<td>' . $rt->HP8 . '</td>';
            $table_data .= '<td>' . $rt->HP9 . '</td>';
            $table_data .= '<td>' . $rt->HP10 . '</td>';
            $table_data .= '<td>' . $rt->LP1 . '</td>';
            $table_data .= '<td>' . $rt->LP2 . '</td>';
            $table_data .= '<td>' . $rt->LP3 . '</td>';
            $table_data .= '<td>' . $rt->LP4 . '</td>';
            $table_data .= '<td>' . $rt->LP5 . '</td>';
            $table_data .= '<td>' . $rt->LP6 . '</td>';
            $table_data .= '<td>' . $rt->LP7 . '</td>';
            $table_data .= '<td>' . $rt->LP8 . '</td>';
            $table_data .= '<td>' . $rt->LP9 . '</td>';
            $table_data .= '<td>' . $rt->LP10 . '</td>';
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RM</td>';
            $rm = json_decode($row->rm);
            $table_data .= '<td>' . $rm->HP1 . '</td>';
            $table_data .= '<td>' . $rm->HP2 . '</td>';
            $table_data .= '<td>' . $rm->HP3 . '</td>';
            $table_data .= '<td>' . $rm->HP4 . '</td>';
            $table_data .= '<td>' . $rm->HP5 . '</td>';
            $table_data .= '<td>' . $rm->HP6 . '</td>';
            $table_data .= '<td>' . $rm->HP7 . '</td>';
            $table_data .= '<td>' . $rm->HP8 . '</td>';
            $table_data .= '<td>' . $rm->HP9 . '</td>';
            $table_data .= '<td>' . $rm->HP10 . '</td>';
            $table_data .= '<td>' . $rm->LP1 . '</td>';
            $table_data .= '<td>' . $rm->LP2 . '</td>';
            $table_data .= '<td>' . $rm->LP3 . '</td>';
            $table_data .= '<td>' . $rm->LP4 . '</td>';
            $table_data .= '<td>' . $rm->LP5 . '</td>';
            $table_data .= '<td>' . $rm->LP6 . '</td>';
            $table_data .= '<td>' . $rm->LP7 . '</td>';
            $table_data .= '<td>' . $rm->LP8 . '</td>';
            $table_data .= '<td>' . $rm->LP9 . '</td>';
            $table_data .= '<td>' . $rm->LP10 . '</td>';
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
            $table_data .= '<tr >';
            $table_data .= '<th> </th>';
            $table_data .= '<td>RB</td>';
            $rb = json_decode($row->rb);
            $table_data .= '<td>' . $rb->HP1 . '</td>';
            $table_data .= '<td>' . $rb->HP2 . '</td>';
            $table_data .= '<td>' . $rb->HP3 . '</td>';
            $table_data .= '<td>' . $rb->HP4 . '</td>';
            $table_data .= '<td>' . $rb->HP5 . '</td>';
            $table_data .= '<td>' . $rb->HP6 . '</td>';
            $table_data .= '<td>' . $rb->HP7 . '</td>';
            $table_data .= '<td>' . $rb->HP8 . '</td>';
            $table_data .= '<td>' . $rb->HP9 . '</td>';
            $table_data .= '<td>' . $rb->HP10 . '</td>';
            $table_data .= '<td>' . $rb->LP1 . '</td>';
            $table_data .= '<td>' . $rb->LP2 . '</td>';
            $table_data .= '<td>' . $rb->LP3 . '</td>';
            $table_data .= '<td>' . $rb->LP4 . '</td>';
            $table_data .= '<td>' . $rb->LP5 . '</td>';
            $table_data .= '<td>' . $rb->LP6 . '</td>';
            $table_data .= '<td>' . $rb->LP7 . '</td>';
            $table_data .= '<td>' . $rb->LP8 . '</td>';
            $table_data .= '<td>' . $rb->LP9 . '</td>';
            $table_data .= '<td>' . $rb->LP10 . '</td>';
            $table_data .= '<td></td>';
            $table_data .= '</tr >';
           
        }
        $table_data .= '</tbody>';
        return $this->response->setJSON([
            'table_data' => $table_data,
            'links'=>$links
        ]);
        } catch (\Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }
   
}