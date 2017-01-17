<?php

namespace UKVS\Client;
class UKVSClient {
    public $host;
    public $namespace;
    public $version;

    public function __construct($namespace, $host = "https://kvs.bins.io"){
        $this->host = $host;
        $this->namespace = $namespace;
        $this->version = 'v1';
    }

    public function get($key, $related = false) {
        $components = array($this->host, $this->version, $this->namespace, $key);
        if ($related) {
            $components[] = "related";
        }
        return $this->call($components);
    }

    public function getRelated($key){
        return $this->get($key, true);
    }

    private function call($components){
        $url = implode("/", $components);
        $result = file_get_contents($url);
        $result = json_decode($result);
        if (isset($result->{'_id'})) {
            if (isset($result->{'related'})) {
                $obj = new UKVSRelated($result->{'_id'});
                foreach ($result->{'related'} as $related) {
                    $tmpObj = new UKVSObject(null);
                    $tmpObj->obj = $related->{'obj'};
                    $tmpObj->time = new \DateTime();
                    $tmpObj->time->SetTimestamp($related->{'time'});
                    $obj->related[] = $tmpObj;
                }
                return $obj;
            } else {
                $obj = new UKVSObject($result->{'_id'});
                $obj->obj = $result->{'obj'};
                $obj->time = new \DateTime();
                $obj->time->SetTimestamp($result->{'time'});
                return $obj;
            }
        } else {
            return false;
        }
    }
}
class UKVSObject {
	public $id;
	public $obj;
	public $time;

	public function __construct($id) {
		$this->id = $id;
	}
}

class UKVSRelated{
	public $id;
	public $related;

	public function __construct($id){
		$this->id = $id;
		$this->related = array();
	}

}