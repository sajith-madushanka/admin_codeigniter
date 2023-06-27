<?php 
namespace App\Controllers\App; 
use CodeIgniter\Controller;
use App\Models\Battery;
use CodeIgniter\API\ResponseTrait;
  
class BatteryDataController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        helper('date');
        if ($this->request->is('get')) {
            echo view('Application/battery');
        }
        
    }

    public function getBatteryData()
    {
        try{
        $session = session();
        $filter = $this->request->getPost('keyword') ?? '';
        $page = $this->request->getPost('page') ?? 1;
        $limit = 15; // Items per page
        $offset = ($page - 1) * $limit;
        $summary_count ='';
        
        $battery = new Battery();
        $pager = \Config\Services::pager(null,null,true);
        if (!empty($filter)) {
            $battery->like('id',$filter);
            $rows = $battery->countAllResults();
            $battery->like('id',$filter);
            $data =  $battery->orderBy('test_date','desc')->get($limit,$offset)->getResult();
        }
        else{
            $rows = $battery->countAllResults();
            $data =  $battery->orderBy('test_date','desc')->get($limit,$offset)->getResult();
        }
        $links = $pager->makeLinks($page,$limit,$rows);
        
        $table_data ='<thead>
                        <tr class="stickey_head">
                            <th>#</th>
                            <th> Last Voltage </th>
                            <th> Test Status </th>
                            <th>Test Date</th>
                            <th>Tester ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                            
        foreach ($data as  $row) {
            $table_data .= '<tr>';
            $table_data .= '<th >' . $row->id . '</th>';
            $table_data .= '<td  style="font-size: 13px">' . $row->voltage . '</td>';
            if($row->status == 0){
                $table_data .= '<td><span class="text-c-pink f-w-600">Fail</span></td>';
            }
            else{
                $table_data .= '<td><span class="text-c-green f-w-600"> Pass </span></td>';
            }
            $table_data .= '<td ><p class="text-muted ">'.$row->test_date.'</p></td>';
            $table_data .= '<td ><p class="text-muted ">'.$row->device.'</p></td>';
            $table_data .= '<td><button style="width:100px;height:30px"  onclick=delete_battery_data('.$row->id.') class="btn btn-danger btn-sm btn-round">Delete</button></td>';
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

    public function deleteData()
    {
        
        try{
        $session = session();
        if($session->get('is_super')==1){
            $id = $this->request->getPost('id');

            $pneumatic_pair_data = new Battery();
            $pneumatic_pair_data->where('id',$id)->delete();
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
}