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

class TSL2561 extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    
     /* Fonction exécutée automatiquement toutes les minutes par Jeedom*/
      public static function cron() {
        foreach (self::byType('TSL2561') as $TSL2561) { //parcours tous les équipements du plugin vdm
            if ($TSL2561->getIsEnable() == 1) { //vérifie que l'équipement est actif
                foreach ($TSL2561->getCmd('info') as $cmd) {
                    log::add('TSL2561', 'debug', 'cron execute');
                    $cmd->execCmd(); // la commande existe on la lance
                }
            }
        }
      }


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
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

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    public static function dependancy_info() {
        $return = array();
        $return['progress_file'] = jeedom::getTmpFolder('TLS2561') . '/dependance';
        $return['state'] = 'ok';
        if (exec(system::getCmdSudo() . "python3 -c 'import adafruit_tsl2561' 2>/dev/null && echo oui || echo non ") == 'non') $return['state'] = 'nok'; 
        if ($return['state'] == 'nok') message::add('TLS2561', __('Si les dépendances sont/restent NOK, veuillez mettre à jour votre système linux, puis relancer l\'installation des dépendances générales. Merci', __FILE__));
        return $return;
        }

    public static function dependancy_install() {
		log::remove(__CLASS__ . '_update');
		return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder('TLS2561') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
	}

    public function getlux(){
    /*    $gain = $this->getConfiguration('gain');
        $inte_time = $this->getConfiguration('integration_time');
        $lux = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 1');
        log::add('TSL2561', 'debug', 'getLux');
        return $lux;*/
        return 10;
    }

    public function getbroadband(){
    /*    $gain = $this->getConfiguration('gain');
        $inte_time = $this->getConfiguration('integration_time');
        $broadband = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 2');
        log::add('TSL2561', 'debug', 'getBroadband');
        return $broadband; */
        return 50;
    }

    public function getinfrared(){
    /*    $gain = $this->getConfiguration('gain');
        $inte_time = $this->getConfiguration('integration_time');
        $infrared = exec(system::getCmdSudo() . 'python3 html/plugins/TSL2561/core/py/./TSL2561.py '. $gain .' '. $inte_time .' 3');
        log::add('TSL2561', 'debug', 'getInfrared');
        return $infrared; */
        return 20;
    }

    /*     * **********************Getteur Setteur*************************** */
}

class TSL2561Cmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {

        $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
        $info = $eqlogic->getlux();  //On lance la fonction randomVdm() pour récupérer une vdm et on la stocke dans la variable $info
        log::add('TLS2561','debug','eqlogic ' . $eqlogic->getname() . 'Info ' . $info);
        $eqlogic->checkAndUpdateCmd('Lux', $info); // on met à jour la commande avec le LogicalId de l'eqlogic
        $info = $eqlogic->getbroadband();
        $eqlogic->checkAndUpdateCmd('Broadband', $info); // on met à jour la commande avec le LogicalId de l'eqlogic
        $info = $eqlogic->getinfrared();
        $eqlogic->checkAndUpdateCmd('Infrared', $info);
    }

    /*     * **********************Getteur Setteur*************************** */
}


