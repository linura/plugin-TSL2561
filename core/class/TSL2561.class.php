<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class TSL2561 extends eqLogic
{

    /* Fonction exécutée automatiquement toutes les minutes par Jeedom*/
 
    public static function cron(){
        foreach (eqLogic::byType('TSL2561') as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                foreach ($eqLogic->getCmd('info') as $cmd) {
                    log::add('TSL2561', 'debug', 'Cron lancé');
                  $cmd->execute();
                   
                }
            }
        }
    }
    
    public function preInsert()
    {
    }

    public function postInsert()
    {
    }

    public function preSave()
    {
    }

    public function postSave()
    {
        $info = $this->getCmd(null, 'Lux');
        if (!is_object($info)) {
            $info = new TSL2561Cmd();
            $info->setName(__('Lux', __FILE__));
        }
        $info->setLogicalId('Lux');
        $info->setEqLogic_id($this->getId());
        $info->setType('info');
        $info->setSubType('numeric');
        $info->save();

        $info = $this->getCmd(null, 'Broadband');
        if (!is_object($info)) {
            $info = new TSL2561Cmd();
            $info->setName(__('Broadband', __FILE__));
        }
        $info->setLogicalId('Broadband');
        $info->setEqLogic_id($this->getId());
        $info->setType('info');
        $info->setSubType('numeric');
        $info->save();

        $info = $this->getCmd(null, 'Infrared');
        if (!is_object($info)) {
            $info = new TSL2561Cmd();
            $info->setName(__('Infrared', __FILE__));
        }
        $info->setLogicalId('Infrared');
        $info->setEqLogic_id($this->getId());
        $info->setType('info');
        $info->setSubType('numeric');
        $info->save();

    }

    public function preUpdate()
    {
    }

    public function postUpdate()
    {
    }

    public function preRemove()
    {
    }

    public function postRemove()
    {
    }

    public static function dependancy_info()
    {
        $return = array();
        $return['progress_file'] = jeedom::getTmpFolder('TLS2561') . '/dependance';
        $return['state'] = 'ok';
        if (exec(system::getCmdSudo() . "python3 -c 'import adafruit_tsl2561' 2>/dev/null && echo oui || echo non ") == 'non') $return['state'] = 'nok';
        if ($return['state'] == 'nok') message::add('TLS2561', __('Si les dépendances sont/restent NOK, veuillez mettre à jour votre système linux, puis relancer l\'installation des dépendances générales. Merci', __FILE__));
        return $return;
    }

    public static function dependancy_install()
    {
        log::remove(__CLASS__ . '_update');
        return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder('TLS2561') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
    }

    public function getlux()
    {
        $gain = $this->getConfiguration('Gain');
        $inte_time = $this->getConfiguration('int_time');
        $nb_decimal = $this->getConfiguration('decimal');
        log::add('TSL2561', 'debug', 'gain '. $gain);
        log::add('TSL2561', 'debug', 'time '. $inte_time);
        log::add('TSL2561', 'debug', 'nb_decimal '. $nb_decimal);
        $lux = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 1');
        log::add('TSL2561', 'debug', 'getLux '. $lux);
        return number_format($lux,$nb_decimal);
        /*log::add('TSL2561', 'debug', 'getLux');
        return 30;*/
    }

    public function getbroadband()
    {
        $gain = $this->getConfiguration('Gain');
        $inte_time = $this->getConfiguration('int_time');
        $broadband = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 2');
        log::add('TSL2561', 'debug', 'getBroadband ' . $broadband);
        return $broadband; 
      	/*log::add('TSL2561', 'debug', 'getBroadband');
        return 250;*/
    }

    public function getinfrared()
    {
        $gain = $this->getConfiguration('Gain');
        $inte_time = $this->getConfiguration('int_time');
        $infrared = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 3');
        log::add('TSL2561', 'debug', 'getInfrared ' . $infrared);
        return $infrared; 
      	/*log::add('TSL2561', 'debug', 'getInfrared');
        return 100;*/
    }

    /*     * **********************Getteur Setteur*************************** */
}

class TSL2561Cmd extends cmd
{

    public function execute($_options = array())
    {
        $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
      	switch ($this->getLogicalId()) {    //vérifie le logicalid de la commande
          case 'Lux':
        	$info = $eqlogic->getlux();  //On lance la fonction randomVdm() pour récupérer une vdm et on la stocke dans la variable $info
        	$eqlogic->checkAndUpdateCmd('Lux', $info); // on met à jour la commande avec le LogicalId de l'eqlogic.
            break;
          case 'Broadband':
        	$info = $eqlogic->getbroadband();
        	$eqlogic->checkAndUpdateCmd('Broadband', $info); // on met à jour la commande avec le LogicalId de l'eqlogic
            break;
            case 'Infrared':
        		$info = $eqlogic->getinfrared();
             	$eqlogic->checkAndUpdateCmd('Infrared', $info);
            break;
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}
