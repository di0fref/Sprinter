<?php

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */

require_once '../fuel/app/classes/model/issues.php';
date_default_timezone_set('Europe/Stockholm');
class Controller_Welcome extends Controller
{

    protected $ignore_statues = array(
        //'Waiting for acceptance',
        //'Waiting final acceptance',
        //'Closed',
        //'Fixed - waiting to be deployed',
    );
     protected $ignore_statues_class = array(
        'Waiting for acceptance' => 'status_waiting_for_acceptance',
        'Waiting final acceptance' => 'status_final_acceptance',
        'Closed' => 'status_closed',
        'Fixed - waiting to be deployed' => 'status_fixed_waiting_to_be_deployd',
        'Feedback' => 'status_feedback',
        'New' => 'status_new',
        'Waiting Customer Response' => 'status_waiting_customer_response',
        'In progress' => 'status_in_progress',
    );
    /**
     * The index action.
     *
     * @access  public
     * @return  void
     */

    public function action_index()
    {

        $data = array();
        $version = $this->getVersion();
        $issueModel = new Issues($version);

        $data = $issueModel->get_all_issues();

        $done_ratio = 0;
        $estimated_hours = 0;
        $issues = array();
        $count = 0;
        $remaining = 0;
        $target_version = "";

        foreach ($data as $key => $item)
        {
            if ($key == 'issues') {
                $count = count($item);
                foreach ($item as $issue)
                {
                
                		$issue["class"] = Issues::clean_status($issue["status"]["name"]);
                		
						if($issue['done_ratio'] > 0)
							$issue["updated_on"] = date("Y-m-d H:i", strtotime($issue["updated_on"] ));
						else
							$issue["updated_on"] = "";

	                    if (empty($issue['assigned_to']['name'])) {
	                        $issue['assigned_to'] = '-';
	                    } else {
	                        $issue['assigned_to'] = $issue['assigned_to']['name'];
	                    }

	                    if (empty($issue['description'])) {
	                        $issue['description'] = '';
	                    } else {
	                    	$issue['description'] = $this->filterHTML($issue['description']);
	                    }

						$issue['due'] = "no_due";
					
						if (isset($issue['due_date']) and $issue["status"]["name"] != "Closed"){
							if($issue['due_date'] == date("Y/m/d")){
								$issue["due"] = "due_today";
							}
							if($issue['due_date'] == date("Y/m/d", strtotime(date("Y/m/d") . " +1 day"))){
								$issue["due"] = "due_tomorrow";
							}
							if($issue['due_date'] < date("Y/m/d", strtotime(date("Y/m/d")))){
								$issue["due"] = "overdue";
							}
							$issue['due_date'] = date("Y-m-d", strtotime($issue['due_date']));
						}
						
						

	                    if (!array_key_exists('estimated_hours', $issue))
	                        $issue['estimated_hours'] = 0;

	                    $remaining_time = (1 - ($issue['done_ratio'] / 100)) * $issue['estimated_hours'];

	                    $issue['remaining_time'] = $remaining_time;
						if (!in_array($issue['status']['name'], $this->ignore_statues))
	                    	$issues[] = $issue;
          				$remaining += $remaining_time;
	                    $estimated_hours = $estimated_hours + $issue['estimated_hours'];
	                }
            }
        }

        $data['issues'] = $issues;
        $data['estimated_hours'] = round($estimated_hours, 1);
        $data['done_ratio'] = round((1 - $remaining / $estimated_hours) * 100, 1);
        $data['completed'] = round($estimated_hours - $remaining, 1);
        $data['remaining'] = round($remaining, 1);
        $data["current_target_version"] = $issueModel->get_current_target_version();
        $data["versions"] = $issueModel->get_target_versions();
        $data["version"] = $issueModel->get_target_version();

        $this->response->body = View::factory('welcome/index', $data);
    }


	function callback($matches){
		return "<a target=_blank href='https://redmine.redpill-linpro.com/issues/{$matches[2]}'>{$matches[0]}</a>";
	}
	function filterHTML($text){
	
		$pattern = "/(\#)([0-9]+)/";
		$text = preg_replace_callback($pattern, array(&$this, "callback"), $text);
		$text = str_replace("\n", '<br>', $text);
		return $text;
	}

    function setCookie($value)
    {
        setcookie("target_version", $value, time() + 86400); // One day
    }

    function getVersion()
    {
        if (isset($_REQUEST["version"])) {
            setcookie("target_version", $_REQUEST["version"], time() + 86400);
            return $_REQUEST["version"];
        }
        if (isset($_COOKIE['target_version'])) {
            return $_COOKIE['target_version'];
        }
        return "";
    }

    /**
     * The 404 action for the application.
     *
     * @access  public
     * @return  void
     */
    public function action_404()
    {
        $messages = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
        $data['title'] = $messages[array_rand($messages)];

        // Set a HTTP 404 output header
        $this->response->status = 404;
        $this->response->body = View::factory('welcome/404', $data);
    }
}

/* End of file welcome.php */
