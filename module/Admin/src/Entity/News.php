<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="news")
*/
class News
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="title", type="string", length=120, nullable=false) */
    protected $title;

    /** @ORM\Column(name="body", type="text", nullable=false) */
    protected $body;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;

    /** @ORM\Column(name="picture_url", type="string", length=120, nullable=false) */
    protected $picture_url;

    /** @ORM\Column(name="piece_of_news_url", type="string", length=120, nullable=false) */
    protected $piece_of_news_url;

    /** @ORM\Column(name="document_id", type="string", unique=true,nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'date' => $this->date->format('Y-m-d'),
            'picture_url' => $this->picture_url,
            'piece_of_news_url' => $this->piece_of_news_url,
            'document_id' => $this->document_id,
        ];
    }

    public function initialize(array $array){
        $this->title = $array['title'];
        $this->body = $array['body'];
        $this->date = new \DateTime($array['date']);
        $this->picture_url = $this->validatePictureUrl($array['picture_url']);
        $this->piece_of_news_url = $this->validatePieceOfNewsUrl($array['piece_of_news_url']);
    }

    public function exchangeArray(array $array){
        $this->title = $array['title'];
        $this->body = $array['body'];
        $this->date = new \DateTime($array['date']);
        $this->picture_url = $this->validatePictureUrl($array['picture_url']);
        $this->piece_of_news_url = $this->validatePieceOfNewsUrl($array['piece_of_news_url']);
        $this->document_id = $array['document_id'];
    }

    public function toFirebase(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'date' => $this->date->format('d/m/Y'),
            'picture_url' => $this->picture_url,
            'piece_of_news_url' => $this->piece_of_news_url
        ];
    }

    public function getId(){ return $this->id; }
    public function getTitle(){ return $this->title; }
    public function getBody(){ return $this->body; }
    public function getDate(){ return $this->date; }
    public function getPictureUrl(){ return $this->picture_url; }
    public function getPieceOfNewsUrl(){ return $this->piece_of_news_url; }
    public function getDocumentId(){ return $this->document_id; }

    public function setTitle($v){ $this->title = $v; }
    public function setBody($v){ $this->body = $v; }
    public function setDate($v){ $this->date = $v; }
    public function setPictureUrl($v){ $this->picture_url = $v; }
    public function setPieceOfNewsUrl($v){ $this->piece_of_news_url = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }

    private function validatePieceOfNewsUrl($piece_of_news_url){
        // Verifico que el URL sea válido
        $url = 'https://obrasocialquimicos.com.ar/';

        if(filter_var($piece_of_news_url, FILTER_VALIDATE_URL) === FALSE) {
            return $url;
        }else{
            return $piece_of_news_url;
        }
    }

    private function validatePictureUrl($picture_url){
        // Verifico que la imagen sea válida

        $template = '';
        $headers = get_headers($picture_url, 1);

        if (strpos($headers['Content-Type'], 'image/') !== false) {
            return $picture_url;
        } else {
            return $template;
        }

    }
}