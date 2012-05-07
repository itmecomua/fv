<?php
/**
* @Entity
* @Table(name="post")
*/
class Post
{
    /** @Id @GeneratedValue @Column(type="integer") **/
    protected $id;
    /** @Column(type="string") **/
    protected $title;
    /** @Column(type="text") **/
    protected $body;
    
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle( $value )
    {
        $this->title =  $value;
    }    
}