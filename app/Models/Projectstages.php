<?php

namespace App\Models;

use App\Models\Timesheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Projectstages extends Model
{
    protected $fillable = [
        'name',
        'color',
        'created_by',
        'order',
    ];


    protected $hidden = [];


    public function tasks($project_id)
    {
        $teamMembers = [];
        if (\Auth::user()->type == 'client' || \Auth::user()->type == 'company' || \Auth::user()->type == 'PMO') {
            return Task::where('stage', '=', $this->id)->where('project_id', '=', $project_id)->orderBy('order')->get();
        } else {
            $teamMembers = Utility::getTeamMembers(\Auth::user()->id);

            $teamMembers[] = \Auth::user()->id;
            $teamMembers[] = "-1";

            $teamMembers = implode('|', $teamMembers);
            $taskDetail =  Task::where('stage', '=', $this->id)->where('assign_to', 'REGEXP', $teamMembers)->where('project_id', '=', $project_id)->orderBy('order')->get();


            //dd(DB::getQueryLog());
            return $taskDetail;
        }
    }

    public static function getChartData()
    {
        $usr     = \Auth::user();
        $m       = date("m");
        $de      = date("d");
        $y       = date("Y");
        $format  = 'Y-m-d';
        $arrDate = [];
        $arrDay  = [];

        for ($i = 0; $i <= 7 - 1; $i++) {
            $date              = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDay['label'][] = __(date('d-M', mktime(0, 0, 0, $m, ($de - $i), $y)));
            $arrDate[]         = $date;
        }

        $stages  = Projectstages::where('created_by', '=', $usr->creatorId())->get();
        $arrTask = [];

        $i = 0;
        if ($usr->type == 'company') {
            foreach ($stages as $key => $stage) {
                $data = [];
                foreach ($arrDate as $d) {
                    $data[] = Task::where('stage', '=', $stage->id)->whereDate('updated_at', '=', $d)->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = true;
                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }

            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

            return $arrTaskData;
        } elseif ($usr->type == 'client') {
            foreach ($stages as $key => $stage) {
                $data = [];
                foreach ($arrDate as $d) {
                    $data[] = Task::join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client', '=', $usr->id)->where('stage', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

            return $arrTaskData;
        } else {
            foreach ($stages as $key => $stage) {
                $data = [];
                foreach ($arrDate as $d) {
                    $data[] = Task::where('assign_to', '=', $usr->id)->where('stage', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

            return $arrTaskData;
        }
    }

    /**
     * @param
     * @return array
     */

    public static function getTeamLoggedHoursChartData()
    {
        $usr     = \Auth::user();
        $m       = date("m");
        $de      = date("d");
        $y       = date("Y");
        $format  = 'Y-m-d';
        $arrDate = [];
        $arrDay  = [];

        for ($i = 7 - 1; $i >= 0; $i--) {
            $date              = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDay['label'][] = __(date('d-M', mktime(0, 0, 0, $m, ($de - $i), $y)));
            $arrDate[]         = $date;
        }

        //$stages  = Projectstages::where('created_by', '=', $usr->creatorId())->get();
        $arrTask = [];

        $i = 0;
        if ($usr->type == 'company') {
        } elseif ($usr->type == 'client') {
        } else {
            //Initialize the array
            $data = $logged_data_in_hours = $logged_data_by_user = [];

            $teamMembers = User::where('user_parent_id', $usr->id)->pluck('name', 'id');
            foreach ($arrDate as $d) {
                foreach ($teamMembers as $id => $name) {
                    $data[$d][$name] = Timesheet::where('user_id', '=', $id)->whereDate('date', '=', $d)->sum('total_mins');
                }
            }

            $total_logged_hours = 0;
            foreach ($data as $index => $userLoggedHours) {
                $total_logged_hours += round(array_sum($userLoggedHours) / 60, 1);
            }

            foreach ($data as $key => $loggedMins) {
                foreach ($loggedMins as $name => $loggedTime) {
                    $logged_time_hour = round(($loggedTime) / 60, 1);
                    $logged_data_by_user[$name] = $logged_time_hour;
                }
                $logged_data_in_hours[] = $logged_data_by_user;
            }

            $loggeedHrs = [];
            $borderColour = '';

            $index = 0;
            foreach ($teamMembers as $key => $value) {
                $teamMembersLoggedHours = [];
                //extract logged hours for each team member
                $teamMembersLoggedHours = (array_column($logged_data_in_hours, $value));

                $color = sprintf("#%06x", mt_rand(0, 0xFFFFFF));
                $rand_color = '#' . substr(md5(mt_rand()), 0, 6);

                $records = $teamMembersLoggedHours;
                $borderColour = $color;
                $background_colour = $color . '70';
                // $loggeedHrs[] = round($value->total_mins / 60, 1, PHP_ROUND_HALF_UP);
                $dataset['label']           = $value;
                $dataset['lineTension']     = 0.8;
                $dataset['borderSkipped']   = 'bottom';
                $dataset['barThickness']    = '50';
                $dataset['grouped']         = true;
                $dataset['pointBorderWidth'] = 2;
                $dataset['pointStyle']      = "rect";
                $dataset['fill']            = true;
                $dataset['borderWidth']     = "1";
                $dataset['borderRadius']     = "0";
                $dataset['backgroundColor'] = $background_colour;
                $dataset['borderColor']     = $borderColour;
                $dataset['data']            = $records;
                $arrTask[]                  = $dataset;

                $index++;
            }

            //dd($arrTask);
            $i++;


            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            $arrTaskData = array_merge($arrTaskData, ["total_logged_hrs" => $total_logged_hours]);
            // unset($arrTaskData['dataset'][$i - 1]['fill']);
            // $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#00800050';

            return $arrTaskData;
        }
    }

    /**
     * @param
     * @return array
     */

    public static function getLoggedHoursChartData()
    {
        $usr     = \Auth::user();
        $m       = date("m");
        $de      = date("d");
        $y       = date("Y");
        $format  = 'Y-m-d';
        $arrDate = [];
        $arrDay  = [];

        for ($i = 7 - 1; $i >= 0; $i--) {
            $date              = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDay['label'][] = __(date('d-M', mktime(0, 0, 0, $m, ($de - $i), $y)));
            $arrDate[]         = $date;
        }

        //$stages  = Projectstages::where('created_by', '=', $usr->creatorId())->get();
        $arrTask = [];

        $i = 0;
        if ($usr->type == 'company') {
        } elseif ($usr->type == 'client') {
        } else {

            // $data = [];
            // $lastSevenDays = date('Y-m-d', strtotime('-7 days'));

            // $data = DB::table('timesheets')->where('user_id', '=', $usr->id)->where('date', '>', $lastSevenDays)->get();

            // $data = (!empty($data)) ? $data->toArray() : $data;

            $data = $data1 = $billable_data_in_hours = $nonbillable_data_in_hours = [];

            foreach ($arrDate as $d) {
                $data[] = Timesheet::where('user_id', '=', $usr->id)->where('billable', '=', 'Yes')->whereDate('date', '=', $d)->sum('total_mins');
                $data1[] = Timesheet::where('user_id', '=', $usr->id)->where('billable', '=', 'No')->whereDate('date', '=', $d)->sum('total_mins');
            }
            $total_logged_hours = round((array_sum(array_merge($data, $data1))) / 60, 2);

            foreach ($data as $key => $loggedMins) {
                $logged_time = round($loggedMins / 60, 1);
                $billable_data_in_hours[] = $logged_time;
            }
            foreach ($data1 as $key => $loggedMins) {
                $logged_time = round($loggedMins / 60, 1);
                $nonbillable_data_in_hours[] = $logged_time;
            }
            $loggeedHrs = [];
            $borderColour = '';

            $project_type = array('Billable', 'Non-billable');

            foreach ($project_type as $key => $value) {
                $records = ($value == 'Billable') ? $billable_data_in_hours : $nonbillable_data_in_hours;
                $borderColour = ($value == 'Billable') ? '#008000' : '#FF0000';
                $background_colour = ($value == 'Billable') ? '#00800050' : '#FF000050';
                // $loggeedHrs[] = round($value->total_mins / 60, 1, PHP_ROUND_HALF_UP);
                $dataset['label']           = $value;
                $dataset['lineTension']     = 0.2;
                $dataset['pointBorderWidth'] = 2;
                $dataset['pointStyle']      = "rect";
                $dataset['fill']            = true;
                $dataset['borderWidth']     = "1";
                $dataset['borderRadius']     = "1";
                $dataset['backgroundColor'] = $background_colour;
                $dataset['borderColor']     = $borderColour;
                $dataset['data']            = $records;
                $arrTask[]                  = $dataset;
            }

            //dd($arrTask);
            $i++;


            // foreach ($data as $key => $value) {
            //     $borderColour = ($value->billable == 'Yes') ? 'green' : 'red';
            //     $loggeedHrs[] = round($value->total_mins / 60, 1, PHP_ROUND_HALF_UP);
            //     $dataset['label']           = $value->billable;
            //     $dataset['fill']            = '!0';
            //     $dataset['backgroundColor'] = 'transparent';
            //     $dataset['borderColor']     = $borderColour;
            //     $dataset['data']            = $loggeedHrs;
            //     $arrTask[]                  = $dataset;
            //     $i++;
            // }

            //dd($loggeedHrs);

            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            $arrTaskData = array_merge($arrTaskData, ["total_logged_hrs" => $total_logged_hours]);
            // unset($arrTaskData['dataset'][$i - 1]['fill']);
            // $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#00800050';

            return $arrTaskData;
        }
    }
}
