<?php if (!defined("RARS_BASE_PATH")) die("No direct script access to this content");

class ExtensionList extends RazorAPI
{
    private $types = array("theme", "system", "all");

    function __construct()
    {
        // REQUIRED IN EXTENDED CLASS TO LOAD DEFAULTS
        parent::__construct();
    }

    public function get($type)
    {
        if (!$this->check_access()) $this->response(null, null, 401);
        if (empty($type) || !in_array($type, $this->types)) $this->response(null, null, 400);

        // first scan the folders for manifests
        $manifests = RazorFileTools::find_file_contents(RAZOR_BASE_PATH."extension", "manifest.json", "json", "end");

        // split into types, so we can filter a little
        $extensions = array();

        $db = new RazorDB();
        $db->connect("extension");

        foreach ($manifests as $mf)
        {
            $mf->created = date("D jS M Y", $mf->created);
            
            // grab settings if any
            if (isset($mf->settings))
            {
                $options = array("amount" => 1);
                $search = array(array("column" => "extension", "value" => $mf->extension),array("column" => "type", "value" => $mf->type),array("column" => "handle", "value" => $mf->handle));
                $extension = $db->get_rows($search, $options);
                if ($extension["count"] == 1)
                {
                    $db_settings = json_decode($extension["result"][0]["json_settings"]);

                    foreach ($mf->settings as $key => $setting) 
                    {
                        if (isset($db_settings->{$setting->name})) $mf->settings[$key]->value = $db_settings->{$setting->name};
                    }
                }
            } 

            // sort list
            if ($mf->type == $type) $extensions[] = $mf;
            else if ($type == "system" && $mf->type != "theme") $extensions[] = $mf;
            else if ($type == "all")
            {
                $mf->type = ucfirst($mf->type);
                $extensions[] = $mf;
            }
        }

        $db->disconnect(); 

        $this->response(array("extensions" => $extensions), "json");
    }
}

/* EOF */