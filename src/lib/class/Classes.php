<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1165
Released: Wed Jun 24 17:09:29 2015 GMT
The MIT License (MIT)

Copyright (c) 2015 Web Modules Ltd. UK

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/
	
class Address
{
private $addsid;
private $add1;
private $add2;
private $add3;
private $city;
private $county;
private $country;
private $postcode;
private $tel;
private $mob;
private $fax;
private $email;
private $web;
private $facebook;
private $twitter;
private $linkedin;
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setAdd1($add1)
	{
		$this->add1 = $add1;
	}

	public function getAdd1()
	{
		return $this->add1;
	}
		
	public function setAdd2($add2)
	{
		$this->add2 = $add2;
	}

	public function getAdd2()
	{
		return $this->add2;
	}
		
	public function setAdd3($add3)
	{
		$this->add3 = $add3;
	}

	public function getAdd3()
	{
		return $this->add3;
	}
		
	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getCity()
	{
		return $this->city;
	}
		
	public function setCounty($county)
	{
		$this->county = $county;
	}

	public function getCounty()
	{
		return $this->county;
	}
		
	public function setCountry($country)
	{
		$this->country = $country;
	}

	public function getCountry()
	{
		return $this->country;
	}
		
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
	}

	public function getPostcode()
	{
		return $this->postcode;
	}
		
	public function setTel($tel)
	{
		$this->tel = $tel;
	}

	public function getTel()
	{
		return $this->tel;
	}
		
	public function setMob($mob)
	{
		$this->mob = $mob;
	}

	public function getMob()
	{
		return $this->mob;
	}
		
	public function setFax($fax)
	{
		$this->fax = $fax;
	}

	public function getFax()
	{
		return $this->fax;
	}
		
	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}
		
	public function setWeb($web)
	{
		$this->web = $web;
	}

	public function getWeb()
	{
		return $this->web;
	}
		
	public function setFacebook($facebook)
	{
		$this->facebook = $facebook;
	}

	public function getFacebook()
	{
		return $this->facebook;
	}
		
	public function setTwitter($twitter)
	{
		$this->twitter = $twitter;
	}

	public function getTwitter()
	{
		return $this->twitter;
	}
		
	public function setLinkedin($linkedin)
	{
		$this->linkedin = $linkedin;
	}

	public function getLinkedin()
	{
		return $this->linkedin;
	}

	public function getAll()
	{
		$ret = array(
		'add1'=>$this->getAdd1(),
		'add2'=>$this->getAdd2(),
		'add3'=>$this->getAdd3(),
		'city'=>$this->getCity(),
		'county'=>$this->getCounty(),
		'country'=>$this->getCountry(),
		'postcode'=>$this->getPostcode(),
		'tel'=>$this->getTel(),
		'mob'=>$this->getMob(),
		'fax'=>$this->getFax(),
		'email'=>$this->getEmail(),
		'web'=>$this->getWeb(),
		'facebook'=>$this->getFacebook(),
		'twitter'=>$this->getTwitter(),
		'linkedin'=>$this->getLinkedin());
		return $ret;
	}

	public function loadAddress() {
		global $db;
		if(!isset($this->addsid)){
			return "No Address ID";
		}		
    	$res = $db->select('SELECT addsid,add1,add2,add3,city,county,country,postcode,tel,mob,fax,email,web,facebook,twitter,linkedin FROM address WHERE addsid=?', array($this->addsid), 'd');		
		$r=$res[0];    
		$this->setAddsid($r->addsid);
		$this->setAdd1($r->add1);
		$this->setAdd2($r->add2);
		$this->setAdd3($r->add3);
		$this->setCity($r->city);
		$this->setCounty($r->county);
		$this->setCountry($r->country);
		$this->setPostcode($r->postcode);
		$this->setTel($r->tel);
		$this->setMob($r->mob);
		$this->setFax($r->fax);
		$this->setEmail($r->email);
		$this->setWeb($r->web);
		$this->setFacebook($r->facebook);
		$this->setTwitter($r->twitter);
		$this->setLinkedin($r->linkedin);

	}


	public function updateDB() {
		global $db;
		global $addressFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'addsid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $addressFormats[$key];
	        }
	    }
	     
	    $res = $db->update('address', $data, $format, array('addsid'=>$this->addsid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $addressFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $addressFormats[$key];
	        }
	    }
		 $res = $db->insert('address', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->addsid)){
			return "No Address ID";
		}
        $res = $db->delete('address', $this->addsid, 'addsid');
         
        return $res;
        
    }

}
	
class Contacts
{
private $contactsid;
private $title;
private $fname;
private $sname;
private $co_name;
private $role;
private $custid;
private $suppid;
private $addsid;
private $notes;
private $lastlogin;
		
	public function setContactsid($contactsid)
	{
		$this->contactsid = $contactsid;
	}

	public function getContactsid()
	{
		return $this->contactsid;
	}
		
	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}
		
	public function setFname($fname)
	{
		$this->fname = $fname;
	}

	public function getFname()
	{
		return $this->fname;
	}
		
	public function setSname($sname)
	{
		$this->sname = $sname;
	}

	public function getSname()
	{
		return $this->sname;
	}
		
	public function setCo_name($co_name)
	{
		$this->co_name = $co_name;
	}

	public function getCo_name()
	{
		return $this->co_name;
	}
		
	public function setRole($role)
	{
		$this->role = $role;
	}

	public function getRole()
	{
		return $this->role;
	}
		
	public function setCustid($custid)
	{
		$this->custid = $custid;
	}

	public function getCustid()
	{
		return $this->custid;
	}
		
	public function setSuppid($suppid)
	{
		$this->suppid = $suppid;
	}

	public function getSuppid()
	{
		return $this->suppid;
	}
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getNotes()
	{
		return $this->notes;
	}
		
	public function setLastlogin($lastlogin)
	{
		$this->lastlogin = $lastlogin;
	}

	public function getLastlogin()
	{
		return $this->lastlogin;
	}

	public function getAll()
	{
		$ret = array(
		'title'=>$this->getTitle(),
		'fname'=>$this->getFname(),
		'sname'=>$this->getSname(),
		'co_name'=>$this->getCo_name(),
		'role'=>$this->getRole(),
		'custid'=>$this->getCustid(),
		'suppid'=>$this->getSuppid(),
		'addsid'=>$this->getAddsid(),
		'notes'=>$this->getNotes(),
		'lastlogin'=>$this->getLastlogin());
		return $ret;
	}

	public function loadContacts() {
		global $db;
		if(!isset($this->contactsid)){
			return "No Contacts ID";
		}		
    	$res = $db->select('SELECT contactsid,title,fname,sname,co_name,role,custid,suppid,addsid,notes,lastlogin FROM contacts WHERE contactsid=?', array($this->contactsid), 'd');		
		$r=$res[0];    
		$this->setContactsid($r->contactsid);
		$this->setTitle($r->title);
		$this->setFname($r->fname);
		$this->setSname($r->sname);
		$this->setCo_name($r->co_name);
		$this->setRole($r->role);
		$this->setCustid($r->custid);
		$this->setSuppid($r->suppid);
		$this->setAddsid($r->addsid);
		$this->setNotes($r->notes);
		$this->setLastlogin($r->lastlogin);

	}


	public function updateDB() {
		global $db;
		global $contactsFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'contactsid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $contactsFormats[$key];
	        }
	    }
	     
	    $res = $db->update('contacts', $data, $format, array('contactsid'=>$this->contactsid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $contactsFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $contactsFormats[$key];
	        }
	    }
		 $res = $db->insert('contacts', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->contactsid)){
			return "No Contacts ID";
		}
        $res = $db->delete('contacts', $this->contactsid, 'contactsid');
         
        return $res;
        
    }

}
	
class Customer
{
private $custid;
private $co_name;
private $contact;
private $addsid;
private $colour;
		
	public function setCustid($custid)
	{
		$this->custid = $custid;
	}

	public function getCustid()
	{
		return $this->custid;
	}
		
	public function setCo_name($co_name)
	{
		$this->co_name = $co_name;
	}

	public function getCo_name()
	{
		return $this->co_name;
	}
		
	public function setContact($contact)
	{
		$this->contact = $contact;
	}

	public function getContact()
	{
		return $this->contact;
	}
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setColour($colour)
	{
		$this->colour = $colour;
	}

	public function getColour()
	{
		return $this->colour;
	}

	public function getAll()
	{
		$ret = array(
		'co_name'=>$this->getCo_name(),
		'contact'=>$this->getContact(),
		'addsid'=>$this->getAddsid(),
		'colour'=>$this->getColour());
		return $ret;
	}

	public function loadCustomer() {
		global $db;
		if(!isset($this->custid)){
			return "No Customer ID";
		}		
    	$res = $db->select('SELECT custid,co_name,contact,addsid,colour FROM customer WHERE custid=?', array($this->custid), 'd');		
		$r=$res[0];    
		$this->setCustid($r->custid);
		$this->setCo_name($r->co_name);
		$this->setContact($r->contact);
		$this->setAddsid($r->addsid);
		$this->setColour($r->colour);

	}


	public function updateDB() {
		global $db;
		global $customerFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'custid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $customerFormats[$key];
	        }
	    }
	     
	    $res = $db->update('customer', $data, $format, array('custid'=>$this->custid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $customerFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $customerFormats[$key];
	        }
	    }
		 $res = $db->insert('customer', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->custid)){
			return "No Customer ID";
		}
        $res = $db->delete('customer', $this->custid, 'custid');
         
        return $res;
        
    }

}
	
class Events
{
private $eventsid;
private $name;
		
	public function setEventsid($eventsid)
	{
		$this->eventsid = $eventsid;
	}

	public function getEventsid()
	{
		return $this->eventsid;
	}
		
	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getAll()
	{
		$ret = array(
		'name'=>$this->getName());
		return $ret;
	}

	public function loadEvents() {
		global $db;
		if(!isset($this->eventsid)){
			return "No Events ID";
		}		
    	$res = $db->select('SELECT eventsid,name FROM events WHERE eventsid=?', array($this->eventsid), 'd');		
		$r=$res[0];    
		$this->setEventsid($r->eventsid);
		$this->setName($r->name);

	}


	public function updateDB() {
		global $db;
		global $eventsFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'eventsid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $eventsFormats[$key];
	        }
	    }
	     
	    $res = $db->update('events', $data, $format, array('eventsid'=>$this->eventsid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $eventsFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $eventsFormats[$key];
	        }
	    }
		 $res = $db->insert('events', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->eventsid)){
			return "No Events ID";
		}
        $res = $db->delete('events', $this->eventsid, 'eventsid');
         
        return $res;
        
    }

}
	
class Invoices
{
private $invoicesid;
private $custid;
private $contactsid;
private $incept;
private $paid;
private $content;
private $price;
private $notes;
		
	public function setInvoicesid($invoicesid)
	{
		$this->invoicesid = $invoicesid;
	}

	public function getInvoicesid()
	{
		return $this->invoicesid;
	}
		
	public function setCustid($custid)
	{
		$this->custid = $custid;
	}

	public function getCustid()
	{
		return $this->custid;
	}
		
	public function setContactsid($contactsid)
	{
		$this->contactsid = $contactsid;
	}

	public function getContactsid()
	{
		return $this->contactsid;
	}
		
	public function setIncept($incept)
	{
		$this->incept = $incept;
	}

	public function getIncept()
	{
		return $this->incept;
	}
		
	public function setPaid($paid)
	{
		$this->paid = $paid;
	}

	public function getPaid()
	{
		return $this->paid;
	}
		
	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}
		
	public function setPrice($price)
	{
		$this->price = $price;
	}

	public function getPrice()
	{
		return $this->price;
	}
		
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getNotes()
	{
		return $this->notes;
	}

	public function getAll()
	{
		$ret = array(
		'custid'=>$this->getCustid(),
		'contactsid'=>$this->getContactsid(),
		'incept'=>$this->getIncept(),
		'paid'=>$this->getPaid(),
		'content'=>$this->getContent(),
		'price'=>$this->getPrice(),
		'notes'=>$this->getNotes());
		return $ret;
	}

	public function loadInvoices() {
		global $db;
		if(!isset($this->invoicesid)){
			return "No Invoices ID";
		}		
    	$res = $db->select('SELECT invoicesid,custid,contactsid,incept,paid,content,price,notes FROM invoices WHERE invoicesid=?', array($this->invoicesid), 'd');		
		$r=$res[0];    
		$this->setInvoicesid($r->invoicesid);
		$this->setCustid($r->custid);
		$this->setContactsid($r->contactsid);
		$this->setIncept($r->incept);
		$this->setPaid($r->paid);
		$this->setContent($r->content);
		$this->setPrice($r->price);
		$this->setNotes($r->notes);

	}


	public function updateDB() {
		global $db;
		global $invoicesFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'invoicesid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $invoicesFormats[$key];
	        }
	    }
	     
	    $res = $db->update('invoices', $data, $format, array('invoicesid'=>$this->invoicesid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $invoicesFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $invoicesFormats[$key];
	        }
	    }
		 $res = $db->insert('invoices', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->invoicesid)){
			return "No Invoices ID";
		}
        $res = $db->delete('invoices', $this->invoicesid, 'invoicesid');
         
        return $res;
        
    }

}
	
class Mail
{
private $mailid;
private $addto;
private $addname;
private $subject;
private $body;
private $sent;
private $incept;
private $timesent;
private $docname;
private $doctitle;
		
	public function setMailid($mailid)
	{
		$this->mailid = $mailid;
	}

	public function getMailid()
	{
		return $this->mailid;
	}
		
	public function setAddto($addto)
	{
		$this->addto = $addto;
	}

	public function getAddto()
	{
		return $this->addto;
	}
		
	public function setAddname($addname)
	{
		$this->addname = $addname;
	}

	public function getAddname()
	{
		return $this->addname;
	}
		
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}
		
	public function setBody($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}
		
	public function setSent($sent)
	{
		$this->sent = $sent;
	}

	public function getSent()
	{
		return $this->sent;
	}
		
	public function setIncept($incept)
	{
		$this->incept = $incept;
	}

	public function getIncept()
	{
		return $this->incept;
	}
		
	public function setTimesent($timesent)
	{
		$this->timesent = $timesent;
	}

	public function getTimesent()
	{
		return $this->timesent;
	}
		
	public function setDocname($docname)
	{
		$this->docname = $docname;
	}

	public function getDocname()
	{
		return $this->docname;
	}
		
	public function setDoctitle($doctitle)
	{
		$this->doctitle = $doctitle;
	}

	public function getDoctitle()
	{
		return $this->doctitle;
	}

	public function getAll()
	{
		$ret = array(
		'addto'=>$this->getAddto(),
		'addname'=>$this->getAddname(),
		'subject'=>$this->getSubject(),
		'body'=>$this->getBody(),
		'sent'=>$this->getSent(),
		'incept'=>$this->getIncept(),
		'timesent'=>$this->getTimesent(),
		'docname'=>$this->getDocname(),
		'doctitle'=>$this->getDoctitle());
		return $ret;
	}

	public function loadMail() {
		global $db;
		if(!isset($this->mailid)){
			return "No Mail ID";
		}		
    	$res = $db->select('SELECT mailid,addto,addname,subject,body,sent,incept,timesent,docname,doctitle FROM mail WHERE mailid=?', array($this->mailid), 'd');		
		$r=$res[0];    
		$this->setMailid($r->mailid);
		$this->setAddto($r->addto);
		$this->setAddname($r->addname);
		$this->setSubject($r->subject);
		$this->setBody($r->body);
		$this->setSent($r->sent);
		$this->setIncept($r->incept);
		$this->setTimesent($r->timesent);
		$this->setDocname($r->docname);
		$this->setDoctitle($r->doctitle);

	}


	public function updateDB() {
		global $db;
		global $mailFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'mailid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $mailFormats[$key];
	        }
	    }
	     
	    $res = $db->update('mail', $data, $format, array('mailid'=>$this->mailid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $mailFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $mailFormats[$key];
	        }
	    }
		 $res = $db->insert('mail', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->mailid)){
			return "No Mail ID";
		}
        $res = $db->delete('mail', $this->mailid, 'mailid');
         
        return $res;
        
    }

}
	
class Owner
{
private $ownerid;
private $co_name;
private $addsid;
private $colour;
private $vat_no;
private $co_no;
private $athenaemail;
private $athenaemailpw;
private $emailsmtpsrv;
private $domain;
		
	public function setOwnerid($ownerid)
	{
		$this->ownerid = $ownerid;
	}

	public function getOwnerid()
	{
		return $this->ownerid;
	}
		
	public function setCo_name($co_name)
	{
		$this->co_name = $co_name;
	}

	public function getCo_name()
	{
		return $this->co_name;
	}
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setColour($colour)
	{
		$this->colour = $colour;
	}

	public function getColour()
	{
		return $this->colour;
	}
		
	public function setVat_no($vat_no)
	{
		$this->vat_no = $vat_no;
	}

	public function getVat_no()
	{
		return $this->vat_no;
	}
		
	public function setCo_no($co_no)
	{
		$this->co_no = $co_no;
	}

	public function getCo_no()
	{
		return $this->co_no;
	}
		
	public function setAthenaemail($athenaemail)
	{
		$this->athenaemail = $athenaemail;
	}

	public function getAthenaemail()
	{
		return $this->athenaemail;
	}
		
	public function setAthenaemailpw($athenaemailpw)
	{
		$this->athenaemailpw = $athenaemailpw;
	}

	public function getAthenaemailpw()
	{
		return $this->athenaemailpw;
	}
		
	public function setEmailsmtpsrv($emailsmtpsrv)
	{
		$this->emailsmtpsrv = $emailsmtpsrv;
	}

	public function getEmailsmtpsrv()
	{
		return $this->emailsmtpsrv;
	}
		
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}

	public function getDomain()
	{
		return $this->domain;
	}

	public function getAll()
	{
		$ret = array(
		'co_name'=>$this->getCo_name(),
		'addsid'=>$this->getAddsid(),
		'colour'=>$this->getColour(),
		'vat_no'=>$this->getVat_no(),
		'co_no'=>$this->getCo_no(),
		'athenaemail'=>$this->getAthenaemail(),
		'athenaemailpw'=>$this->getAthenaemailpw(),
		'emailsmtpsrv'=>$this->getEmailsmtpsrv(),
		'domain'=>$this->getDomain());
		return $ret;
	}

	public function loadOwner() {
		global $db;
		if(!isset($this->ownerid)){
			return "No Owner ID";
		}		
    	$res = $db->select('SELECT ownerid,co_name,addsid,colour,vat_no,co_no,athenaemail,athenaemailpw,emailsmtpsrv,domain FROM owner WHERE ownerid=?', array($this->ownerid), 'd');		
		$r=$res[0];    
		$this->setOwnerid($r->ownerid);
		$this->setCo_name($r->co_name);
		$this->setAddsid($r->addsid);
		$this->setColour($r->colour);
		$this->setVat_no($r->vat_no);
		$this->setCo_no($r->co_no);
		$this->setAthenaemail($r->athenaemail);
		$this->setAthenaemailpw($r->athenaemailpw);
		$this->setEmailsmtpsrv($r->emailsmtpsrv);
		$this->setDomain($r->domain);

	}


	public function updateDB() {
		global $db;
		global $ownerFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'ownerid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $ownerFormats[$key];
	        }
	    }
	     
	    $res = $db->update('owner', $data, $format, array('ownerid'=>$this->ownerid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $ownerFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $ownerFormats[$key];
	        }
	    }
		 $res = $db->insert('owner', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->ownerid)){
			return "No Owner ID";
		}
        $res = $db->delete('owner', $this->ownerid, 'ownerid');
         
        return $res;
        
    }

}
	
class Pwd
{
private $pwdid;
private $usr;
private $staffid;
private $contactsid;
private $seclev;
private $pw;
		
	public function setPwdid($pwdid)
	{
		$this->pwdid = $pwdid;
	}

	public function getPwdid()
	{
		return $this->pwdid;
	}
		
	public function setUsr($usr)
	{
		$this->usr = $usr;
	}

	public function getUsr()
	{
		return $this->usr;
	}
		
	public function setStaffid($staffid)
	{
		$this->staffid = $staffid;
	}

	public function getStaffid()
	{
		return $this->staffid;
	}
		
	public function setContactsid($contactsid)
	{
		$this->contactsid = $contactsid;
	}

	public function getContactsid()
	{
		return $this->contactsid;
	}
		
	public function setSeclev($seclev)
	{
		$this->seclev = $seclev;
	}

	public function getSeclev()
	{
		return $this->seclev;
	}
		
	public function setPw($pw)
	{
		$this->pw = $pw;
	}

	public function getPw()
	{
		return $this->pw;
	}

	public function getAll()
	{
		$ret = array(
		'usr'=>$this->getUsr(),
		'staffid'=>$this->getStaffid(),
		'contactsid'=>$this->getContactsid(),
		'seclev'=>$this->getSeclev(),
		'pw'=>$this->getPw());
		return $ret;
	}

	public function loadPwd() {
		global $db;
		if(!isset($this->pwdid)){
			return "No Pwd ID";
		}		
    	$res = $db->select('SELECT pwdid,usr,staffid,contactsid,seclev,pw FROM pwd WHERE pwdid=?', array($this->pwdid), 'd');		
		$r=$res[0];    
		$this->setPwdid($r->pwdid);
		$this->setUsr($r->usr);
		$this->setStaffid($r->staffid);
		$this->setContactsid($r->contactsid);
		$this->setSeclev($r->seclev);
		$this->setPw($r->pw);

	}


	public function updateDB() {
		global $db;
		global $pwdFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'pwdid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $pwdFormats[$key];
	        }
	    }
	     
	    $res = $db->update('pwd', $data, $format, array('pwdid'=>$this->pwdid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $pwdFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $pwdFormats[$key];
	        }
	    }
		 $res = $db->insert('pwd', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->pwdid)){
			return "No Pwd ID";
		}
        $res = $db->delete('pwd', $this->pwdid, 'pwdid');
         
        return $res;
        
    }

}
	
class Quotes
{
private $quotesid;
private $staffid;
private $custid;
private $contactsid;
private $incept;
private $agree;
private $live;
private $content;
private $notes;
private $origin;
private $price;
		
	public function setQuotesid($quotesid)
	{
		$this->quotesid = $quotesid;
	}

	public function getQuotesid()
	{
		return $this->quotesid;
	}
		
	public function setStaffid($staffid)
	{
		$this->staffid = $staffid;
	}

	public function getStaffid()
	{
		return $this->staffid;
	}
		
	public function setCustid($custid)
	{
		$this->custid = $custid;
	}

	public function getCustid()
	{
		return $this->custid;
	}
		
	public function setContactsid($contactsid)
	{
		$this->contactsid = $contactsid;
	}

	public function getContactsid()
	{
		return $this->contactsid;
	}
		
	public function setIncept($incept)
	{
		$this->incept = $incept;
	}

	public function getIncept()
	{
		return $this->incept;
	}
		
	public function setAgree($agree)
	{
		$this->agree = $agree;
	}

	public function getAgree()
	{
		return $this->agree;
	}
		
	public function setLive($live)
	{
		$this->live = $live;
	}

	public function getLive()
	{
		return $this->live;
	}
		
	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}
		
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getNotes()
	{
		return $this->notes;
	}
		
	public function setOrigin($origin)
	{
		$this->origin = $origin;
	}

	public function getOrigin()
	{
		return $this->origin;
	}
		
	public function setPrice($price)
	{
		$this->price = $price;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getAll()
	{
		$ret = array(
		'staffid'=>$this->getStaffid(),
		'custid'=>$this->getCustid(),
		'contactsid'=>$this->getContactsid(),
		'incept'=>$this->getIncept(),
		'agree'=>$this->getAgree(),
		'live'=>$this->getLive(),
		'content'=>$this->getContent(),
		'notes'=>$this->getNotes(),
		'origin'=>$this->getOrigin(),
		'price'=>$this->getPrice());
		return $ret;
	}

	public function loadQuotes() {
		global $db;
		if(!isset($this->quotesid)){
			return "No Quotes ID";
		}		
    	$res = $db->select('SELECT quotesid,staffid,custid,contactsid,incept,agree,live,content,notes,origin,price FROM quotes WHERE quotesid=?', array($this->quotesid), 'd');		
		$r=$res[0];    
		$this->setQuotesid($r->quotesid);
		$this->setStaffid($r->staffid);
		$this->setCustid($r->custid);
		$this->setContactsid($r->contactsid);
		$this->setIncept($r->incept);
		$this->setAgree($r->agree);
		$this->setLive($r->live);
		$this->setContent($r->content);
		$this->setNotes($r->notes);
		$this->setOrigin($r->origin);
		$this->setPrice($r->price);

	}


	public function updateDB() {
		global $db;
		global $quotesFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'quotesid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $quotesFormats[$key];
	        }
	    }
	     
	    $res = $db->update('quotes', $data, $format, array('quotesid'=>$this->quotesid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $quotesFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $quotesFormats[$key];
	        }
	    }
		 $res = $db->insert('quotes', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->quotesid)){
			return "No Quotes ID";
		}
        $res = $db->delete('quotes', $this->quotesid, 'quotesid');
         
        return $res;
        
    }

}
	
class Sitelog
{
private $sitelogid;
private $incept;
private $staffid;
private $content;
private $eventsid;
		
	public function setSitelogid($sitelogid)
	{
		$this->sitelogid = $sitelogid;
	}

	public function getSitelogid()
	{
		return $this->sitelogid;
	}
		
	public function setIncept($incept)
	{
		$this->incept = $incept;
	}

	public function getIncept()
	{
		return $this->incept;
	}
		
	public function setStaffid($staffid)
	{
		$this->staffid = $staffid;
	}

	public function getStaffid()
	{
		return $this->staffid;
	}
		
	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}
		
	public function setEventsid($eventsid)
	{
		$this->eventsid = $eventsid;
	}

	public function getEventsid()
	{
		return $this->eventsid;
	}

	public function getAll()
	{
		$ret = array(
		'incept'=>$this->getIncept(),
		'staffid'=>$this->getStaffid(),
		'content'=>$this->getContent(),
		'eventsid'=>$this->getEventsid());
		return $ret;
	}

	public function loadSitelog() {
		global $db;
		if(!isset($this->sitelogid)){
			return "No Sitelog ID";
		}		
    	$res = $db->select('SELECT sitelogid,incept,staffid,content,eventsid FROM sitelog WHERE sitelogid=?', array($this->sitelogid), 'd');		
		$r=$res[0];    
		$this->setSitelogid($r->sitelogid);
		$this->setIncept($r->incept);
		$this->setStaffid($r->staffid);
		$this->setContent($r->content);
		$this->setEventsid($r->eventsid);

	}


	public function updateDB() {
		global $db;
		global $sitelogFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'sitelogid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $sitelogFormats[$key];
	        }
	    }
	     
	    $res = $db->update('sitelog', $data, $format, array('sitelogid'=>$this->sitelogid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $sitelogFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $sitelogFormats[$key];
	        }
	    }
		 $res = $db->insert('sitelog', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->sitelogid)){
			return "No Sitelog ID";
		}
        $res = $db->delete('sitelog', $this->sitelogid, 'sitelogid');
         
        return $res;
        
    }

}
	
class Staff
{
private $staffid;
private $fname;
private $sname;
private $addsid;
private $jobtitle;
private $status;
private $lastlogin;
private $notes;
		
	public function setStaffid($staffid)
	{
		$this->staffid = $staffid;
	}

	public function getStaffid()
	{
		return $this->staffid;
	}
		
	public function setFname($fname)
	{
		$this->fname = $fname;
	}

	public function getFname()
	{
		return $this->fname;
	}
		
	public function setSname($sname)
	{
		$this->sname = $sname;
	}

	public function getSname()
	{
		return $this->sname;
	}
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setJobtitle($jobtitle)
	{
		$this->jobtitle = $jobtitle;
	}

	public function getJobtitle()
	{
		return $this->jobtitle;
	}
		
	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}
		
	public function setLastlogin($lastlogin)
	{
		$this->lastlogin = $lastlogin;
	}

	public function getLastlogin()
	{
		return $this->lastlogin;
	}
		
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getNotes()
	{
		return $this->notes;
	}

	public function getAll()
	{
		$ret = array(
		'fname'=>$this->getFname(),
		'sname'=>$this->getSname(),
		'addsid'=>$this->getAddsid(),
		'jobtitle'=>$this->getJobtitle(),
		'status'=>$this->getStatus(),
		'lastlogin'=>$this->getLastlogin(),
		'notes'=>$this->getNotes());
		return $ret;
	}

	public function loadStaff() {
		global $db;
		if(!isset($this->staffid)){
			return "No Staff ID";
		}		
    	$res = $db->select('SELECT staffid,fname,sname,addsid,jobtitle,status,lastlogin,notes FROM staff WHERE staffid=?', array($this->staffid), 'd');		
		$r=$res[0];    
		$this->setStaffid($r->staffid);
		$this->setFname($r->fname);
		$this->setSname($r->sname);
		$this->setAddsid($r->addsid);
		$this->setJobtitle($r->jobtitle);
		$this->setStatus($r->status);
		$this->setLastlogin($r->lastlogin);
		$this->setNotes($r->notes);

	}


	public function updateDB() {
		global $db;
		global $staffFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'staffid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $staffFormats[$key];
	        }
	    }
	     
	    $res = $db->update('staff', $data, $format, array('staffid'=>$this->staffid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $staffFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $staffFormats[$key];
	        }
	    }
		 $res = $db->insert('staff', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->staffid)){
			return "No Staff ID";
		}
        $res = $db->delete('staff', $this->staffid, 'staffid');
         
        return $res;
        
    }

}
	
class Supplier
{
private $suppid;
private $co_name;
private $contact;
private $addsid;
private $colour;
		
	public function setSuppid($suppid)
	{
		$this->suppid = $suppid;
	}

	public function getSuppid()
	{
		return $this->suppid;
	}
		
	public function setCo_name($co_name)
	{
		$this->co_name = $co_name;
	}

	public function getCo_name()
	{
		return $this->co_name;
	}
		
	public function setContact($contact)
	{
		$this->contact = $contact;
	}

	public function getContact()
	{
		return $this->contact;
	}
		
	public function setAddsid($addsid)
	{
		$this->addsid = $addsid;
	}

	public function getAddsid()
	{
		return $this->addsid;
	}
		
	public function setColour($colour)
	{
		$this->colour = $colour;
	}

	public function getColour()
	{
		return $this->colour;
	}

	public function getAll()
	{
		$ret = array(
		'co_name'=>$this->getCo_name(),
		'contact'=>$this->getContact(),
		'addsid'=>$this->getAddsid(),
		'colour'=>$this->getColour());
		return $ret;
	}

	public function loadSupplier() {
		global $db;
		if(!isset($this->suppid)){
			return "No Supplier ID";
		}		
    	$res = $db->select('SELECT suppid,co_name,contact,addsid,colour FROM supplier WHERE suppid=?', array($this->suppid), 'd');		
		$r=$res[0];    
		$this->setSuppid($r->suppid);
		$this->setCo_name($r->co_name);
		$this->setContact($r->contact);
		$this->setAddsid($r->addsid);
		$this->setColour($r->colour);

	}


	public function updateDB() {
		global $db;
		global $supplierFormats;
		
	    $format = '';
	    foreach($this as $key => $value) {
	    	if($key == 'suppid'){continue;}
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $supplierFormats[$key];
	        }
	    }
	     
	    $res = $db->update('supplier', $data, $format, array('suppid'=>$this->suppid), 'i');
	    
	    return $res;
	}



	public function insertIntoDB() {
		global $db;
		global $supplierFormats;
	    $format = '';
		foreach($this as $key => $value) {	    	
	        if (isset($this->$key)) {
	            $data[$key] = $value;
	            $format .= $supplierFormats[$key];
	        }
	    }
		 $res = $db->insert('supplier', $data, $format);
	    
	    return $res;
		
	}


	 public function deleteFromDB() {

        global $db;
        
        if(!isset($this->suppid)){
			return "No Supplier ID";
		}
        $res = $db->delete('supplier', $this->suppid, 'suppid');
         
        return $res;
        
    }

}


 $addressFormats= array(
"addsid" => "i",
"add1" => "s",
"add2" => "s",
"add3" => "s",
"city" => "s",
"county" => "s",
"country" => "s",
"postcode" => "s",
"tel" => "s",
"mob" => "s",
"fax" => "s",
"email" => "s",
"web" => "s",
"facebook" => "s",
"twitter" => "s",
"linkedin" => "s");


 $contactsFormats= array(
"contactsid" => "i",
"title" => "s",
"fname" => "s",
"sname" => "s",
"co_name" => "s",
"role" => "s",
"custid" => "i",
"suppid" => "i",
"addsid" => "i",
"notes" => "s",
"lastlogin" => "i");


 $customerFormats= array(
"custid" => "i",
"co_name" => "s",
"contact" => "s",
"addsid" => "i",
"colour" => "s");


 $eventsFormats= array(
"eventsid" => "i",
"name" => "s");


 $invoicesFormats= array(
"invoicesid" => "i",
"custid" => "i",
"contactsid" => "i",
"incept" => "i",
"paid" => "i",
"content" => "s",
"price" => "d",
"notes" => "s");


 $mailFormats= array(
"mailid" => "i",
"addto" => "s",
"addname" => "s",
"subject" => "s",
"body" => "s",
"sent" => "i",
"incept" => "i",
"timesent" => "i",
"docname" => "s",
"doctitle" => "s");


 $ownerFormats= array(
"ownerid" => "i",
"co_name" => "s",
"addsid" => "i",
"colour" => "s",
"vat_no" => "s",
"co_no" => "s",
"athenaemail" => "s",
"athenaemailpw" => "s",
"emailsmtpsrv" => "s",
"domain" => "s");


 $pwdFormats= array(
"pwdid" => "i",
"usr" => "s",
"staffid" => "i",
"contactsid" => "i",
"seclev" => "i",
"pw" => "s");


 $quotesFormats= array(
"quotesid" => "i",
"staffid" => "i",
"custid" => "i",
"contactsid" => "i",
"incept" => "i",
"agree" => "i",
"live" => "i",
"content" => "s",
"notes" => "s",
"origin" => "s",
"price" => "d");


 $sitelogFormats= array(
"sitelogid" => "i",
"incept" => "i",
"staffid" => "i",
"content" => "s",
"eventsid" => "i");


 $staffFormats= array(
"staffid" => "i",
"fname" => "s",
"sname" => "s",
"addsid" => "i",
"jobtitle" => "s",
"status" => "s",
"lastlogin" => "i",
"notes" => "s");


 $supplierFormats= array(
"suppid" => "i",
"co_name" => "s",
"contact" => "s",
"addsid" => "i",
"colour" => "s");



?>