<?php
namespace Api\Modules\Account\Models;

use Api\Models\AppModel;

/**
 * /Api/Modules/Account/Models/User.php
 * 
 * User
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Modules/Account/Models
 * @author     Diego Luis Restrepo <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
 */
class User extends AppModel {

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    public $id = 0;

    /**
     * @Related(type="belongsTo", model="Api\Modules\Account\Models\Group")
     * @Column(type="integer", nullable=false)
     */
    public $group_id = 0;

    /**
     * @Unique
     * @Column(type="email", nullable=false)
     */
    public $username = '';

    /**
     * @Column(type="string", nullable=false, max=100)
     */
    public $password = '';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $created_at = '0000-00-00 00:00:00';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $modified_at = '0000-00-00 00:00:00';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $deleted_at = '0000-00-00 00:00:00';

    /**
     * 
     * initialize
     *
     * @return void
    */
    public function initialize() {
        $this->belongsTo('group_id', '\Api\Modules\Account\Models\Group', 'id', array(
            'alias' => 'Group'
        ));
        $this->useDynamicUpdate(true);
        return;
    }
    /**
     * 
     * getSource
     *
     * @return void
    */
    public function getSource() {
        return "users";
    }
    /**
     * 
     * beforeCreate
     *
     * @return void
    */
    public function beforeCreate() {
        $this->created_at = date('Y-m-d H:i:s');
        $this->modified_at = date('Y-m-d H:i:s');
        $this->deleted_at = '0000-00-00 00:00:00';
    }
    /**
     * 
     * beforeUpdate
     *
     * @return void
    */
    public function beforeUpdate() {
        $this->modified_at = date('Y-m-d H:i:s');
    }
    /**
     * 
     * validation
     *
     * @return $this->validationHasFailed
    */
    public function validation() {
        parent::validation();
        return $this->validationHasFailed() != true;
    }
    /**
     * 
     * getValidUser
     *
     * @return null
    */
    public static function getValidUser($id) {
        $user = self::findFirst(
            array(
                'id = :user_id: AND deleted_at = :deleted:',
                'bind' => array(
                    'user_id' => $id,
                    'deleted' => '0000-00-00 00:00:00',
                )
            )
        );

        if(is_object($user)){
            $usr = array();
            $usr['id'] = $user->id;
            $usr['username'] = $user->username;
            $usr['created_at'] = $user->created_at;
            $usr['group']['id'] = $user->group_id;
            $usr['group']['name'] = $user->Group->name;

            return $usr;
        }
        return null;
    }

}