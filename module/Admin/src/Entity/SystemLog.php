<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="system_logs")
*/
class SystemLog
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="date", type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP", nullable=false) */
    protected $date;

    /** @ORM\Column(name="type", type="integer", nullable=false) */
    protected $type;

    /** @ORM\Column(name="event", type="text", nullable=false) */
    protected $event;

    /** @ORM\Column(name="url", type="string", length=2000, nullable=false) */
    protected $url;

    /** @ORM\Column(name="file", type="string", length=2000, nullable=false) */
    protected $file;

    /** @ORM\Column(name="line", type="integer", nullable=false) */
    protected $line;

    /** @ORM\Column(name="error_type", type="string", nullable=false) */
    protected $error_type;

    /** @ORM\Column(name="trace", type="text", nullable=true) */
    protected $trace;

    /** @ORM\Column(name="request_data", type="text", nullable=true) */
    protected $request_data;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'date' => $this->date,
            'type' => $this->type,
            'event' => $this->event,
            'url' => $this->url,
            'file' => $this->file,
            'line' => $this->line,
            'error_type' => $this->error_type,
            'trace' => $this->trace,
            'request_data' => $this->request_data,
        ];
    }

    public function initialize($array){
        $this->date = $array['date'];
        $this->type = $array['type'];
        $this->event = $array['event'];
        $this->url = $array['url'];
        $this->file = $array['file'];
        $this->line = $array['line'];
        $this->error_type = $array['error_type'];
        $this->trace = $array['trace'];
        $this->request_data = $array['request_data'];
    }

    public function exchangeArray($array){
        $this->date = $array['date'];
        $this->type = $array['type'];
        $this->event = $array['event'];
        $this->url = $array['url'];
        $this->file = $array['file'];
        $this->line = $array['line'];
        $this->error_type = $array['error_type'];
        $this->trace = $array['trace'];
        $this->request_data = $array['request_data'];
    }

    public function getId(){ return $this->id; }
    public function getDate(){ return $this->date; }
    public function getType(){ return $this->type; }
    public function getEvent(){ return $this->event; }
    public function getUrl(){ return $this->url; }
    public function getFile(){ return $this->file; }
    public function getLine(){ return $this->line; }
    public function getErrorType(){ return $this->error_type; }
    public function getTrace(){ return $this->trace; }
    public function getRequestData(){ return $this->request_data; }

    public function setid($v){ $this->id = $v; }
    public function setDate($v){ $this->date = $v; }
    public function setType($v){ $this->type = $v; }
    public function setEvent($v){ $this->event = $v; }
    public function setUrl($v){ $this->url = $v; }
    public function setFile($v){ $this->file = $v; }
    public function setLine($v){ $this->line = $v; }
    public function setErrorType($v){ $this->error_type = $v; }
    public function setTrace($v){ $this->trace = $v; }
    public function setRequestData($v){ $this->request_data = $v; }
}
