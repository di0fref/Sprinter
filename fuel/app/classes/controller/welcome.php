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
        'Waiting for acceptance',
        'Waiting final acceptance',
        'Closed',
        'Fixed - waiting to be deployed',
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
					if (!in_array($issue['status']['name'], $this->ignore_statues)){
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
	                        $issue['description'] = str_replace("\n", '<br />', $issue['description']);
	                    }

						$issue['due'] = "no_due";
					
						if (isset($issue['due_date'])){

							if($issue['due_date'] == date("Y/m/d")){
								$issue["due"] = "due_today";
							}
							if($issue['due_date'] == date("Y/m/d", strtotime(date("Y/m/d") . " +1 day"))){
								$issue["due"] = "due_tomorrow";
							}
							if($issue['due_date'] < date("Y/m/d", strtotime(date("Y/m/d")))){
								$issue["due"] = "overdue";
							}

						}

	                    if (!array_key_exists('estimated_hours', $issue))
	                        $issue['estimated_hours'] = 0;

	                    $remaining_time = (1 - ($issue['done_ratio'] / 100)) * $issue['estimated_hours'];

	                    $issue['remaining_time'] = $remaining_time;

	                    $issues[] = $issue;
          				$remaining += $remaining_time;
	                    $estimated_hours = $estimated_hours + $issue['estimated_hours'];
	                }
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
