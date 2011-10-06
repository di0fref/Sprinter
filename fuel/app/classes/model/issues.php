<?php 

class Issues
{
    protected $data;
    protected $target_versions;
    protected $target_name;
    protected $versions;
    protected $version;
    protected $pattern = "Y v.W";
    protected $statuses = array();

    function __construct($version)
    {
        $this->version = $version;
        $this->load_target_versions();
        if (!$this->version) $this->load_latest_version();
        $this->load_issues();
        $this->load_current_target_version();
        $this->build_statuses();

    }

	static public function clean_status($string){
		return "status_".str_replace(" ", "_", strtolower($string));
	}

	function build_statuses(){
		foreach ($this->data["issues"] as $key => $issue) {
			$string = self::clean_status($issue["status"]["name"]);
			$this->statuses[$string] = $issue["status"]["name"];
		}
		

	}

	function get_statuses(){
		return $this->statuses;
	}

    function load_latest_version()
    {
        $date = date($this->pattern);
        foreach ($this->versions as $key => $v) {
            if ($v == $date) {
                $this->version = $key;
                return;
            }
        }
    }

    function get_current_target_version()
    {
        return $this->target_name;
    }

    function load_current_target_version()
    {
        foreach ($this->data["issues"] as $key => $issue) {
            if ($this->target_name == "") {
                $this->target_name = $issue["fixed_version"]["name"];
            }
        }
    }

    function get_target_versions()
    {
        return $this->versions;
    }

    function get_target_version()
    {
        return $this->version;
    }

    function load_target_versions()
    {
        $json = file_get_contents('https://redmine.redpill-linpro.com/projects/sprintproject/issues.json?key=ce334bd62e7a672d832f4252ac311b80fdacfc24&limit=100');
        $data = json_decode($json, true);

        foreach ($data["issues"] as $key => $issue) {
            if (isset($issue["fixed_version"]["id"]))
                $tv[$issue["fixed_version"]["id"]] = $issue["fixed_version"]["name"];
        }
        arsort($tv);
        $this->versions = $tv;
    }

    function load_issues()
    {
        $json = file_get_contents('https://redmine.redpill-linpro.com/projects/sprintproject/issues.json?set_filter=1&f[]=status_id&op[status_id]=*&v[status_id][]=14&f[]=fixed_version_id&op[fixed_version_id]==&v[fixed_version_id][]=' . $this->version . '&f[]=&c[]=tracker&c[]=status&c[]=priority&c[]=subject&c[]=assigned_to&c[]=fixed_version&c[]=due_date&c[]=estimated_hours&c[]=done_ratio&c[]=cf_13&key=ce334bd62e7a672d832f4252ac311b80fdacfc24&limit=10000');
        $this->data = json_decode($json, true);
    }


    function get_all_issues($version = null)
    {
        return $this->data;
    }
}
