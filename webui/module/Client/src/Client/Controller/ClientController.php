<?php

/**
 *
 * bareos-webui - Bareos Web-Frontend
 *
 * @link      https://github.com/bareos/bareos for the canonical source repository
 * @copyright Copyright (c) 2013-2023 Bareos GmbH & Co. KG (http://www.bareos.org/)
 * @license   GNU Affero General Public License (http://www.gnu.org/licenses/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Client\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class ClientController extends AbstractActionController
{

  /**
   * Variables
   */
  protected $clientModel = null;
  protected $directorModel = null;
  protected $jobModel = null;
  protected $bsock = null;
  protected $acl_alert = false;

  /**
   * Index Action
   *
   * @return object
   */
  public function indexAction()
  {
    $this->RequestURIPlugin()->setRequestURI();

    if(!$this->SessionTimeoutPlugin()->isValid()) {
      return $this->redirect()->toRoute(
        'auth',
        array(
          'action' => 'login'
        ),
        array(
          'query' => array(
            'req' => $this->RequestURIPlugin()->getRequestURI(),
            'dird' => $_SESSION['bareos']['director']
          )
        )
      );
    }

    $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
    $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
      $module_config['console_commands']['Client']['mandatory']
    );
    if(count($invalid_commands) > 0) {
      $this->acl_alert = true;
      return new ViewModel(
        array(
          'acl_alert' => $this->acl_alert,
          'invalid_commands' => implode(",", $invalid_commands)
        )
      );
    }

    $result = null;

    $action = $this->params()->fromQuery('action');

    if(empty($action)) {
      return new ViewModel();
    }
    else {
      try {
        $this->bsock = $this->getServiceLocator()->get('director');
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }

      if($action == "enable") {
        $clientname = $this->params()->fromQuery('client');
        try {
          $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
          $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
            $module_config['console_commands']['Client']['optional']
          );
          if(count($invalid_commands) > 0 && in_array('enable', $invalid_commands)) {
            $this->acl_alert = true;
            return new ViewModel(
              array(
                'acl_alert' => $this->acl_alert,
                'invalid_commands' => 'enable'
              )
            );
          } else {
            $result = $this->getClientModel()->enableClient($this->bsock, $clientname);
          }
        }
        catch(Exception $e) {
          echo $e->getMessage();
        }
      }
      elseif($action == "disable") {
        $clientname = $this->params()->fromQuery('client');
        try {
          $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
          $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
            $module_config['console_commands']['Client']['optional']
          );
          if(count($invalid_commands) > 0 && in_array('disable', $invalid_commands)) {
            $this->acl_alert = true;
            return new ViewModel(
              array(
                'acl_alert' => $this->acl_alert,
                'invalid_commands' => 'disable'
              )
            );
          } else {
            $result = $this->getClientModel()->disableClient($this->bsock, $clientname);
          }
        }
        catch(Exception $e) {
          echo $e->getMessage();
        }
      }

      try {
        $this->bsock->disconnect();
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }

      return new ViewModel(
        array(
          'result' => $result
        )
      );
    }
  }

  /**
   * Details Action
   *
   * @return object
   */
  public function detailsAction()
  {
    $this->RequestURIPlugin()->setRequestURI();

    if(!$this->SessionTimeoutPlugin()->isValid()) {
      return $this->redirect()->toRoute(
        'auth',
        array(
          'action' => 'login'
        ),
        array(
          'query' => array(
            'req' => $this->RequestURIPlugin()->getRequestURI(),
            'dird' => $_SESSION['bareos']['director']
          )
        )
      );
    }

    $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
    $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
      $module_config['console_commands']['Client']['mandatory']
    );
    if(count($invalid_commands) > 0){
      $this->acl_alert = true;
      return new ViewModel(
        array(
          'acl_alert' => $this->acl_alert,
          'invalid_commands' => $invalid_commands
        )
      );
    }

    return new ViewModel(
      array(
        'client' => $this->params()->fromQuery('client')
      )
    );
  }

  /**
   * Status Action
   *
   * @return object
   */
  public function statusAction()
  {
    $this->RequestURIPlugin()->setRequestURI();

    if(!$this->SessionTimeoutPlugin()->isValid()) {
      return $this->redirect()->toRoute(
        'auth',
        array(
          'action' => 'login'
        ),
        array(
          'query' => array(
            'req' => $this->RequestURIPlugin()->getRequestURI(),
            'dird' => $_SESSION['bareos']['director']
          )
        )
      );
    }

    $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
    $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
      $module_config['console_commands']['Client']['optional']
    );
    if(count($invalid_commands) > 0 && in_array('status', $invalid_commands)) {
      $this->acl_alert = true;
      return new ViewModel(
        array(
          'acl_alert' => $this->acl_alert,
          'invalid_commands' => 'status'
        )
      );
    }

    $result = null;

    $clientname = $this->params()->fromQuery('client');

    try {
      $this->bsock = $this->getServiceLocator()->get('director');
      $result = $this->getClientModel()->statusClient($this->bsock, $clientname);
      $this->bsock->disconnect();
    }
    catch(Exception $e) {
      echo $e->getMessage();
    }

    return new ViewModel(
      array(
        'result' => $result
      )
    );
  }

  public function timelineAction()
  {
    $this->RequestURIPlugin()->setRequestURI();

    if(!$this->SessionTimeoutPlugin()->isValid()) {
      return $this->redirect()->toRoute(
        'auth',
        array(
          'action' => 'login'
        ),
        array(
          'query' => array(
            'req' => $this->RequestURIPlugin()->getRequestURI(),
            'dird' => $_SESSION['bareos']['director']
          )
        )
      );
    }

    $module_config = $this->getServiceLocator()->get('ModuleManager')->getModule('Application')->getConfig();
    $invalid_commands = $this->CommandACLPlugin()->getInvalidCommands(
      $module_config['console_commands']['Job']['mandatory']
    );
    if(count($invalid_commands) > 0) {
      $this->acl_alert = true;
      return new ViewModel(
        array(
          'acl_alert' => $this->acl_alert,
          'invalid_commands' => implode(",", $invalid_commands)
        )
      );
    }

    $this->bsock = $this->getServiceLocator()->get('director');

    return new ViewModel();
  }

  public function getDataAction()
  {
    $this->RequestURIPlugin()->setRequestURI();

    if(!$this->SessionTimeoutPlugin()->isValid()) {
      return $this->redirect()->toRoute(
        'auth',
        array(
          'action' => 'login'
        ),
        array(
          'query' => array(
            'req' => $this->RequestURIPlugin()->getRequestURI(),
            'dird' => $_SESSION['bareos']['director']
          )
        )
      );
    }

    $result = null;

    $data = $this->params()->fromQuery('data');
    $client = $this->params()->fromQuery('client');
    $clients = $this->params()->fromQuery('clients');
    $period = $this->params()->fromQuery('period');

    if($data == "all") {
      try {
        $this->bsock = $this->getServiceLocator()->get('director');
        $clients = $this->getClientModel()->getClients($this->bsock);
        $dot_clients = $this->getClientModel()->getDotClients($this->bsock);
        $this->bsock->disconnect();
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }

      $result = array();

      for($i = 0; $i < count($clients); $i++) {
        $result[$i]['clientid'] = $clients[$i]['clientid'];
        $result[$i]['uname'] = $clients[$i]['uname'];
        $result[$i]['name'] = $clients[$i]['name'];
        $result[$i]['autoprune'] = $clients[$i]['autoprune'];
        $result[$i]['fileretention'] = $clients[$i]['fileretention'];
        $result[$i]['jobretention'] = $clients[$i]['jobretention'];
        $uname = explode(",", $clients[$i]['uname']);
        $v = explode(" ", $uname[0]);
        $result[$i]['version'] = $v[0];
        for($j = 0; $j < count($dot_clients); $j++) {
          if($result[$i]['name'] == $dot_clients[$j]['name']) {
            $result[$i]['enabled'] = $dot_clients[$j]['enabled'];
          }
        }
      }
    }
    elseif($data == "details" && isset($client)) {
      try {
        $this->bsock = $this->getServiceLocator()->get('director');
        $result = $this->getClientModel()->getClient($this->bsock, $client);
        $this->bsock->disconnect();
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }
    }
    elseif($data == "jobs" && isset($client)) {
      try {
        $this->bsock = $this->getServiceLocator()->get('director');
        $result = $this->getClientModel()->getClientJobs($this->bsock, $client, null, 'desc', null);
        $this->bsock->disconnect();
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }
    }
    elseif($data == "client-timeline") {
      try {
        $result = [];
        $c = explode(",", $clients);

        $this->bsock = $this->getServiceLocator()->get('director');
        foreach($c as $client) {
          $result = array_merge($result, $this->getJobModel()->getClientJobsForPeriod($this->bsock, $client, $period));
        }
        $this->bsock->disconnect();

        $jobs = array();

        // Ensure a proper date.timezone setting for the job timeline.
        // Surpress a possible error thrown by date_default_timezone_get()
        // in older PHP versions with @ in front of the function call.
        date_default_timezone_set(@date_default_timezone_get());

        foreach($result as $job) {

          $starttime = new \DateTime($job['starttime']);
          $endtime = new \DateTime($job['endtime']);
          $schedtime = new \DateTime($job['schedtime']);

          $starttime = $starttime->format('U')*1000;
          $endtime = $endtime->format('U')*1000;
          $schedtime = $schedtime->format('U')*1000;

          switch($job['jobstatus']) {
            // SUCESS
            case 'T':
              $fillcolor = "#5cb85c";
              break;
            // WARNING
            case 'A':
            case 'W':
              $fillcolor = "#f0ad4e";
              break;
            // RUNNING
            case 'R':
            case 'l':
              $fillcolor = "#5bc0de";
              $endtime = new \DateTime(null);
              $endtime = $endtime->format('U')*1000;
              break;
            // FAILED
            case 'E':
            case 'e':
            case 'f':
              $fillcolor = "#d9534f";
              break;
            // WAITING
            case 'F':
            case 'S':
            case 's':
            case 'M':
            case 'm':
            case 'j':
            case 'C':
            case 'c':
            case 'd':
            case 't':
            case 'p':
            case 'q':
              $fillcolor = "#555555";
              $endtime = new \DateTime(null);
              $endtime = $endtime->format('U')*1000;
              break;
            default:
              $fillcolor = "#555555";
              break;
            }

            // workaround to display short job runs <= 1 sec.
            if($starttime === $endtime) {
              $endtime += 1000;
            }

            $item = '{"x":"'.$job['client'].'","y":["'.$starttime.'","'.$endtime.'"],"fillColor":"'.$fillcolor.'","name":"'.$job['name'].'","jobid":"'.$job['jobid'].'","starttime":"'.$job['starttime'].'","endtime":"'.$job['endtime'].'","schedtime":"'.$job['schedtime'].'","client":"'.$job['client'].'"}';
            array_push($jobs, json_decode($item));

        }

        $result = $jobs;

      } catch(Exception $e) {
        echo $e->getMessage();
      }
    }

    $response = $this->getResponse();
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

    if(isset($result)) {
      $response->setContent(JSON::encode($result));
    }

    return $response;
  }

  /**
   * Get Client Model
   *
   * @return object
   */
  public function getClientModel()
  {
    if(!$this->clientModel) {
      $sm = $this->getServiceLocator();
      $this->clientModel = $sm->get('Client\Model\ClientModel');
    }
    return $this->clientModel;
  }

  /**
   * Get Director Model
   *
   * @return object
   */
  public function getDirectorModel()
  {
    if(!$this->directorModel) {
      $sm = $this->getServiceLocator();
      $this->directorModel = $sm->get('Director\Model\DirectorModel');
    }
    return $this->directorModel;
  }

  public function getJobModel()
  {
    if(!$this->jobModel) {
      $sm = $this->getServiceLocator();
      $this->jobModel = $sm->get('Job\Model\JobModel');
    }
    return $this->jobModel;
  }
}
