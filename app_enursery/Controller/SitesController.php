<?php

class SitesController extends AppController
{

    var $name = 'Sites';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('sitemap', 'suspended', 'under_maintenance', 'getSiteList', 'robot', 'routemap');
    }


    function getSiteList()
    {
        return $this->Site->find('list');
    }

    function sitemap()
    {
        $this->layout = null;
        $this->response->type('xml');
        $categoryProducts = $this->getSiteCategoriesProductsImages();
        $this->set(compact('categoryProducts'));
    }

    function routemap()
    {
        $this->layout = 'default';

        $hideLeftMenu = true;
        $this->set(compact('hideLeftMenu'));
    }

    function suspended()
    {
        $this->layout = 'suspended';
    }

    function under_maintenance()
    {
        $this->layout = 'undermaintenance';
    }

    function admin_edit()
    {
        $siteID = $this->Session->read('Site.id');
        $siteInfo = $this->Site->findById($siteID);
        $serviceTypes = array();
        $serviceTypes = $this->Site->find('all', array('conditions' => array('Site.service_type NOT' => 'NULL'), 'fields' => array('DISTINCT Site.service_type'), 'recursive' => '-1'));

        $errorMsg = array();
        if ($this->request->isPut() or $this->request->isPost()) {
            $data['Site'] = $this->data['Site'];

            // validate Site Title
            if (Validation::blank($data['Site']['title'])) {
                $errorMsg[] = 'Enter Site Title';
            } elseif (!Validation::between($data['Site']['title'], 3, 50)) {
                $errorMsg[] = 'Site title should be 3 to 50 chars long';
            }
            if (!Validation::blank($data['Site']['caption'])) {
                if (!Validation::between($data['Site']['caption'], 3, 100)) {
                    $errorMsg[] = 'Site caption should be 3 to 100 chars long';
                }
            }

            // validate phone
            if (Validation::blank($data['Site']['contact_phone'])) {
                $errorMsg[] = 'Enter Phone No.';
            } elseif (!Validation::between($data['Site']['contact_phone'], 10, 55)) {
                $errorMsg[] = 'Phone No. should contain min 10 & max 55 digits';
            }

            // validate user email
            if (Validation::blank($data['Site']['contact_email'])) {
                $errorMsg[] = 'Enter Email Address';
            } elseif (!(Validation::email($data['Site']['contact_email']))) {
                $errorMsg[] = 'Invalid Email Address';
            }

            // validate address
            if (Validation::blank($data['Site']['address'])) {
                $errorMsg[] = 'Enter Contact Address';
            }

            if (empty($errorMsg)) {
                // Sanitize data
                $data['Site']['id'] = $siteID;
                $data['Site']['title'] = htmlentities($this->data['Site']['title']);
                $data['Site']['caption'] = htmlentities($this->data['Site']['caption']);
                $data['Site']['service_type'] = htmlentities($this->data['Site']['service_type']);
                $data['Site']['meta_keywords'] = htmlentities($this->data['Site']['meta_keywords']);
                $data['Site']['meta_description'] = htmlentities($this->data['Site']['meta_description']);
                $data['Site']['address'] = $this->data['Site']['address'];

                if (!$data['Site']['show_landing_page'] and !$data['Site']['show_products']) {
                    $data['Site']['image_gallery'] = '0';
                }

                if ($this->Site->save($data)) {
                    $this->Session->setFlash('Data saved successfully', 'default', array('class' => 'success'));
                    $this->redirect('/admin/sites/');
                } else {
                    $errorMsg[] = 'Failed to save data';
                }
            }
        } else {
            $this->data = $siteInfo;
        }
        $errorMsg = implode('<br/>', $errorMsg);
        $this->set(compact('siteInfo', 'errorMsg', 'serviceTypes'));
    }

    function admin_addDomain($siteID)
    {
        if ($this->request->isPost()) {
            App::uses('Domain', 'Model');
            $this->Domain = new Domain;
            $error = false;
            $data['Domain'] = $this->request->data['Domain'];
            if (empty($data['Domain']['name'])) {
                $error = true;
                $this->Session->setFlash('Enter Domain Name', 'default', array('class' => 'error'));
            } elseif ($this->Domain->findByName($data['Domain']['name'])) {
                $error = true;
                $this->Session->setFlash('Domain "' . $data['Domain']['name'] . '" is already registered', 'default', array('class' => 'error'));
            }

            if (!$error) {
                $data['Domain']['id'] = null;
                $data['Domain']['site_id'] = $siteID;
                if ($this->Domain->save($data)) {
                    $this->Session->setFlash('Domain name added successfully', 'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('Failed to create domain name', 'default', array('class' => 'error'));
                }
            }
        } else {
            $this->Session->setFlash('You are not authorized to perform this action', 'default', array('class' => 'error'));
        }
        $this->redirect('/admin/sites/edit/' . $siteID);
    }

    function admin_deleteDomain($domainID, $siteID)
    {
        if ($this->request->isGet()) {
            App::uses('Domain', 'Model');
            $this->Domain = new Domain;
            $domainInfo = $this->Domain->findById($domainID);
            if ($domainInfo) {
                if ($domainInfo['Domain']['default']) {
                    $this->Session->setFlash('You cannot delete a default domain', 'default', array('class' => 'error'));
                } else {
                    if ($this->Domain->delete($domainID)) {
                        $this->Session->setFlash('Domain name deleted successfully', 'default', array('class' => 'success'));
                    } else {
                        $this->Session->setFlash('Failed to delete domain name', 'default', array('class' => 'error'));
                    }
                }
            } else {
                $this->Session->setFlash('Domain not found', 'default', array('class' => 'error'));
            }
        } else {
            $this->Session->setFlash('You are not authorized to perform this action', 'default', array('class' => 'error'));
        }
        $this->redirect('/admin/sites/edit/' . $siteID);
    }

    function admin_setDefaultDomain($domainID, $siteID)
    {
        if ($this->request->isGet()) {
            App::uses('Domain', 'Model');
            $this->Domain = new Domain;
            $domainInfo = $this->Domain->findById($domainID);
            if ($domainInfo) {
                if ($domainInfo['Domain']['default']) {
                    $this->Session->setFlash('You cannot delete a default domain', 'default', array('class' => 'error'));
                } else {
                    // reset all domains
                    $conditions = array('Domain.site_id' => $siteID);
                    $domains = $this->Domain->findAllBySiteId($siteID);
                    foreach ($domains as $row) {
                        $data = array();
                        $data['Domain']['id'] = $row['Domain']['id'];
                        $data['Domain']['site_id'] = $siteID;
                        $data['Domain']['default'] = '0';
                        $this->Domain->save($data);
                    }

                    // make the selected domain default
                    $data = array();
                    $data['Domain']['id'] = $domainID;
                    $data['Domain']['default'] = true;
                    if ($this->Domain->save($data)) {
                        $this->Session->setFlash('Domain successfully set to default', 'default', array('class' => 'success'));
                    } else {
                        $this->Session->setFlash('Failed to set domain as default', 'default', array('class' => 'error'));
                    }
                }
            } else {
                $this->Session->setFlash('Domain not found', 'default', array('class' => 'error'));
            }
        } else {
            $this->Session->setFlash('You are not authorized to perform this action', 'default', array('class' => 'error'));
        }
        $this->redirect('/admin/sites/edit/' . $siteID);
    }

    function admin_index()
    {
        if ($siteInfo = $this->Site->findByUserId($this->Session->read('User.id'))) {
            $this->data = $siteInfo;
            $this->set(compact('siteInfo'));
        } else {
            $this->Session->setFlash('Site not found', 'default', array('class' => 'error'));
            $this->redirect('/');
        }
    }

    function robot()
    {
        error_reporting(0);
        $this->layout = false;

        $url = 'http://';
        $url .= $this->request->host();
        $url .= '/sitemap';
        $sitemapUrl = 'Sitemap: ' . $url;


        header("Content-Type:text/plain");
        echo $sitemapUrl . "

User-agent: *
Disallow:
		";
        exit;
    }
}

?>
