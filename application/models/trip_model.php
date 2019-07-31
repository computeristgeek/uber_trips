<?php
class Trip_model extends CI_Model {

    public function count_trip()
    {
        //set last trip timestamp for reset check
        if($this->db->set('last_trip_ts','current_timestamp',FALSE)
            ->update('reset_table'))
        {
            $id=$this->session->userdata('identity');
            $this->db->set('month_count', 'month_count+1', FALSE);
            $this->db->set('year_count', 'year_count+1', FALSE);
            $this->db->set('all_count', 'all_count+1', FALSE);
            $this->db->where('id',$id);
            return array('status'=>$this->db->update('trip_counts'));
        }
        else
        {
            return array('status'=>0);
        }
    }

    public function get_monopoly()
    {
        //set column names
        $allcount_name='ALL_COUNT';
        $monthcount_name='MONTH_COUNT';
        $yearcount_name='YEAR_COUNT';

        //get column sums 
        $counts=array(
            $allcount_name=>0,
            $monthcount_name=>0,
            $yearcount_name=>0,
        );
        $q=$this->db
            ->select_sum($allcount_name,$allcount_name)
            ->select_sum($monthcount_name,$monthcount_name)
            ->select_sum($yearcount_name,$yearcount_name)
            ->get('trip_counts');
        if($q) $counts=$q->row_array();

        //get monopoly list where counts > 0.1 (10%)
        //for every column
        $monopoly_list=array(
            $allcount_name=>array(),
            $monthcount_name=>array(),
            $yearcount_name=>array(),
        );

        foreach($monopoly_list as $key=>&$val)
        {
            $q=$this->db
                ->select('drivers.full_name')
                ->from('trip_counts')
                ->join('drivers','drivers.id=trip_counts.id')
                ->group_by('drivers.id')
                ->having("sum($key)>0.1*{$counts["$key"]}")
                ->get();
            if($q) $val=$q->result_array();
        }
        return $monopoly_list;
    }

    public function reset_counts()
    {
        $success=true;
        //returns reset table which has last_trip
        $q=$this->db
            ->select('(case when month(current_timestamp)!=month(last_trip_ts) then 1 else 0 end) clear_month','(case when year(current_timestamp)!=year(last_trip_ts) then 1 else 0 end) clear_year')
            ->get('reset_table');
        if($q) $clear_data=$q->row_array();

        //clear month counts or year counts if last_trip is different from current_timestamp
        if($clear_data && ($clear_data['clear_month'] || $clear_data['clear_year']))
        {
            //reset the whole month count if entered a new month or year
            $success=$success && $this->db
                ->set('month_count',0)
                ->update('trip_counts');
        }
        if($clear_data && $clear_data['clear_year'])
        {
            //reset the whole year count if entered a new year 
            $success=$success && $this->db
                ->set('year_count',0)
                ->update('trip_counts');
        }
        return $success;
    }

}
?>
