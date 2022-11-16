<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DataFetchController extends Controller
{
    public function fetchBrands(Request $request){
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('brands')
                    ->where($select, $value)
                    ->get();
        $output = '<option value="">Select Brand</option>';
        foreach($data as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        echo $output;
    }
    public function fetchCampaigns(Request $request){
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('campaigns')
                    ->where($select, $value)
                    ->get();
        $output = '<option value="">Select Campaign</option>';
        foreach($data as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        echo $output;
    }
    public function fetchSenderNames(Request $request){
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('sender_names')
                    ->where($select, $value)
                    ->get();
        $output = '<option value="">Select Sender Name</option>';
        foreach($data as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->short_code.'</option>';
        }
        echo $output;
    }
}
